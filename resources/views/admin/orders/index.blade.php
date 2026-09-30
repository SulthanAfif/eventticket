@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Orders</h1>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $order->code }}</td>
                        <td class="px-4 py-3">{{ $order->user->name }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 capitalize">{{ $order->status }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
