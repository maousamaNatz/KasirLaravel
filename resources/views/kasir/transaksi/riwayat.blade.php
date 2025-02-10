@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Pendapatan Hari Ini:</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Filter dan Pencarian (Opsional) -->
        <div class="mb-6">
            <form action="{{ route('kasir.transaksi.riwayat') }}" method="GET" class="flex gap-4">
                <input type="date" name="tanggal" 
                       class="border rounded px-3 py-2"
                       value="{{ request('tanggal') }}">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>
                @if(request('tanggal'))
                <a href="{{ route('kasir.transaksi.riwayat') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">No Order</th>
                        <th class="px-4 py-2 text-left">No Meja</th>
                        <th class="px-4 py-2 text-left">Kasir</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $transaksi)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $transaksis->firstItem() + $index }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">#{{ $transaksi->order->id_order }}</td>
                        <td class="px-4 py-2">{{ $transaksi->order->no_meja }}</td>
                        <td class="px-4 py-2">{{ $transaksi->user->nama_user }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('kasir.orders.invoice', $transaksi->order->id_order) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                Lihat Invoice
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transaksis->links() }}
        </div>

        <!-- Ringkasan -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-green-700">Total Transaksi</h3>
                <p class="text-2xl font-bold text-green-600">{{ $transaksis->total() }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-700">Rata-rata Transaksi</h3>
                <p class="text-2xl font-bold text-blue-600">
                    Rp {{ number_format($transaksis->avg('total_bayar'), 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="font-semibold text-purple-700">Total Pendapatan</h3>
                <p class="text-2xl font-bold text-purple-600">
                    Rp {{ number_format($transaksis->sum('total_bayar'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 