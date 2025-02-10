@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Total Pengguna</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
            <div class="mt-2 text-sm">
                <span class="text-gray-600">Kasir: {{ $totalKasir }}</span>
                <span class="mx-2">•</span>
                <span class="text-gray-600">Koki: {{ $totalKoki }}</span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Menu</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $totalMenu }}</p>
            <div class="mt-2 text-sm">
                <span class="text-green-600">Tersedia: {{ $menuTersedia }}</span>
                <span class="mx-2">•</span>
                <span class="text-red-600">Habis: {{ $menuHabis }}</span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Transaksi Hari Ini</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $transaksiHariIni }}</p>
            <p class="mt-2 text-sm text-green-600">
                Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Order Hari Ini</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $orderHariIni }}</p>
            <div class="mt-2 text-sm space-x-2">
                <span class="text-yellow-600">Pending: {{ $orderPending }}</span>
                <span class="text-blue-600">Proses: {{ $orderProses }}</span>
                <span class="text-green-600">Selesai: {{ $orderSelesai }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Transaksi Terbaru</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($transaksiTerbaru as $transaksi)
                    <div class="flex justify-between items-center border-b pb-4 last:border-0">
                        <div>
                            <p class="font-medium">Order #{{ $transaksi->order->id_order }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $transaksi->tanggal->format('d/m/Y H:i') }} •
                                Kasir: {{ $transaksi->user->nama_user }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-green-600">
                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Menu Terlaris -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Menu Terlaris</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($menuTerlaris as $menu)
                    <div class="flex justify-between items-center border-b pb-4 last:border-0">
                        <div>
                            <p class="font-medium">{{ $menu->nama_masakan }}</p>
                            <p class="text-sm text-gray-600">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-blue-600">
                                {{ $menu->total_dipesan }} Pesanan
                            </p>
                            <p class="text-sm {{ $menu->status_masakan === 'tersedia' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($menu->status_masakan) }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada data menu</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('admin.users.index') }}" 
           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Kelola Pengguna
        </a>
        <a href="{{ route('daftar.makanans') }}" 
           class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
            Kelola Menu
        </a>
    </div>
</div>
@endsection
