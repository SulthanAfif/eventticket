<div>
    <label class="block text-sm font-medium mb-1">Kategori</label>
    <select name="category_id" class="w-full rounded-lg border-gray-300">
        <option value="">— Pilih kategori —</option>
        @foreach(($categories ?? []) as $cat)
            <option value="{{ $cat->id }}"
                @selected(old('category_id', $event->category_id ?? '') == $cat->id)>
                {{ $cat->icon }} {{ $cat->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium mb-1">Judul</label>
    <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" required
           class="w-full rounded-lg border-gray-300">
    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300">{{ old('description', $event->description ?? '') }}</textarea>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Venue</label>
        <input type="text" name="venue" value="{{ old('venue', $event->venue ?? '') }}" required
               class="w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Kota</label>
        <input type="text" name="city" value="{{ old('city', $event->city ?? '') }}"
               class="w-full rounded-lg border-gray-300">
    </div>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Mulai</label>
        <input type="datetime-local" name="starts_at"
               value="{{ old('starts_at', isset($event) ? $event->starts_at->format('Y-m-d\TH:i') : '') }}"
               required class="w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Selesai</label>
        <input type="datetime-local" name="ends_at"
               value="{{ old('ends_at', isset($event) && $event->ends_at ? $event->ends_at->format('Y-m-d\TH:i') : '') }}"
               class="w-full rounded-lg border-gray-300">
    </div>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Status</label>
    <select name="status" class="w-full rounded-lg border-gray-300">
        @foreach(['draft','published','finished','cancelled'] as $s)
            <option value="{{ $s }}" @selected(old('status', $event->status ?? 'draft') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</div>
{{-- Pilihan Gambar Poster Event --}}
<div x-data="{
    source: '{{ (isset($event) && $event->poster && Str::startsWith($event->poster, ['http://', 'https://'])) ? 'url' : 'file' }}',
    previewUrl: '{{ isset($event) ? $event->poster_url : '' }}',
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
        }
    }
}" class="border border-gray-200 rounded-xl p-4 bg-gray-50/50 space-y-3">

    <label class="block text-sm font-semibold text-gray-900">Poster / Banner Event</label>

    <!-- Tab Pilihan Metode Input -->
    <div class="flex gap-2 p-1 bg-gray-200/60 rounded-lg text-xs font-medium">
        <button type="button"
                @click="source = 'file'"
                :class="source === 'file' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                class="flex-1 py-1.5 px-3 rounded-md transition text-center">
            📁 File Explorer (Upload)
        </button>
        <button type="button"
                @click="source = 'url'"
                :class="source === 'url' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                class="flex-1 py-1.5 px-3 rounded-md transition text-center">
            🔗 Link Gambar (URL)
        </button>
    </div>

    <input type="hidden" name="image_source" :value="source">

    <!-- Input 1: File Explorer -->
    <div x-show="source === 'file'" class="space-y-2">
        <label class="block text-xs text-gray-500">Pilih berkas gambar dari komputer (JPG, PNG, WebP max 3MB):</label>
        <input type="file"
               name="poster_file"
               accept="image/*"
               @change="handleFileChange($event)"
               class="block w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-300 rounded-lg bg-white">
        @error('poster_file') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <!-- Input 2: Link URL -->
    <div x-show="source === 'url'" class="space-y-2">
        <label class="block text-xs text-gray-500">Masukkan link gambar langsung (misal dari Unsplash / CDN):</label>
        <input type="url"
               name="poster_url"
               x-model="previewUrl"
               placeholder="https://images.unsplash.com/..."
               value="{{ (isset($event) && Str::startsWith($event->poster ?? '', ['http://', 'https://'])) ? $event->poster : '' }}"
               class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('poster_url') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <!-- Pratinjau Gambar Saat Ini / Yang Dipilih -->
    <template x-if="previewUrl">
        <div class="mt-3 pt-3 border-t border-gray-200">
            <span class="block text-xs text-gray-500 mb-1.5 font-medium">Pratinjau Poster:</span>
            <div class="relative w-full h-40 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                <img :src="previewUrl" alt="Preview Poster" class="w-full h-full object-cover">
            </div>
        </div>
    </template>

    {{-- Pengaturan Desain Latar Tiket --}}
