<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Order saya</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-10 space-y-4">
        @forelse($orders as $order)
            @php
                $first = $order->items->first();
                $more = $order->items->count() - 1;
                $qty = $order->items->sum('qty');
            @endphp
            <a href="{{ route('orders.show', $order) }}"
               class="block bg-white rounded-2xl border border-gray-100 p-5 hover:border-indigo-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $first?->ticketType?->event?->title ?? 'Event' }}@if($more > 0) <span class="text-gray-400 font-normal">+{{ $more }} lainnya</span>@endif</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $qty }} tiket · <span class="font-mono">{{ $order->code }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    <x-order-status :status="$order->status" class="shrink-0" />
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <p class="font-bold text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    @if($order->status === 'pending')
                        <span class="text-sm font-medium text-indigo-600">Lanjut bayar →</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl">🎫</div>
                <p class="text-gray-600 font-medium">Belum ada order</p>
                <p class="text-sm text-gray-400 mt-1">Pilih event dan beli tiket pertamamu.</p>
                <a href="{{ route('events.index') }}" class="inline-block mt-4 text-indigo-600 text-sm font-medium hover:underline">Lihat event →</a>
            </div>
        @endforelse
    </div>
</x-app-layout>
