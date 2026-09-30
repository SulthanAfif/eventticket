<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline">← Orders</a>
            <h2 class="font-semibold text-xl text-gray-800 font-mono">{{ $order->code }}</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 py-10 space-y-6">
        @foreach ([
            'success' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
            'error' => 'bg-red-50 border-red-200 text-red-700',
            'info' => 'bg-sky-50 border-sky-200 text-sky-700',
        ] as $key => $classes)
            @if (session($key))
                <div class="border px-4 py-3 rounded-xl text-sm {{ $classes }}">{{ session($key) }}</div>
            @endif
        @endforeach

        {{-- Ringkasan order --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <x-order-status :status="$order->status" />
                    <p class="text-sm text-gray-400 mt-3">Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                    @if ($order->paid_at)
                        <p class="text-sm text-gray-400">
                            Dibayar {{ $order->paid_at->translatedFormat('d M Y, H:i') }}
                            @if ($order->paymentLabel())
                                via {{ $order->paymentLabel() }}
                            @endif
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="font-bold text-indigo-600 text-xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            @if ($order->status === 'pending')
                <div class="mt-5 rounded-xl bg-amber-50 border border-amber-100 px-4 py-3 text-sm text-amber-800">
                    Menunggu pembayaran
                    @if ($order->expires_at)
                        , batas bayar {{ $order->expires_at->translatedFormat('d M, H:i') }}
                    @endif
                    .
                    Tiket muncul di halaman ini setelah pembayaran diterima.
                </div>
                <div class="mt-4 grid sm:grid-cols-2 gap-3">
                    <a href="{{ route('orders.pay', $order) }}"
                       class="inline-flex justify-center bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                        Lanjut bayar
                    </a>
                    <form method="POST" action="{{ route('orders.sync', $order) }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 rounded-xl font-semibold text-gray-700 border border-gray-200 hover:bg-gray-50 transition">
                            Cek status pembayaran
                        </button>
                    </form>
                </div>
            @elseif ($order->status === 'cancelled')
                <div class="mt-5 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
                    Order ini dibatalkan atau waktu pembayarannya habis. Kamu bisa memesan tiket lagi dari halaman event.
                </div>
            @elseif ($order->status === 'paid')
                @php $ticketCount = $order->items->sum(fn ($i) => $i->tickets->count()); @endphp
                @if ($ticketCount > 0)
                    <div class="mt-5">
                        <a href="{{ route('orders.tickets.download', $order) }}"
                           class="inline-flex w-full sm:w-auto justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-5 rounded-xl transition shadow-sm shadow-indigo-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh tiket ({{ $ticketCount }})
                        </a>
                    </div>
                @endif
            @endif
        </div>

        {{-- Tiket --}}
        @foreach ($order->items as $item)
            <section class="space-y-3">
                <div>
                    <p class="font-semibold text-gray-900">{{ $item->ticketType->event->title ?? 'Event' }}</p>
                    <p class="text-sm text-gray-500">{{ $item->ticketType->name }} × {{ $item->qty }}</p>
                </div>

                @forelse ($item->tickets as $ticket)
                    @php
                        $event = $item->ticketType->event;
                    @endphp
                    <article class="flex rounded-2xl overflow-hidden shadow-lg shadow-indigo-100 bg-white border border-gray-100 {{ $ticket->is_used ? 'opacity-60' : '' }}">
                        <div class="flex-1 p-5 text-white min-w-0" style="{{ $event->ticket_style }}">
                            <p class="text-xs opacity-90">{{ $item->ticketType->name }}</p>
                            <h3 class="font-bold text-lg leading-snug mt-1 line-clamp-2">{{ $event->title ?? '' }}</h3>
                            @if ($event)
                                <p class="text-sm text-indigo-100 mt-3">{{ $event->starts_at->translatedFormat('l, d F Y · H:i') }}</p>
                                <p class="text-sm text-indigo-100 truncate">
                                    {{ $event->venue }}
                                    @if ($event->city)
                                        , {{ $event->city }}
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="relative w-36 sm:w-44 shrink-0 p-4 flex flex-col items-center justify-center text-center border-l-2 border-dashed border-gray-200">
                            <span class="absolute -top-2 -left-2 w-4 h-4 rounded-full bg-slate-50"></span>
                            <span class="absolute -bottom-2 -left-2 w-4 h-4 rounded-full bg-slate-50"></span>
                            <p class="text-[11px] text-gray-400">Kode tiket</p>
                            <p class="font-mono font-bold text-gray-900 tracking-wider text-sm sm:text-base break-all mt-1">{{ $ticket->code }}</p>
                            <span class="mt-3 text-xs font-medium px-2.5 py-1 rounded-full {{ $ticket->is_used ? 'bg-gray-100 text-gray-600' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $ticket->is_used ? 'Sudah dipakai' : 'Valid' }}
                            </span>
                        </div>
                    </article>
                @empty
                    @if ($order->status === 'paid')
                        <p class="text-sm text-gray-400">Tiket sedang diproses. Muat ulang halaman ini sebentar lagi.</p>
                    @endif
                @endforelse
            </section>
        @endforeach
    </div>
</x-app-layout>
