@extends('layouts.admin')

@section('title', 'Check-in')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Check-in Tiket</h1>

    <form action="{{ route('admin.tickets.check-in.store') }}" method="POST"
          class="bg-white rounded-xl border p-6 max-w-md space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Kode Tiket</label>
            <input type="text" name="code" required autofocus
                   placeholder="Contoh: ABC123XYZ"
                   class="w-full rounded-lg border-gray-300 font-mono uppercase"
                   value="{{ old('code') }}">
        </div>
        <button class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-medium hover:bg-indigo-700">
            Check-in
        </button>
    </form>
@endsection
