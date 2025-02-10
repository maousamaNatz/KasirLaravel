@extends('layouts.app')

@section('title', 'Dashboard Koki')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Total Pesanan Hari Ini</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Pesanan Pending</h3>
            <p class="text-2xl font-bold text-yellow-500">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Sedang Diproses</h3>
            <p class="text-2xl font-bold text-blue-500">{{ $prosesOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Pesanan Selesai</h3>
            <p class="text-2xl font-bold text-green-500">{{ $completedOrders }}</p>
        </div>
    </div>

    <!-- Daftar Pesanan Aktif -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Pesanan Aktif</h2>
        </div>
        <div class="p-6">
            @forelse($activeOrders as $order)
            <div class="mb-6 last:mb-0">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">Order #{{ $order->id_order }}</h3>
                        <p class="text-sm text-gray-600">
                            Meja {{ $order->no_meja }} •
                            {{ $order->tanggal->format('H:i') }} •
                            Kasir: {{ $order->user->nama_user }}
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm
                        @if($order->status_order === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status_order === 'proses') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($order->status_order) }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($order->detailOrders as $detail)
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium">{{ $detail->makanan->nama_masakan }}</h4>
                            <p class="text-sm text-gray-600">{{ $detail->jumlah }}x</p>
                            @if($detail->keterangan)
                            <p class="text-sm text-gray-500 mt-1">Catatan: {{ $detail->keterangan }}</p>
                            @endif
                        </div>
                        <div class="ml-4">
                            <form action="{{ route('koki.orders.update', $detail->id_detail_order) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status"
                                        onchange="this.form.submit()"
                                        class="rounded border-gray-300 text-sm
                                        @if($detail->status_detail_order === 'pending') text-yellow-600
                                        @elseif($detail->status_detail_order === 'diproses') text-blue-600
                                        @else text-green-600 @endif">
                                    <option value="pending"
                                            {{ $detail->status_detail_order === 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="diproses"
                                            {{ $detail->status_detail_order === 'diproses' ? 'selected' : '' }}>
                                        Diproses
                                    </option>
                                    <option value="selesai"
                                            {{ $detail->status_detail_order === 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                Tidak ada pesanan aktif saat ini
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
