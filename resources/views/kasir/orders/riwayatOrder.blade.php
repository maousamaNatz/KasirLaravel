@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Riwayat Transaksi</h1>
                <p class="mt-2 text-gray-600">Daftar seluruh transaksi yang telah dilakukan</p>
            </div>
            <div class="w-64">
                <input type="text" placeholder="Cari transaksi..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-medium text-gray-500">
                            <th class="px-6 py-4">ID Transaksi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">No Meja</th>
                            <th class="px-6 py-4">Items</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $order->id_order }}</td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">Meja {{ $order->no_meja }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2 max-w-xs">
                                    @foreach($order->detailOrders as $detail)
                                    <div class="px-2 py-1 bg-gray-100 rounded text-sm">
                                        {{ $detail->makanan->nama_masakan }}
                                        <span class="text-gray-500">(x{{ $detail->jumlah }})</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                Rp{{ number_format($order->detailOrders->sum(function($item) { return $item->makanan->harga * $item->jumlah; }), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $order->status_order == 'selesai' ? 'bg-green-100 text-green-800' :
                                       ($order->status_order == 'proses' ? 'bg-yellow-100 text-yellow-800' :
                                       ($order->status_order == 'dibatalkan' ? 'bg-red-100 text-red-800' :
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($order->status_order) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
                    </div>
                    <div class="space-x-2">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
