@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Dashboard -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Kasir</h1>
            <p class="mt-2 text-gray-600">Ringkasan aktivitas transaksi hari ini</p>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Total Transaksi</h3>
                <p class="text-4xl font-bold text-blue-600">{{ $totalTransactions }}</p>
                <div class="mt-2 text-sm text-blue-500">{{ $completedTransactions }} selesai</div>
            </div>

            <div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Pendapatan Hari Ini</h3>
                <p class="text-4xl font-bold text-green-600">Rp{{ number_format($dailyRevenue, 0, ',', '.') }}</p>
                <div class="mt-2 text-sm text-green-500">Rata-rata Rp{{ number_format($averageTransaction, 0, ',', '.') }}/transaksi</div>
            </div>

            <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Pesanan Aktif</h3>
                <p class="text-4xl font-bold text-purple-600">{{ $activeOrders }}</p>
                <div class="mt-2 text-sm text-purple-500">{{ $pendingOrders }} dalam proses</div>
            </div>
        </div>

        <!-- Daftar Pesanan Terbaru -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">10 Transaksi Terakhir</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-medium text-gray-500">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">No Meja</th>
                            <th class="px-6 py-4">Items</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->tanggal)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">Meja {{ $order->no_meja }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->detailOrders->groupBy('id_masakan') as $id => $details)
                                        @php
                                            $jumlah = $details->sum('jumlah');
                                            $makanan = $details->first()->makanan;
                                        @endphp
                                        <span class="px-2 py-1 bg-gray-100 rounded text-sm">
                                            {{ $makanan->nama_masakan }} x{{ $jumlah }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                Rp{{ number_format($order->detailOrders->sum(fn($d) => $d->makanan->harga * $d->jumlah), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $order->status_order == 'selesai' ? 'bg-green-100 text-green-800' :
                                       ($order->status_order == 'proses' ? 'bg-yellow-100 text-yellow-800' :
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->status_order) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada transaksi hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
