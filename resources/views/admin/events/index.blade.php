@extends('layouts.admin')

@section('title', 'Events')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Events</h1>
        <a href="{{ route('admin.events.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            + Tambah Event
        </a>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tiket</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($events as $event)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $event->title }}</td>
                        <td class="px-4 py-3">
                            @if($event->category)
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full ring-1 ring-inset {{ $event->category->badge_classes }}">
                                    {{ $event->category->icon }} {{ $event->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $event->starts_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 capitalize">{{ $event->status }}</td>
                        <td class="px-4 py-3">{{ $event->ticket_types_count }} tipe</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
