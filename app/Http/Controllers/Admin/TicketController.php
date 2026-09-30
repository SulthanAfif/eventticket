<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function checkInForm()
    {
        return view('admin.tickets.check-in');
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $ticket = Ticket::with(['orderItem.ticketType.event', 'orderItem.order.user'])
            ->where('code', strtoupper(trim($request->code)))
            ->first();

        if (! $ticket) {
            return back()->with('error', 'Kode tiket tidak ditemukan.');
        }

        if ($ticket->is_used) {
            return back()->with('error', 'Tiket sudah digunakan pada ' . $ticket->used_at->format('d M Y H:i'));
        }

        if ($ticket->orderItem->order->status !== 'paid') {
            return back()->with('error', 'Order belum paid.');
        }

        $ticket->markAsUsed();

        return back()->with('success', "Check-in berhasil: {$ticket->code} — {$ticket->orderItem->ticketType->event->title}");
    }
}
