@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Edit Menu Makanan</h1>
            <p class="text-gray-500 mt-2">Perbarui informasi menu makanan dengan data terbaru</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl">
            <form action="{{ route('admin.makanans.update', $makanan->id_masakan) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Menu Input -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nama Menu
                    </label>
                    <input type="text" id="nama_masakan" name="nama_masakan"
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all
                           @error('nama_masakan') border-red-500 @enderror"
                           value="{{ old('nama_masakan', $makanan->nama_masakan) }}">
                    @error('nama_masakan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Input -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Harga
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" id="harga" name="harga"
                               class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all
                               @error('harga') border-red-500 @enderror"
                               value="{{ old('harga', $makanan->harga) }}">
                    </div>
                    @error('harga')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Select -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status Ketersediaan
                    </label>
                    <div class="relative">
                        <select id="status_masakan" name="status_masakan"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-all
                                @error('status_masakan') border-red-500 @enderror">
                            <option value="1" {{ $makanan->status_masakan ? 'selected' : '' }}>Tersedia</option>
                            <option value="0" {{ !$makanan->status_masakan ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('status_masakan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.makanans.index') }}" class="flex items-center px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" class="flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
