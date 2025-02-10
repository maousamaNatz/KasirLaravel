@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Transaksi Baru</h1>

        <form action="{{ route('kasir.orders.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Pelanggan -->
                <div class="col-span-2 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pelanggan</label>
                        <input type="text" name="customer_name" class="w-full border-2 border-gray-200 p-3 rounded-lg"
                               placeholder="Masukkan nama pelanggan">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. Meja</label>
                        <input type="number" name="no_meja" class="w-full border-2 border-gray-200 p-3 rounded-lg"
                               min="1" max="50" required placeholder="Masukkan nomor meja">
                    </div>
                </div>

                <!-- Daftar Menu -->
                <div class="col-span-2">
                    <h3 class="text-xl font-semibold mb-4">Pilih Menu</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach($makanans as $menu)
                        <div class="bg-gray-50 p-4 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors border-2"
                             onclick="addMenuItem({{ $menu->id_masakan }}, '{{ $menu->nama_masakan }}', {{ $menu->harga }})">
                            <div class="h-32 bg-gray-200 rounded-lg mb-2"></div>
                            <h4 class="font-bold text-gray-800">{{ $menu->nama_masakan }}</h4>
                            <p class="text-sm text-gray-600">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Daftar Pesanan -->
                <div class="col-span-2">
                    <h3 class="text-xl font-semibold mb-4">Pesanan</h3>
                    <div id="order-list" class="space-y-3">
                        <!-- Item pesanan akan ditambahkan di sini secara dinamis -->
                    </div>
                </div>

                <!-- Container untuk input hidden -->
                <div id="items-container" style="display: none;"></div>

                <!-- Total dan Pembayaran -->
                <div class="col-span-2 border-t pt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran</label>
                            <select name="payment_method" class="w-full border-2 border-gray-200 p-3 rounded-lg" required>
                                <option value="cash">Tunai</option>
                                <option value="debit">Debit</option>
                                <option value="credit">Kredit</option>
                                <option value="e-wallet">E-Wallet</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
                            <input type="text" name="notes" class="w-full border-2 border-gray-200 p-3 rounded-lg"
                                   placeholder="Catatan tambahan">
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">Subtotal:</span>
                            <span class="font-bold text-xl" id="subtotal">Rp0</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">PPN (11%):</span>
                            <span class="font-bold text-xl" id="tax-amount">Rp0</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">Total:</span>
                            <span class="font-bold text-xl text-blue-600" id="total-amount">Rp0</span>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pembayaran</label>
                            <input type="number" name="payment_amount" id="payment-amount"
                                   class="w-full border-2 border-gray-200 p-3 rounded-lg"
                                   required placeholder="Masukkan jumlah pembayaran"
                                   oninput="calculateChange()">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kembalian</label>
                            <input type="text" id="change-amount" name="change_amount"
                                   class="w-full border-2 border-gray-200 p-3 rounded-lg bg-gray-100"
                                   readonly>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition-colors mt-6">
                        SIMPAN TRANSAKSI
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedItems = [];
    const TAX_RATE = 0.11; // 11% PPN

    function addMenuItem(id, nama, harga) {
        const existingItem = selectedItems.find(item => item.id === id);

        if(existingItem) {
            existingItem.quantity++;
        } else {
            selectedItems.push({
                id: id,
                nama: nama,
                harga: harga,
                quantity: 1
            });
        }

        updateOrderList();
    }

    function updateQuantity(id, action) {
        const item = selectedItems.find(item => item.id === id);
        if(action === 'increment') {
            item.quantity++;
        } else if(action === 'decrement' && item.quantity > 1) {
            item.quantity--;
        }
        updateOrderList();
    }

    function removeItem(id) {
        selectedItems = selectedItems.filter(item => item.id !== id);
        updateOrderList();
    }

    function calculateChange() {
        const totalAmount = calculateTotal();
        const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
        const changeAmount = paymentAmount - totalAmount;

        document.getElementById('change-amount').value = changeAmount >= 0
            ? `Rp${changeAmount.toLocaleString()}`
            : `Kekurangan: Rp${Math.abs(changeAmount).toLocaleString()}`;
    }

    function calculateTotal() {
        const subtotal = selectedItems.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
        const taxAmount = subtotal * TAX_RATE;
        return subtotal + taxAmount;
    }

    function updateOrderList() {
        const orderList = document.getElementById('order-list');
        const container = document.getElementById('items-container');

        // Bersihkan konten sebelumnya
        orderList.innerHTML = '';
        container.innerHTML = '';

        const subtotal = selectedItems.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
        const taxAmount = subtotal * TAX_RATE;
        const total = subtotal + taxAmount;

        selectedItems.forEach((item, index) => {
            // Tambahkan item ke daftar pesanan
            const orderItem = document.createElement('div');
            orderItem.className = 'flex items-center justify-between bg-gray-50 p-3 rounded-lg';
            orderItem.innerHTML = `
                <div class="flex-1">
                    <h4 class="font-semibold">${item.nama}</h4>
                    <p class="text-sm text-gray-500">Rp${item.harga.toLocaleString()}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="updateQuantity(${item.id}, 'decrement')" class="px-3 py-1 bg-gray-200 rounded">-</button>
                    <span class="w-8 text-center">${item.quantity}</span>
                    <button type="button" onclick="updateQuantity(${item.id}, 'increment')" class="px-3 py-1 bg-gray-200 rounded">+</button>
                    <button type="button" onclick="removeItem(${item.id})" class="text-red-500 hover:text-red-700 ml-2">✕</button>
                </div>
            `;
            orderList.appendChild(orderItem);

            // Tambahkan input hidden
            const itemInput = document.createElement('input');
            itemInput.type = 'hidden';
            itemInput.name = `items[${index}][id]`;
            itemInput.value = item.id;
            container.appendChild(itemInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = `items[${index}][qty]`;
            qtyInput.value = item.quantity;
            container.appendChild(qtyInput);
        });

        // Update tampilan total
        document.getElementById('subtotal').textContent = `Rp${subtotal.toLocaleString()}`;
        document.getElementById('tax-amount').textContent = `Rp${taxAmount.toLocaleString()}`;
        document.getElementById('total-amount').textContent = `Rp${total.toLocaleString()}`;

        calculateChange();
    }
</script>
@endsection
