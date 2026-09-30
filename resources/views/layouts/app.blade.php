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

        {{-- Promo banner pelanggan (dari admin settings) --}}
        @php
            $promoActive = ($globalSiteSettings['promo_banner_active'] ?? '0') === '1';
            $promoText = trim($globalSiteSettings['promo_banner_text'] ?? '');
        @endphp
        @if($promoActive && $promoText !== '')
            <div class="bg-indigo-600 text-white text-center text-xs sm:text-sm font-medium px-4 py-2.5">
                {{ $promoText }}
            </div>
        @endif

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

    {{-- Floating WhatsApp CS --}}
    @php
        $waRaw = preg_replace('/\D+/', '', $globalSiteSettings['support_whatsapp'] ?? '');
        // Normalisasi: 08xxx → 62xxx
        if ($waRaw !== '' && str_starts_with($waRaw, '0')) {
            $waRaw = '62' . substr($waRaw, 1);
        }
    @endphp
    @if($waRaw !== '')
        <a href="https://wa.me/{{ $waRaw }}?text={{ urlencode('Halo, saya butuh bantuan terkait EventTicket.') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="fixed bottom-5 right-5 z-50 flex items-center gap-2 bg-[#25D366] hover:bg-[#1ebe57] text-white font-semibold text-sm pl-3.5 pr-4 py-3 rounded-full shadow-lg shadow-green-500/30 transition hover:scale-105"
           aria-label="Chat WhatsApp">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.85 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <span class="hidden sm:inline">Bantuan</span>
        </a>
    @endif
</body>
</html>
