@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div x-data="{ openCustomizeModal: false }" class="space-y-6">

    <!-- Header & Aksi Cepat -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Utama</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} — Ringkasan performa penjualan tiket
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Tombol Kustomisasi Tanpa Kode -->
            <button @click="openCustomizeModal = true"
                    type="button"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Atur Tampilan Dashboard
            </button>

            <a href="{{ route('admin.events.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm flex items-center justify-between">
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Banner Pengumuman Dinamis -->
    @if (!empty($settings['show_announcement']) && !empty($settings['announcement']))
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-5 text-white shadow-sm">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold tracking-wide uppercase text-indigo-100">Pemberitahuan Admin</h3>
                    <p class="mt-1 text-sm text-white/90 leading-relaxed">{{ $settings['announcement'] }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Grid Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">

        @if(!empty($settings['show_revenue']))
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow transition sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Omzet</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
            @if(!empty($settings['revenue_target']) && $settings['revenue_target'] > 0)
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Target: Rp {{ number_format($settings['revenue_target'], 0, ',', '.') }}</span>
                        <span class="font-medium text-emerald-600">{{ $revenueProgress }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $revenueProgress }}%"></div>
                    </div>
                </div>
            @endif
        </div>
        @endif

        @if(!empty($settings['show_orders']))
        <!-- Order Berhasil -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Order Paid</span>
                <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['orders']) }}</p>
            <p class="text-xs text-gray-400 mt-2">Transaksi berhasil dibayar</p>
        </div>
        @endif

        @if(!empty($settings['show_tickets_sold']))
        <!-- Tiket Terjual -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tiket Terjual</span>
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['tickets_sold']) }}</p>
            <p class="text-xs text-gray-400 mt-2">Total tiket terbit</p>
        </div>
        @endif

        @if(!empty($settings['show_tickets_used']))
        <!-- Check-in Tiket -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Check-In</span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['tickets_used']) }}</p>
            <p class="text-xs text-gray-400 mt-2">Tiket sudah digunakan</p>
        </div>
        @endif

        @if(!empty($settings['show_events']))
        <!-- Event Aktif -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Event</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['events']) }}</p>
            <p class="text-xs text-amber-600 font-medium mt-2">{{ $stats['published'] }} Terpublikasi</p>
        </div>
        @endif

    </div>

    @if(!empty($settings['show_recent_orders']))
    <!-- Tabel Order Terbaru -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4.5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Order Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">Menampilkan {{ $latestOrders->count() }} transaksi terakhir</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                Lihat Semua Order &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/75 text-xs uppercase text-gray-500 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Kode Order</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Total Bayar</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($latestOrders as $order)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4 font-mono font-semibold text-indigo-600">
                                {{ $order->code }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-400">{{ $order->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                    {{ $order->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-600 hover:text-indigo-600 bg-gray-50 hover:bg-indigo-50 rounded-lg transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                                Belum ada order yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- MODAL: KUSTOMISASI DASHBOARD TANPA KODE -->
    <div x-show="openCustomizeModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop -->
        <div x-show="openCustomizeModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="openCustomizeModal = false"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="openCustomizeModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                 class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">Pengaturan Tampilan Dashboard</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Sesuaikan widget dan konten tanpa perlu mengubah kode.</p>
                    </div>
                    <button @click="openCustomizeModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.dashboard.settings.update') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Pilihan Widget yang Ditampilkan -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">
                            Widget Statistik
                        </label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="show_revenue" value="1" {{ !empty($settings['show_revenue']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Total Omzet</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="show_orders" value="1" {{ !empty($settings['show_orders']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Order Paid</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="show_tickets_sold" value="1" {{ !empty($settings['show_tickets_sold']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Tiket Terjual</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="show_tickets_used" value="1" {{ !empty($settings['show_tickets_used']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Check-in</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="show_events" value="1" {{ !empty($settings['show_events']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Jumlah Event</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="show_recent_orders" value="1" {{ !empty($settings['show_recent_orders']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Tabel Order</span>
                            </label>
                        </div>
                    </div>

                    <!-- Target Omzet -->
                    <div>
                        <label for="revenue_target" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-1">
                            Target Omzet Bulanan (Rp)
                        </label>
                        <input type="number" id="revenue_target" name="revenue_target"
                               value="{{ $settings['revenue_target'] ?? 0 }}"
                               placeholder="Contoh: 50000000"
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="text-xs text-gray-400 mt-1 block">Progress bar capaian omzet akan disesuaikan otomatis.</span>
                    </div>

                    <!-- Jumlah Baris Order -->
                    <div>
                        <label for="orders_limit" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-1">
                            Jumlah Order Ditampilkan
                        </label>
                        <select id="orders_limit" name="orders_limit" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="5" {{ ($settings['orders_limit'] ?? 8) == 5 ? 'selected' : '' }}>5 Order</option>
                            <option value="8" {{ ($settings['orders_limit'] ?? 8) == 8 ? 'selected' : '' }}>8 Order</option>
                            <option value="15" {{ ($settings['orders_limit'] ?? 8) == 15 ? 'selected' : '' }}>15 Order</option>
                        </select>
                    </div>

                    <!-- Banner Pemberitahuan -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="announcement" class="text-xs font-bold uppercase text-gray-500 tracking-wider">
                                Pesan Pengumuman / Catatan
                            </label>
                            <label class="inline-flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                                <input type="checkbox" name="show_announcement" value="1" {{ !empty($settings['show_announcement']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                                Aktifkan Banner
                            </label>
                        </div>
                        <textarea id="announcement" name="announcement" rows="2"
                                  placeholder="Tuliskan pesan atau target tim di sini..."
                                  class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">{{ $settings['announcement'] ?? '' }}</textarea>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-3 flex items-center justify-end gap-3 border-t border-gray-100">
                        <button type="button" @click="openCustomizeModal = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
