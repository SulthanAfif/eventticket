@extends('layouts.admin')

@section('title', 'Kelola Slide Banner')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Slide Banner Customer</h1>
            <p class="text-sm text-gray-500 mt-1">Tambahkan gambar slide promo atau poster event untuk beranda customer.</p>
        </div>
        <a href="{{ route('events.index') }}" target="_blank" class="text-sm text-indigo-600 font-semibold hover:underline">
            Lihat Beranda &rarr;
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Tambah Banner -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Tambah Slide Baru</h2>

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Judul / Label Banner (Opsional)</label>
                <input type="text" name="title" placeholder="Contoh: Konser Musik Spesial Akhir Tahun" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 tracking-wider mb-1">Link Tujuan saat Banner Diklik (Opsional)</label>
                <input type="url" name="link" placeholder="https://domain.com/events/nama-event atau #events" class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Tab Pilihan Gambar -->
            <div x-data="{
                source: 'file',
                preview: null,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) this.preview = URL.createObjectURL(file);
                }
            }" class="border border-gray-200 rounded-xl p-4 bg-gray-50/50 space-y-3">

                <span class="block text-xs font-bold uppercase text-gray-700 tracking-wider">Sumber Gambar Banner</span>

                <div class="flex gap-2 p-1 bg-gray-200/70 rounded-lg text-xs font-medium">
                    <button type="button" @click="source = 'file'" :class="source === 'file' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600'" class="flex-1 py-1.5 rounded-md text-center">
                        📁 File Explorer (Upload)
                    </button>
                    <button type="button" @click="source = 'url'" :class="source === 'url' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600'" class="flex-1 py-1.5 rounded-md text-center">
                        🔗 Link Gambar (URL)
                    </button>
                </div>

                <input type="hidden" name="image_source" :value="source">

                <!-- Upload File -->
                <div x-show="source === 'file'" class="space-y-1">
                    <label class="block text-xs text-gray-500">Pilih gambar dari komputer (rekomendasi rasio 16:9 / horizontal):</label>
                    <input type="file" name="banner_file" accept="image/*" @change="handleFile($event)" class="block w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 rounded-lg bg-white">
                </div>

                <!-- Input Link -->
                <div x-show="source === 'url'" class="space-y-1">
                    <label class="block text-xs text-gray-500">Masukkan link gambar langsung (CDN/Unsplash):</label>
                    <input type="url" name="banner_url" x-model="preview" placeholder="https://images.unsplash.com/..." class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Preview -->
                <template x-if="preview">
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <span class="block text-xs text-gray-500 mb-1 font-medium">Pratinjau:</span>
                        <div class="h-36 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                            <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    </div>
                </template>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                    + Tambahkan Slide
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Slide Aktif -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Daftar Slide Banner Aktif</h2>

        @if($banners->isEmpty())
            <div class="text-center py-10 text-gray-400 text-sm">
                Belum ada banner yang ditambahkan. Silakan tambahkan banner pertama Anda di atas.
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($banners as $banner)
                    <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm flex flex-col justify-between">
                        <div class="relative h-44 bg-gray-100">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-full object-cover">
                            @if($banner->title)
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white text-xs font-semibold truncate">
                                    {{ $banner->title }}
                                </div>
                            @endif
                        </div>
                        <div class="p-3.5 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100">
                            <span class="truncate max-w-[180px]">{{ $banner->link ? 'Link: ' . $banner->link : 'Tanpa link' }}</span>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Hapus slide banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
