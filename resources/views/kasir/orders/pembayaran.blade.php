@extends('layouts.app')

@section('title', 'Pembayaran Pesanan')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-center">Pembayaran Pesanan #{{ $order->id_order }}</h1>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Nomor Meja:</span>
                    <span class="font-semibold">{{ $order->no_meja }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Pesanan:</span>
                    <span class="font-semibold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <form action="{{ route('kasir.orders.pembayaran', $order->id_order) }}" method="POST" class="space-y-6" id="paymentForm">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="w-full p-3 border rounded-lg" required
                        onchange="togglePaymentFields(this.value)">
                    <option value="tunai">Tunai</option>
                    <option value="debit">Kartu Debit</option>
                    <option value="kredit">Kartu Kredit</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div id="cashPaymentFields">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Uang</label>
                    <input type="number" name="uang_bayar" id="uang_bayar"
                           class="w-full p-3 border rounded-lg"
                           min="{{ $order->total_harga }}"
                           max="999999999999999.99"
                           step="1000"
                           oninput="validateAmount(this)"
                           required>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kembalian</label>
                    <div class="w-full p-3 bg-gray-50 rounded-lg font-semibold" id="kembalian">
                        Rp 0
                    </div>
                </div>
            </div>

            <div id="nonCashPaymentFields" style="display: none;">
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-yellow-700">
                        Silakan ikuti instruksi pembayaran pada mesin EDC atau scan QRIS yang tersedia.
                    </p>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                Proses Pembayaran
            </button>
        </form>

        <div class="mt-6">
            <a href="{{ route('kasir.orders.invoice', $order->id_order) }}"
               class="block text-center text-gray-600 hover:text-gray-800">
                Kembali ke Invoice
            </a>
        </div>
    </div>
</div>

<script>
function togglePaymentFields(method) {
    const cashFields = document.getElementById('cashPaymentFields');
    const nonCashFields = document.getElementById('nonCashPaymentFields');
    const uangBayarInput = document.getElementById('uang_bayar');

    if (method === 'tunai') {
        cashFields.style.display = 'block';
        nonCashFields.style.display = 'none';
        uangBayarInput.required = true;
    } else {
        cashFields.style.display = 'none';
        nonCashFields.style.display = 'block';
        uangBayarInput.required = false;
        // Untuk non-tunai, set uang_bayar sama dengan total_harga
        uangBayarInput.value = {{ $order->total_harga }};
    }
}

function validateAmount(input) {
    const value = parseFloat(input.value);
    if (value > 999999999999999.99) {
        input.value = 999999999999999.99;
    }
    calculateChange();
}

function calculateChange() {
    const totalHarga = {{ $order->total_harga }};
    const uangBayar = parseFloat(document.getElementById('uang_bayar').value) || 0;
    const kembalian = Math.min(uangBayar - totalHarga, 999999999999999.99);
    
    document.getElementById('kembalian').textContent = 
        `Rp ${kembalian >= 0 ? kembalian.toLocaleString() : 0}`;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentFields('tunai');
});
</script>
@endsection
