<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.show', $order) }}" class="text-sm text-indigo-600 hover:underline">← Order</a>
            <h2 class="font-semibold text-xl text-gray-800">Pembayaran</h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-10 grid lg:grid-cols-5 gap-6">
        {{-- Ringkasan --}}
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Kode order</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $order->code }}</p>
                    </div>
                    <x-order-status :status="$order->status" />
                </div>

                <ul class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <li class="px-6 py-4 flex justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $item->ticketType->event->title ?? 'Event' }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $item->ticketType->name }} · {{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <p class="font-medium text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                            </p>
                        </li>
                    @endforeach
                </ul>

                <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total bayar</span>
                    <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            @unless($isProduction)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900">
                    <p class="font-semibold mb-2">Mode Sandbox: tidak ada uang sungguhan yang dipakai</p>
                    <dl class="grid grid-cols-[7rem_1fr] gap-x-3 gap-y-1.5">
                        <dt class="text-amber-700">Kartu sukses</dt>
                        <dd class="font-mono">4811 1111 1111 1114</dd>
                        <dt class="text-amber-700">Kartu ditolak</dt>
                        <dd class="font-mono">4911 1111 1111 1113</dd>
                        <dt class="text-amber-700">Expiry / CVV</dt>
                        <dd class="font-mono">12/30 · 123</dd>
                        <dt class="text-amber-700">OTP 3DS</dt>
                        <dd class="font-mono">112233</dd>
                    </dl>
                    <p class="mt-3 text-amber-800">
                        Untuk VA, GoPay, atau QRIS, selesaikan pembayaran lewat
                        <a href="https://simulator.sandbox.midtrans.com" target="_blank" rel="noopener" class="underline font-medium">Midtrans Simulator</a>.
                    </p>
                </div>
            @endunless
        </div>

        {{-- Aksi bayar --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:sticky lg:top-24">
                @if($order->expires_at)
                    <div class="rounded-xl bg-indigo-50 px-4 py-3 text-center mb-5">
                        <p class="text-xs text-indigo-700">Selesaikan pembayaran dalam</p>
                        <p id="countdown" class="font-mono text-2xl font-bold text-indigo-700" data-expires="{{ $order->expires_at->toIso8601String() }}">--:--</p>
                    </div>
                @endif

                <button id="pay-button" type="button"
                        class="w-full bg-indigo-600 text-white py-3.5 rounded-xl font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md shadow-indigo-200 disabled:opacity-60 disabled:cursor-not-allowed">
                    Pilih metode pembayaran
                </button>
                <p id="pay-error" class="hidden mt-3 text-sm text-red-600 text-center"></p>

                <ul class="mt-5 space-y-2 text-xs text-gray-500">
                    <li class="flex gap-2"><span class="text-emerald-600">✓</span> Kartu, transfer bank (VA), GoPay, QRIS, dan lainnya</li>
                    <li class="flex gap-2"><span class="text-emerald-600">✓</span> Tiket terbit otomatis setelah pembayaran diterima</li>
                    <li class="flex gap-2"><span class="text-emerald-600">✓</span> Diproses aman oleh Midtrans</li>
                </ul>

                <a href="{{ route('orders.show', $order) }}"
                   class="block mt-5 text-center text-sm text-gray-500 hover:text-gray-700 hover:underline">
                    Bayar nanti
                </a>
            </div>
        </div>
    </div>

    <script src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ $clientKey }}"></script>
    <script>
        (function () {
            const orderUrl = @json(route('orders.show', $order));
            const btn = document.getElementById('pay-button');
            const err = document.getElementById('pay-error');

            function showError(msg) {
                err.textContent = msg;
                err.classList.remove('hidden');
                btn.disabled = false;
            }

            btn.addEventListener('click', function () {
                err.classList.add('hidden');
                btn.disabled = true;

                window.snap.pay(@json($snapToken), {
                    onSuccess: () => { window.location.href = orderUrl; },
                    onPending: () => { window.location.href = orderUrl; },
                    onError: () => showError('Pembayaran gagal. Silakan coba lagi.'),
                    onClose: () => { btn.disabled = false; },
                });
            });

            const cd = document.getElementById('countdown');
            if (cd) {
                const end = new Date(cd.dataset.expires).getTime();
                const tick = () => {
                    const left = Math.max(0, Math.floor((end - Date.now()) / 1000));
                    const h = Math.floor(left / 3600);
                    const m = String(Math.floor((left % 3600) / 60)).padStart(2, '0');
                    const s = String(left % 60).padStart(2, '0');
                    cd.textContent = (h > 0 ? h + ':' : '') + m + ':' + s;
                    if (left === 0) {
                        btn.disabled = true;
                        btn.textContent = 'Waktu pembayaran habis';
                        clearInterval(timer);
                    }
                };
                const timer = setInterval(tick, 1000);
                tick();
            }
        })();
    </script>
</x-app-layout>
