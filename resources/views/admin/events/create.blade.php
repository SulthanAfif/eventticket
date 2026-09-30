@extends('layouts.admin')

@section('title', 'Tambah Event')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Event</h1>

    <form action="{{ route('admin.events.store') }}" method="POST"
          class="bg-white rounded-xl border p-6 max-w-xl space-y-4">
        @csrf
        @include('admin.events._form')
        <button class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
            Simpan
        </button>
    </form>
@endsection
