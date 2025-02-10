@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Tambah Menu Baru</h2>

            <form action="{{ route('admin.makanans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nama Menu
                    </label>
                    <input type="text" name="nama_masakan"
                           class="w-full border-gray-300 rounded-md shadow-sm"
                           value="{{ old('nama_masakan') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Harga
                    </label>
                    <input type="number" name="harga"
                           class="w-full border-gray-300 rounded-md shadow-sm"
                           value="{{ old('harga') }}" required min="0">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Status
                    </label>
                    <select name="status_masakan" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Gambar
                    </label>
                    <input type="file" name="gambar"
                           class="w-full border-gray-300 rounded-md shadow-sm"
                           accept="image/*">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
