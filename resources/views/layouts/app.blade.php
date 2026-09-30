<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="EventTicket — platform beli tiket event online. Konser, workshop, meetup, dan lainnya. Pembayaran aman via Midtrans.">
    <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name', 'EventTicket') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100/80">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-100 bg-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-10">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <x-application-logo class="w-8 h-8" />
                            <span class="font-bold text-lg text-gray-900 tracking-tight">EventTicket</span>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
                            Platform tiket event online. Temukan konser, workshop, dan meetup favoritmu — bayar aman lewat Midtrans.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Navigasi</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('events.index') }}" class="text-gray-600 hover:text-indigo-600 transition">Events</a></li>
                            @auth
                                <li><a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-indigo-600 transition">Pesanan saya</a></li>
                                <li><a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-indigo-600 transition">Profil</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition">Masuk</a></li>
                                <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-indigo-600 transition">Daftar</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Pembayaran</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Didukung Midtrans — kartu kredit, VA bank, GoPay, QRIS, dan e-wallet lainnya.
                        </p>
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">Visa</span>
                            <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">Mastercard</span>
                            <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">GoPay</span>
                            <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">QRIS</span>
                            <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">BCA VA</span>
                        </div>
                    </div>
                </div>
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-gray-400">
                    <p>© {{ date('Y') }} EventTicket. Semua hak dilindungi.</p>
                    <p class="text-xs">Pembayaran diproses aman oleh Midtrans</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
