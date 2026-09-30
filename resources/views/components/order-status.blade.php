@props(['status'])

@php
    $map = [
        'paid' => ['Lunas', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'bg-emerald-500'],
        'pending' => ['Menunggu pembayaran', 'bg-amber-50 text-amber-700 ring-amber-600/20', 'bg-amber-500'],
        'cancelled' => ['Dibatalkan', 'bg-red-50 text-red-700 ring-red-600/20', 'bg-red-500'],
    ];
    [$label, $classes, $dot] = $map[$status] ?? [ucfirst($status), 'bg-gray-50 text-gray-700 ring-gray-500/20', 'bg-gray-400'];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset', $classes]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ $label }}
</span>
