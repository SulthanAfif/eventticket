<x-app-layout>
    {{-- 1. HERO SLIDER BANNER PROMO (Gaya Loket.com / TipTip.id) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
        @if(isset($banners) && $banners->isNotEmpty())
            <div x-data="{
                active: 0,
                total: {{ $banners->count() }},
                timer: null,
                startAutoPlay() {
                    if (this.total > 1) {
                        this.timer = setInterval(() => { this.next(); }, 4500);
                    }
                },
                stopAutoPlay() {
                    clearInterval(this.timer);
                },
                next() {
                    this.active = (this.active + 1) % this.total;
                },
                prev() {
                    this.active = (this.active - 1 + this.total) % this.total;
                }
            }"
            x-init="startAutoPlay()"
            @mouseenter="stopAutoPlay()"
            @mouseleave="startAutoPlay()"
            class="relative w-full aspect-[21/9] sm:aspect-[24/9] md:aspect-[3/1] min-h-[200px] sm:min-h-[280px] md:min-h-[340px] rounded-2xl md:rounded-3xl overflow-hidden shadow-lg shadow-gray-200/50 bg-gray-900 group">

                <!-- Slide Item -->
                @foreach($banners as $index => $banner)
                    <div x-show="active === {{ $index }}"
                         x-transition:enter="transition ease-out duration-700 transform"
                         x-transition:enter-start="opacity-0 translate-x-12 scale-98"
                         x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                         x-transition:leave="transition ease-in duration-400 transform absolute inset-0"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-12"
                         class="w-full h-full">

                        @if($banner->link)
                            <a href="{{ $banner->link }}" class="block w-full h-full relative cursor-pointer">
                        @else
                            <div class="w-full h-full relative">
                        @endif

                            <img src="{{ $banner->image_url }}"
                                 alt="{{ $banner->title ?? 'Promo Banner' }}"
                                 class="w-full h-full object-cover object-center">

                            @if($banner->title)
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-gray-950/80 via-gray-950/30 to-transparent p-5 sm:p-8 flex items-end">
                                    <h3 class="text-white font-bold text-lg sm:text-2xl drop-shadow">
                                        {{ $banner->title }}
                                    </h3>
                                </div>
                            @endif

                        @if($banner->link)
                            </a>
                        @else
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Tombol Navigasi Kiri / Kanan -->
                @if($banners->count() > 1)
                    <button @click="prev()"
                            type="button"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-gray-800 shadow-md backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()"
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-gray-800 shadow-md backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Titik Indikator (Dots) -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                        @foreach($banners as $index => $banner)
                            <button @click="active = {{ $index }}"
                                    type="button"
                                    :class="active === {{ $index }} ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <!-- Banner Default Jika Belum Ada Banner dari Admin -->
            <div class="relative w-full aspect-[21/9] sm:aspect-[24/9] md:aspect-[3/1] min-h-[220px] rounded-2xl md:rounded-3xl overflow-hidden shadow-md bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 text-white flex items-center p-8 sm:p-12">
                <div class="max-w-xl space-y-2 z-10">
                    <span class="px-3 py-1 bg-white/10 border border-white/20 rounded-full text-xs font-semibold text-indigo-200">
                        Event Favorit Indonesia
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                        Temukan Event Seru & Beli Tiketnya Sekarang
                    </h1>
                    <p class="text-xs sm:text-sm text-indigo-100">
                        Tambahkan gambar banner beresolusi tinggi di menu Admin &rarr; Slide Banner.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- 2. SEARCH & KATEGORI CEPAT (Ala Kiostix & Eratix) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">

            <!-- Input Pencarian -->
            <div class="relative w-full md:max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       id="searchEvent"
                       placeholder="Cari event, konser, artis, atau venue..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">
            </div>

            <!-- Kategori Chips / Pills -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs font-medium no-scrollbar">
                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold whitespace-nowrap shadow-sm">
                    Semua
                </button>
                <button class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 whitespace-nowrap transition">
                    🎵 Musik
                </button>
                <button class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 whitespace-nowrap transition">
                    🎪 Festival
                </button>
                <button class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 whitespace-nowrap transition">
                    💼 Workshop
                </button>
                <button class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 whitespace-nowrap transition">
                    ⚽ Olahraga
                </button>
            </div>
        </div>
    </div>

    <!-- 3. GRID DAFTAR EVENT UTAMA -->
    <div id="events" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Event Pilihan & Terpopuler</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Tiket resmi terjamin dan proses cepat</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full">
                {{ $events->count() }} Event Aktif
            </span>
        </div>

        {{-- Filter Kategori --}}
        @if(isset($categories) && $categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto pb-1">
                <a href="{{ route('events.index') }}#events"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition
                   {{ empty($categorySlug) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('events.index', ['category' => $cat->slug]) }}#events"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition whitespace-nowrap
                       {{ ($categorySlug ?? '') === $cat->slug ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                        <span>{{ $cat->icon }}</span>
                        {{ $cat->name }}
                        @if($cat->published_events_count > 0)
                            <span class="opacity-70">({{ $cat->published_events_count }})</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

        @if($events->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 p-16 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <h3 class="text-gray-800 font-bold text-base">Belum Ada Event Aktif</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Silakan buat event baru melalui dashboard admin agar tampil di halaman ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-indigo-100/50 hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">

                        <!-- Gambar Poster Event -->
                        <div>
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                                @if($event->poster)
                                    <img src="{{ $event->poster_url ?? $event->poster }}"
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-4xl font-extrabold opacity-40">
                                        {{ strtoupper(substr($event->title, 0, 1)) }}
                                    </div>
                                @endif

                                <!-- Badge Tanggal Mengambang -->
                                <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md text-gray-900 px-2.5 py-1 rounded-xl shadow-sm text-center">
                                    <span class="block text-[10px] uppercase font-bold text-indigo-600 tracking-wider">
                                        {{ $event->starts_at->translatedFormat('M') }}
                                    </span>
                                    <span class="block text-sm font-extrabold leading-none">
                                        {{ $event->starts_at->translatedFormat('d') }}
                                    </span>
                                </div>

                                <!-- Status Tiket -->
                                @php
                                    $available = $event->ticketTypes->where('is_active', true)->sum(fn($t) => max(0, $t->quota - $t->sold));
                                @endphp
                                @if($available <= 0)
                                    <div class="absolute top-3 right-3 bg-red-600/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-sm">
                                        Sold Out
                                    </div>
                                @elseif($available <= 20)
                                    <div class="absolute top-3 right-3 bg-amber-500/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-sm">
                                        Sisa Dikit
                                    </div>
                                @endif
                            </div>

                            <!-- Informasi Event -->
                            <div class="p-4 space-y-2">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <p class="text-xs text-indigo-600 font-semibold flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $event->city ?? 'Indonesia' }}
                                    </p>
                                    @if($event->category)
                                        <span class="inline-flex items-center gap-0.5 text-[10px] font-medium px-1.5 py-0.5 rounded-full ring-1 ring-inset {{ $event->category->badge_classes }}">
                                            {{ $event->category->icon }} {{ $event->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-bold text-sm sm:text-base text-gray-900 group-hover:text-indigo-600 transition line-clamp-2 leading-snug">
                                    {{ $event->title }}
                                </h3>

                                <p class="text-xs text-gray-400 truncate flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $event->venue }}
                                </p>
                            </div>
                        </div>

                        <!-- Harga & Aksi Beli -->
                        <div class="p-4 pt-0">
                            @php $minPrice = $event->ticketTypes->where('is_active', true)->min('price'); @endphp
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] text-gray-400">Harga mulai</span>
                                    <span class="font-extrabold text-sm sm:text-base text-indigo-600">
                                        {{ $minPrice !== null ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis' }}
                                    </span>
                                </div>
                                <span class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white text-xs font-semibold transition">
                                    Beli Tiket
                                </span>
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 4. TRUST BADGES / KEUNGGULAN (Midtrans, Instan E-Tiket) -->
    <div class="border-t border-gray-200/60 bg-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Pembayaran Aman & Terverifikasi</h4>
                        <p class="text-xs text-gray-500 mt-1">Dukungan QRIS, Virtual Account, dan e-wallet resmi terhubung otomatis via Midtrans.</p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">E-Tiket QR Instan</h4>
                        <p class="text-xs text-gray-500 mt-1">E-tiket otomatis terbit dan langsung dapat diunduh dalam format PDF siap scan.</p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Check-in Cepat di Venue</h4>
                        <p class="text-xs text-gray-500 mt-1">Sistem validasi kode tiket anti-duplikat untuk kepraktisan panitia dan penonton.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