<div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-200 mt-6"
     x-data="{
        bgType: '{{ old('ticket_bg_type', $event->ticket_bg_type ?? 'gradient') }}',
        activeColor: '{{ old('ticket_bg_color', $event->ticket_bg_color ?? 'indigo') }}',
        imgSource: 'file'
     }">
    <h3 class="text-sm font-bold text-gray-900 mb-1 flex items-center gap-2">
        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
        Tampilan Latar Belakang Tiket
    </h3>
    <p class="text-xs text-gray-500 mb-4">Pilih gaya visual e-tiket yang akan diunduh oleh pembeli.</p>

    {{-- Pilihan Tipe: Gradasi vs Gambar --}}
    <div class="flex gap-4 mb-4">
        <label class="flex items-center gap-2 cursor-pointer text-sm">
            <input type="radio" name="ticket_bg_type" value="gradient" x-model="bgType" class="text-indigo-600 focus:ring-indigo-500">
            <span class="font-medium text-gray-700">Preset Warna Gradasi</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer text-sm">
            <input type="radio" name="ticket_bg_type" value="image" x-model="bgType" class="text-indigo-600 focus:ring-indigo-500">
            <span class="font-medium text-gray-700">Gambar Kustom (Upload / URL)</span>
        </label>
    </div>

    {{-- Opsi 1: Pilihan Palet Gradasi --}}
    <div x-show="bgType === 'gradient'" class="space-y-3">
        <label class="block text-xs font-semibold text-gray-600">Pilih Tema Warna:</label>
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5">
            @php
                $colors = [
                    'indigo' => ['label' => 'Indigo Violet', 'class' => 'from-indigo-600 to-violet-700'],
                    'emerald' => ['label' => 'Emerald Teal', 'class' => 'from-emerald-600 to-teal-700'],
                    'sunset' => ['label' => 'Sunset Coral', 'class' => 'from-rose-600 to-orange-500'],
                    'dark' => ['label' => 'Midnight Dark', 'class' => 'from-slate-900 to-slate-700'],
                    'gold' => ['label' => 'Gold Bronze', 'class' => 'from-amber-700 to-yellow-600'],
                    'purple' => ['label' => 'Magenta Dream', 'class' => 'from-purple-700 to-pink-600'],
                ];
            @endphp

            @foreach($colors as $val => $c)
                <label class="cursor-pointer">
                    <input type="radio" name="ticket_bg_color" value="{{ $val }}" x-model="activeColor" class="sr-only">
                    <div class="h-12 rounded-xl bg-gradient-to-br {{ $c['class'] }} flex items-center justify-center border-2 transition-all shadow-sm"
                         :class="activeColor === '{{ $val }}' ? 'border-gray-900 scale-105 ring-2 ring-indigo-400' : 'border-transparent opacity-80 hover:opacity-100'">
                        <span x-show="activeColor === '{{ $val }}'" class="text-white text-xs font-bold">✓</span>
                    </div>
                    <span class="block text-[11px] text-center text-gray-600 mt-1 truncate">{{ $c['label'] }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Opsi 2: Upload Gambar atau Link --}}
    <div x-show="bgType === 'image'" class="space-y-3" style="display: none;">
        <div class="flex items-center gap-3 border-b border-gray-200 pb-2 text-xs">
            <button type="button" @click="imgSource = 'file'"
                    :class="imgSource === 'file' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-500'">
                Upload dari File Explorer
            </button>
            <button type="button" @click="imgSource = 'link'"
                    :class="imgSource === 'link' ? 'font-bold text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-500'">
                Gunakan Link Gambar (URL)
            </button>
        </div>

        <div x-show="imgSource === 'file'">
            <input type="file" name="ticket_bg_file" accept="image/*"
                   class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WebP (Rekomendasi rasio lebar 1200x500px, maks 3MB).</p>
        </div>

        <div x-show="imgSource === 'link'" style="display: none;">
            <input type="url" name="ticket_bg_url" placeholder="https://example.com/ticket-background.jpg"
                   value="{{ old('ticket_bg_url', str_starts_with($event->ticket_bg_image ?? '', 'http') ? $event->ticket_bg_image : '') }}"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        @if(isset($event) && $event->ticket_bg_image_url)
            <div class="mt-3 flex items-center gap-3 bg-white p-2 rounded-xl border border-gray-200">
                <img src="{{ $event->ticket_bg_image_url }}" alt="Background Tiket" class="w-16 h-10 object-cover rounded-lg">
                <span class="text-xs text-gray-600">Latar gambar aktif saat ini</span>
            </div>
        @endif
    </div>
</div>
