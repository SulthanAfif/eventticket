@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Event</h1>
        <form action="{{ route('admin.events.update', $event) }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-xl border p-6 max-w-xl space-y-4 mb-8">
        @csrf
        @method('PUT')
        @include('admin.events._form')
        <div class="flex gap-3 pt-2">
            <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                Update Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="px-5 py-2.5 rounded-lg text-sm border font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>


    {{-- Tipe Tiket --}}
    <div class="bg-white rounded-xl border p-6 max-w-xl">
        <h2 class="font-semibold mb-4">Tipe Tiket</h2>

        <ul class="space-y-2 mb-6">
            @foreach($event->ticketTypes as $type)
                <li class="flex justify-between items-center text-sm border rounded-lg px-3 py-2">
                    <span>
                        {{ $type->name }} —
                        Rp {{ number_format($type->price, 0, ',', '.') }}
                        ({{ $type->sold }}/{{ $type->quota }})
                    </span>
                    @if($type->sold === 0)
                        <form action="{{ route('admin.events.ticket-types.destroy', [$event, $type]) }}" method="POST"
                              onsubmit="return confirm('Hapus tipe ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 text-xs hover:underline">Hapus</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>

        <form action="{{ route('admin.events.ticket-types.store', $event) }}" method="POST"
              class="grid grid-cols-3 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nama</label>
                <input type="text" name="name" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Harga</label>
                <input type="number" name="price" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Kuota</label>
                <input type="number" name="quota" required min="1" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <button class="col-span-3 bg-gray-800 text-white py-2 rounded-lg text-sm hover:bg-gray-900">
                + Tambah Tipe Tiket
            </button>
        </form>
    </div>
@endsection
