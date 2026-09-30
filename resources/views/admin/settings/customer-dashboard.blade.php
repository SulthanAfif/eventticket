@extends('layouts.admin')

@section('title', 'Kustomisasi Dashboard Customer')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between pb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kustomisasi Halaman Pelanggan</h1>
            <p class="text-sm text-gray-500 mt-1">Ubah teks banner, promosi, dan tombol di halaman beranda customer secara langsung.</p>
        </div>
        <a href="{{ route('events.index') }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
            Lihat Halaman Customer &rarr;
        </a>
    </div>

    @if (session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('admin.customer-dashboard.update') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf

        <!-- Bagian Hero Banner -->
        <div>
            <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">1</span>
                Hero Banner Utama
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Badge Atas</label>
                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge']) }}" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Judul Utama (Headline)</label>
                    <textarea name="hero_title" rows="2" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('hero_title', $settings['hero_title']) }}</textarea>
                    <span class="text-xs text-gray-400">Gunakan baris baru (enter) jika ingin memecah teks menjadi 2 baris.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Deskripsi / Sub-Headline</label>
                    <textarea name="hero_description" rows="3" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('hero_description', $settings['hero_description']) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Teks Tombol Aksi (CTA)</label>
                    <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $settings['hero_cta_text']) }}" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>
        </div>

        <!-- Bagian Pengumuman & Promo Customer -->
        <div>
            <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">2</span>
                Pengumuman / Promo Pelanggan
            </h2>

            <div class="space-y-4">
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="promo_banner_active" value="1" {{ $settings['promo_banner_active'] == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-700">Tampilkan Banner Promo di Atas Halaman</span>
                </label>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Teks Banner Promo</label>
                    <input type="text" name="promo_banner_text" value="{{ old('promo_banner_text', $settings['promo_banner_text']) }}" placeholder="Contoh: Diskon 20% khusus pengguna baru dengan kode TIKETHEMAT" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Bantuan WhatsApp Customer -->
        <div>
            <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">3</span>
                Bantuan & CS Pelanggan
            </h2>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Nomor WhatsApp Bantuan (Opsional)</label>
                <input type="text" name="support_whatsapp" value="{{ old('support_whatsapp', $settings['support_whatsapp']) }}" placeholder="Contoh: 628123456789" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                <span class="text-xs text-gray-400 mt-1 block">Jika diisi, tombol WhatsApp melayang akan muncul di pojok kanan bawah halaman customer.</span>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
