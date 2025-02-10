@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pesanan Baru</h1>
            <a href="{{ route('kasir.orders.index') }}" class="text-blue-500 hover:text-blue-700">
                Kembali ke Daftar Pesanan
            </a>
        </div>

        <form action="{{ route('kasir.orders.store') }}" method="POST" id="orderForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Pesanan -->
                <div class="col-span-2">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. Meja</label>
                        <input type="number" name="no_meja" class="w-full border-2 border-gray-200 p-3 rounded-lg"
                               min="1" required placeholder="Masukkan nomor meja">
                    </div>
                </div>

                <!-- Daftar Menu -->
                <div class="col-span-2">
                    <h3 class="text-xl font-semibold mb-4">Menu Tersedia</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                        @foreach($makanans as $menu)
                        <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow cursor-pointer border"
                             onclick="addMenuItem({{ $menu->id_masakan }}, '{{ $menu->nama_masakan }}', {{ $menu->harga }})">
                            <div class="text-center">
                                <h4 class="font-bold text-gray-800">{{ $menu->nama_masakan }}</h4>
                                <p class="text-sm text-gray-600 mt-1">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Daftar Pesanan -->
                <div class="col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold mb-4">Pesanan</h3>
                        <div id="order-list" class="space-y-3">
                            <!-- Item pesanan akan ditambahkan di sini -->
                        </div>

                        <!-- Total -->
                        <div class="mt-4 border-t pt-4">
                            <div class="flex justify-between items-center text-xl font-bold">
                                <span>Total:</span>
                                <span id="total-amount" class="text-blue-600">Rp0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Container untuk input hidden -->
                <div id="items-container" style="display: none;"></div>

                <!-- Catatan -->
                <div class="col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                              class="w-full border-2 border-gray-200 p-3 rounded-lg resize-none"
                              placeholder="Tambahkan catatan pesanan (opsional)"></textarea>
                </div>

                <div class="col-span-2">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition-colors"
                            id="submitBtn" disabled>
                        BUAT PESANAN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let selectedItems = [];

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

function updateOrderList() {
    const orderList = document.getElementById('order-list');
    const container = document.getElementById('items-container');
    const submitBtn = document.getElementById('submitBtn');

    orderList.innerHTML = '';
    container.innerHTML = '';

    const total = selectedItems.reduce((sum, item) => sum + (item.harga * item.quantity), 0);

    selectedItems.forEach((item, index) => {
        const orderItem = document.createElement('div');
        orderItem.className = 'flex items-center justify-between bg-white p-3 rounded-lg shadow-sm';
        orderItem.innerHTML = `
            <div class="flex-1">
                <h4 class="font-semibold">${item.nama}</h4>
                <p class="text-sm text-gray-500">Rp${item.harga.toLocaleString()}</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="updateQuantity(${item.id}, 'decrement')"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">-</button>
                <span class="w-8 text-center">${item.quantity}</span>
                <button type="button" onclick="updateQuantity(${item.id}, 'increment')"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">+</button>
                <button type="button" onclick="removeItem(${item.id})"
                        class="ml-2 text-red-500 hover:text-red-700">✕</button>
            </div>
        `;
        orderList.appendChild(orderItem);

        // Add hidden inputs
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

    document.getElementById('total-amount').textContent = `Rp${total.toLocaleString()}`;
    submitBtn.disabled = selectedItems.length === 0;
}
</script>
@endsection
