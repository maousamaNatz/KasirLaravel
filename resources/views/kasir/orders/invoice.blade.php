@extends('layouts.app')

@section('title', 'Invoice Pesanan')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8">
        <!-- Header Invoice -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">INVOICE PESANAN</h1>
            <p class="text-gray-600">{{ config('app.name') }}</p>
        </div>

        <!-- Informasi Pesanan -->
        <div class="mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Nomor Meja: {{ $order->no_meja }}</p>
                    <p class="text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($order->tanggal)->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Kasir: {{ $order->user->nama_user }}</p>
                    <p class="text-gray-600">No Order: #{{ $order->id_order }}</p>
                    <p class="text-gray-600">Status: {{ ucfirst($order->status_order) }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <table class="w-full mb-6">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-2">Menu</th>
                    <th class="text-center py-2">Qty</th>
                    <th class="text-right py-2">Harga</th>
                    <th class="text-right py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->detailOrders as $detail)
                <tr class="border-b border-gray-100">
                    <td class="py-2">{{ $detail->makanan->nama_masakan }}</td>
                    <td class="text-center py-2">{{ $detail->jumlah }}</td>
                    <td class="text-right py-2">Rp {{ number_format($detail->makanan->harga, 0, ',', '.') }}</td>
                    <td class="text-right py-2">Rp {{ number_format($detail->makanan->harga * $detail->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-bold">
                    <td colspan="3" class="text-right py-2">Total:</td>
                    <td class="text-right py-2">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Catatan -->
        @if($order->keterangan)
        <div class="mb-6">
            <p class="font-semibold">Catatan:</p>
            <p class="text-gray-600">{{ $order->keterangan }}</p>
        </div>
        @endif

        <!-- Setelah bagian total -->
        @if($order->status_pembayaran !== 'belum_bayar')
        <div class="mt-4 space-y-2">
            <div class="flex justify-between">
                <span class="font-semibold">Metode Pembayaran:</span>
                <span>{{ $order->metode_pembayaran_label }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Status Pembayaran:</span>
                <span class="
                    @if($order->status_pembayaran === 'lunas') text-green-600
                    @elseif($order->status_pembayaran === 'kurang') text-red-600
                    @else text-yellow-600
                    @endif font-semibold
                ">
                    {{ $order->status_pembayaran_label }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Uang Dibayar:</span>
                <span>Rp {{ number_format($order->uang_bayar, 0, ',', '.') }}</span>
            </div>
            @if($order->status_pembayaran === 'lunas')
            <div class="flex justify-between">
                <span class="font-semibold">Uang Kembali:</span>
                <span>Rp {{ number_format($order->uang_kembali, 0, ',', '.') }}</span>
            </div>
            @elseif($order->status_pembayaran === 'kurang')
            <div class="flex justify-between">
                <span class="font-semibold">Kekurangan:</span>
                <span class="text-red-600 font-semibold">
                    Rp {{ number_format($order->total_harga - $order->uang_bayar, 0, ',', '.') }}
                </span>
            </div>
            @endif
        </div>
        @endif

        <!-- Form Pembayaran (jika belum lunas) -->
        @if($order->status_pembayaran !== 'lunas')
        <form action="{{ route('kasir.orders.pembayaran', $order->id_order) }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="tunai">Tunai</option>
                    <option value="debit">Kartu Debit</option>
                    <option value="kredit">Kartu Kredit</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Uang</label>
                <input type="number" name="uang_bayar"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                       min="0" step="1000" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                Proses Pembayaran
            </button>
        </form>
        @endif

        <!-- Footer -->
        <div class="text-center text-gray-600 text-sm">
            <p>Terima kasih atas kunjungan Anda</p>
            <p>Silahkan berkunjung kembali</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-8 flex gap-4 justify-center">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Cetak Invoice
            </button>
            @if($order->status_order === 'pending')
            <form action="{{ route('kasir.orders.complete', $order->id_order) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Selesaikan Pesanan
                </button>
            </form>
            @endif
            <a href="{{ route('kasir.orders.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Kembali
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .bg-gray-50 { background-color: white !important; }
    .shadow-md { box-shadow: none !important; }
    button, a, form { display: none !important; }
}
</style>
@endsection
