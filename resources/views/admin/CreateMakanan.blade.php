@extends('layouts.app')

@section('title', 'Tambah Menu Makanan Baru')

@section('content')
<div class="container mx-auto p-6 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Menu Makanan Baru</h2>

        <form method="POST" action="{{ route('admin.menu.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_masakan">
                    Nama Makanan
                </label>
                <input type="text" name="nama_masakan" id="nama_masakan" required
                    class="w-full px-3 py-2 border rounded-lg @error('nama_masakan') border-red-500 @enderror"
                    value="{{ old('nama_masakan') }}">
                @error('nama_masakan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">
                    Harga
                </label>
                <input type="number" name="harga" id="harga" min="0" required
                    class="w-full px-3 py-2 border rounded-lg @error('harga') border-red-500 @enderror"
                    value="{{ old('harga') }}">
                @error('harga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status_masakan">
                    Status Ketersediaan
                </label>
                <select name="status_masakan" id="status_masakan" required
                    class="w-full px-3 py-2 border rounded-lg @error('status_masakan') border-red-500 @enderror">
                    <option value="1" {{ old('status_masakan') == 1 ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ old('status_masakan') == 0 ? 'selected' : '' }}>Habis</option>
                </select>
                @error('status_masakan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                    Tambah Menu
                </button>
                <a href="{{ route('daftar.makanans') }}"
                    class="text-gray-600 hover:text-gray-800 ml-4">
                    Kembali ke Daftar Menu
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
