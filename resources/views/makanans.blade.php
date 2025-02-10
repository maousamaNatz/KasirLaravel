@extends('layouts.app')

@section('title', 'Daftar Menu Makanan')

@section('content')
@php

$userLevel = Session::get('id_level');
@endphp
<div class="container mx-auto p-6 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar Menu Makanan</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">Nama Makanan</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">Harga</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($makanans as $makanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-b border-gray-200">{{ $makanan->id_masakan }}</td>
                        <td class="px-6 py-4 border-b border-gray-200">{{ $makanan->nama_masakan }}</td>
                        <td class="px-6 py-4 border-b border-gray-200">Rp{{ number_format($makanan->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <span class="px-2 py-1 text-sm rounded-full {{ $makanan->status_masakan ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $makanan->status_masakan ? 'Tersedia' : 'Habis' }}
                            </span>
                        </td>
                        @if ($userLevel == 1)
                        <td class="px-6 py-4 border-b border-gray-200">
                            <a href="{{ route('admin.makanans.edit', $makanan->id_masakan) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                            <form action="{{ route('admin.makanans.destroy', $makanan->id_masakan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data makanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $makanans->links() }}
        </div>
    </div>
</div>
@endsection
