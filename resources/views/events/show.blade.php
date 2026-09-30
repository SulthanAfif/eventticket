<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium mb-6 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Events
        </a>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3 space-y-6">
                <div class="rounded-2xl overflow-hidden border border-gray-100 bg-white shadow-sm">
                    <div class="h-56 sm:h-72 relative">
                        @if($event->poster)
                            <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 flex items-center justify-center">
                                <span class="text-white text-6xl font-bold opacity-30">{{ strtoupper(substr($event->title, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 sm:p-8">
                        <p class="text-sm font-medium text-indigo-600 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $event->starts_at->translatedFormat('l, d F Y · H:i') }}
                            @if($event->ends_at) – {{ $event->ends_at->format('H:i') }} @endif
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2 leading-tight">{{ $event->title }}</h1>
                        @if($event->category)
                            <a href="{{ route('events.index', ['category' => $event->category->slug]) }}"
                               class="inline-flex items-center gap-1.5 mt-2 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset {{ $event->category->badge_classes }} hover:opacity-80 transition">
                                {{ $event->category->icon }} {{ $event->category->name }}
                            </a>
                        @endif
                        <p class="mt-3 text-gray-500 flex items-start gap-1.5">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $event->venue }}@if($event->city), {{ $event->city }}@endif</span>
                        </p>
                        @if($event->description)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <h2 class="font-semibold text-gray-900 mb-3">Tentang Event</h2>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h2 class="font-bold text-gray-900 text-lg mb-1">Pilih Tiket</h2>
                    <p class="text-sm text-gray-500 mb-5">Tentukan jenis dan jumlah tiket</p>

                    @php
                        $ticketTypes = $event->ticketTypes;
                        $hasAvailable = $ticketTypes->contains(fn($t) => ($t->quota - $t->sold) > 0);
                    @endphp

                    @if($ticketTypes->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">Belum ada tiket tersedia untuk event ini.</p>
                        </div>
                    @elseif(!$hasAvailable)
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-red-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <p class="font-semibold text-gray-700">Sold out</p>
                            <p class="text-sm text-gray-400 mt-1">Semua tiket sudah habis terjual.</p>
                        </div>
                    @else
                        <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="space-y-3">
                                @foreach($ticketTypes as $idx => $tt)
                                    @php
                                        $remaining = max(0, $tt->quota - $tt->sold);
                                        $soldOut = $remaining <= 0;
                                    @endphp
                                    <div class="border rounded-xl p-4 transition {{ $soldOut ? 'border-gray-100 bg-gray-50 opacity-60' : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30' }}">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="min-w-0">
                                                <p class="font-semibold text-gray-900">{{ $tt->name }}</p>
                                                @if($tt->description)
                                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $tt->description }}</p>
                                                @endif
                                                <p class="text-indigo-600 font-bold mt-2">Rp {{ number_format($tt->price, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    @if($soldOut) Habis @else Sisa {{ $remaining }} tiket @endif
                                                </p>
                                            </div>
                                            <div class="shrink-0">
                                                @if(!$soldOut)
                                                    <input type="hidden" name="tickets[{{ $idx }}][ticket_type_id]" value="{{ $tt->id }}">
                                                    <select name="tickets[{{ $idx }}][qty]"
                                                            class="ticket-qty w-20 rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                            data-price="{{ $tt->price }}">
                                                        @for($i = 0; $i <= min(10, $remaining); $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                @else
                                                    <span class="text-xs font-medium text-red-500 bg-red-50 px-2 py-1 rounded-lg">Sold out</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 pt-5 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-sm text-gray-500">Total</span>
                                    <span class="font-bold text-xl text-indigo-600" id="order-total">Rp 0</span>
                                </div>

                                @auth
                                    <button type="submit" id="btn-order"
                                            disabled
                                            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition shadow-sm shadow-indigo-200">
                                        Lanjut ke pembayaran
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition shadow-sm shadow-indigo-200">
                                        Masuk untuk beli tiket
                                    </a>
                                    <p class="text-xs text-center text-gray-400 mt-2">
                                        Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Daftar gratis</a>
                                    </p>
                                @endauth
                            </div>
                        </form>

                        <script>
                            (function () {
                                const selects = document.querySelectorAll('.ticket-qty');
                                const totalEl = document.getElementById('order-total');
                                const btn = document.getElementById('btn-order');
                                function update() {
                                    let total = 0, qty = 0;
                                    selects.forEach(s => {
                                        const q = parseInt(s.value, 10) || 0;
                                        qty += q;
                                        total += q * parseFloat(s.dataset.price || 0);
                                    });
                                    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
                                    if (btn) btn.disabled = qty === 0;
                                }
                                selects.forEach(s => s.addEventListener('change', update));
                                update();
                            })();
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
