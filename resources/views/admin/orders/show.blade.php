@extends('layouts.admin')

@section('title', $order->code)

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:underline">← Orders</a>
    <h1 class="text-2xl font-bold mt-2 mb-6">{{ $order->code }}</h1>

    <div class="bg-white rounded-xl border p-6 mb-6 max-w-xl text-sm space-y-2">
        <p><span class="text-gray-500">Customer:</span> {{ $order->user->name }} ({{ $order->user->email }})</p>
        <p><span class="text-gray-500">Status:</span> <span class="capitalize">{{ $order->status }}</span></p>
        <p><span class="text-gray-500">Total:</span> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p><span class="text-gray-500">Tanggal:</span> {{ $order->created_at->format('d M Y H:i') }}</p>
    </div>

    @foreach($order->items as $item)
        <div class="bg-white rounded-xl border p-6 mb-4 max-w-xl">
            <p class="font-semibold">{{ $item->ticketType->event->title }} — {{ $item->ticketType->name }}</p>
            <p class="text-sm text-gray-500 mb-3">{{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            <div class="space-y-1 font-mono text-xs">
                @foreach($item->tickets as $ticket)
                    <div class="flex justify-between bg-gray-50 rounded px-3 py-2">
                        <span>{{ $ticket->code }}</span>
                        <span class="{{ $ticket->is_used ? 'text-gray-400' : 'text-green-600' }}">
                            {{ $ticket->is_used ? 'Used' : 'Valid' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
