@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Menu</h1>
        <a href="{{ route('admin.makanans.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Tambah Menu
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($menus as $menu)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($menu->gambar)
            <img src="{{ asset('storage/' . $menu->gambar) }}"
                 alt="{{ $menu->nama_masakan }}"
                 class="w-full h-48 object-cover">
            @endif
            <div class="p-4">
                <h3 class="text-lg font-semibold">{{ $menu->nama_masakan }}</h3>
                <p class="text-gray-600">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                <div class="mt-2">
                    <span class="px-2 py-1 rounded text-sm
                        {{ $menu->status_masakan === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($menu->status_masakan) }}
                    </span>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.makanans.edit', $menu->id_masakan) }}"
                       class="text-blue-600 hover:text-blue-900">Edit</a>
                    <form action="{{ route('admin.makanans.destroy', $menu->id_masakan) }}"
                          method="POST"
                          class="inline"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
