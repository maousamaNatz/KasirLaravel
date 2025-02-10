@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Order</h2>
            <a href="{{ route('kasir.orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Buat Order Baru
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-medium text-gray-500">
                            <th class="px-6 py-4">No. Meja</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $order->no_meja }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ date('d/m/Y H:i', strtotime($order->tanggal)) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $order->status_order == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->status_order) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                Rp{{ number_format($order->detailOrders->sum(fn($d) => $d->makanan->harga * $d->jumlah), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('kasir.orders.show', $order->id_order) }}" class="px-3 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors text-sm">
                                    Detail
                                </a>
                                @if($order->status_order != 'selesai')
                                <form action="{{ route('kasir.orders.complete', $order->id_order) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-green-500 text-white rounded hover:bg-green-600 transition-colors text-sm">
                                        Selesaikan
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
