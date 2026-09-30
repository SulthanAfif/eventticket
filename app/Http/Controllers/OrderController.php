<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.ticketType.event'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $orders->each(fn (Order $order) => $this->expireIfNeeded($order));

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $this->expireIfNeeded($order);
        $this->syncFromMidtrans($order);

        $order->refresh()->load(['items.ticketType.event', 'items.tickets']);

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_type_id' => 'required|exists:ticket_types,id',
            'tickets.*.qty' => 'required|integer|min:0|max:10',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                $total = 0;
                $lines = [];

                foreach ($request->tickets as $row) {
                    $qty = (int) ($row['qty'] ?? 0);
                    if ($qty <= 0) {
                        continue;
                    }

                    $type = TicketType::lockForUpdate()->findOrFail($row['ticket_type_id']);

                    if (! $type->isAvailable() || $type->remaining() < $qty) {
                        throw new \Exception("Tiket {$type->name} tidak cukup / tidak tersedia.");
                    }

                    if ($type->event->status !== 'published') {
                        throw new \Exception('Event tidak tersedia.');
                    }

                    $lines[] = ['type' => $type, 'qty' => $qty];
                    $total += $type->price * $qty;
                }

                if (empty($lines)) {
                    throw new \Exception('Pilih minimal 1 tiket.');
                }

                // Order pending — tiket baru dibuat setelah bayar sukses
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total_price' => $total,
                    'status' => 'pending',
                    'paid_at' => null,
                    'expires_at' => now()->addMinutes(config('services.midtrans.expiry_minutes', 60)),
                ]);

                foreach ($lines as $line) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $line['type']->id,
                        'qty' => $line['qty'],
                        'price' => $line['type']->price,
                    ]);
                }

                return $order;
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('orders.pay', $order);
    }

    public function pay(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order sudah dibayar.');
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Order sudah dibatalkan.');
        }

        if ($this->expireIfNeeded($order)) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Waktu pembayaran habis. Order dibatalkan, silakan pesan ulang.');
        }

        $order->load(['user', 'items.ticketType.event']);

        // Snap token dibuat sekali lalu dipakai ulang. Membuat token baru dengan
        // order_id yang sama akan ditolak Midtrans setelah pembayaran dimulai.
        if (! $order->snap_token) {
            $this->initMidtrans();

            $minutesLeft = $order->expires_at
                ? max(1, (int) ceil(now()->diffInSeconds($order->expires_at) / 60))
                : config('services.midtrans.expiry_minutes', 60);

            $params = [
                'transaction_details' => [
                    'order_id' => $order->code,
                    'gross_amount' => (int) $order->total_price,
                ],
                'item_details' => $order->items->map(fn ($item) => [
                    'id' => 'TT-' . $item->ticket_type_id,
                    'price' => (int) $item->price,
                    'quantity' => (int) $item->qty,
                    'name' => mb_substr(
                        ($item->ticketType->event->title ?? 'Event') . ' - ' . $item->ticketType->name,
                        0,
                        50
                    ),
                ])->all(),
                'customer_details' => [
                    'first_name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'minutes',
                    'duration' => $minutesLeft,
                ],
                'callbacks' => [
                    'finish' => route('orders.show', $order),
                ],
            ];

            try {
                $order->update(['snap_token' => Snap::getSnapToken($params)]);
            } catch (\Exception $e) {
                Log::error('Midtrans getSnapToken gagal', ['order' => $order->code, 'error' => $e->getMessage()]);

                return redirect()->route('orders.show', $order)
                    ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
            }
        }

        return view('orders.pay', [
            'order' => $order,
            'snapToken' => $order->snap_token,
            'clientKey' => config('services.midtrans.client_key'),
            'isProduction' => config('services.midtrans.is_production'),
        ]);
    }

    /** Tombol "Cek status pembayaran" (berguna saat webhook belum masuk, mis. tanpa ngrok). */
    public function sync(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $this->syncFromMidtrans($order);
        $order->refresh();

        return redirect()->route('orders.show', $order)->with(
            $order->isPaid() ? 'success' : 'info',
            $order->isPaid()
                ? 'Pembayaran diterima. Tiket kamu sudah terbit.'
                : 'Pembayaran belum terdeteksi. Coba lagi beberapa saat setelah membayar.'
        );
    }

    public function callback(Request $request)
    {
        $this->initMidtrans();

        try {
            // Notification mengambil status langsung dari Midtrans, bukan dari body request.
            $notif = new Notification();
            $data = (array) $notif->getResponse();
        } catch (\Exception $e) {
            Log::warning('Midtrans callback tidak valid', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $order = Order::where('code', $data['order_id'] ?? null)->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $this->applyStatus($order, $data);

        return response()->json(['message' => 'ok']);
    }

    protected function syncFromMidtrans(Order $order): void
    {
        if ($order->status !== 'pending') {
            return;
        }

        $this->initMidtrans();

        try {
            $data = (array) Transaction::status($order->code);
        } catch (\Throwable $e) {
            // 404 = belum ada transaksi di Midtrans (user belum memilih metode bayar)
            return;
        }

        $this->applyStatus($order, $data);
    }

    protected function applyStatus(Order $order, array $data): void
    {
        $status = $data['transaction_status'] ?? null;
        $fraud = $data['fraud_status'] ?? null;

        if (isset($data['gross_amount'])
            && (int) round((float) $data['gross_amount']) !== (int) $order->total_price) {
            Log::warning('Midtrans gross_amount tidak cocok', ['order' => $order->code]);

            return;
        }

        if (! empty($data['payment_type']) && $order->payment_type !== $data['payment_type']) {
            $order->update(['payment_type' => $data['payment_type']]);
        }

        if (($status === 'capture' && $fraud === 'accept') || $status === 'settlement') {
            $this->markPaid($order);
        } elseif (in_array($status, ['deny', 'cancel', 'expire'], true)) {
            if ($order->status === 'pending') {
                $order->update(['status' => 'cancelled']);
            }
        }
    }

    protected function expireIfNeeded(Order $order): bool
    {
        if (! $order->isExpired()) {
            return false;
        }

        // Cek dulu ke Midtrans: bisa jadi user sudah bayar tapi webhook belum masuk.
        $this->syncFromMidtrans($order);
        $order->refresh();

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);

            return true;
        }

        return false;
    }

    protected function markPaid(Order $order): void
    {
        if ($order->status === 'paid') {
            return;
        }

        DB::transaction(function () use ($order) {
            $order = Order::lockForUpdate()->with('items.ticketType')->find($order->id);

            if ($order->status === 'paid') {
                return;
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            foreach ($order->items as $item) {
                for ($i = 0; $i < $item->qty; $i++) {
                    Ticket::create(['order_item_id' => $item->id]);
                }
                $item->ticketType->increment('sold', $item->qty);
            }
        });
    }


    /** Halaman unduh/cetak tiket (print → Save as PDF). */
    public function downloadTickets(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Tiket hanya bisa diunduh setelah pembayaran lunas.');
        }

        $order->load(['user', 'items.ticketType.event', 'items.tickets']);

        $tickets = $order->items->flatMap->tickets;

        if ($tickets->isEmpty()) {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Tiket belum tersedia. Muat ulang sebentar lagi.');
        }

        return view('tickets.download', [
            'order' => $order,
            'tickets' => $tickets,
        ]);
    }

    protected function initMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Windows sering error SSL CA. CURLOPT_HTTPHEADER (10023) wajib diinisialisasi
        // karena library Midtrans mengakses key itu (Undefined array key 10023).
        if (! Config::$isProduction && app()->environment('local')) {
            Config::$curlOptions = [
                CURLOPT_HTTPHEADER => [],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];
        }
    }
}
