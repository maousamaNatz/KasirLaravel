@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Daftar Pesanan</h2>

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">No Pesanan</th>
                        <th class="px-4 py-2">Meja</th>
                        <th class="px-4 py-2">Menu</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="border px-4 py-2">#{{ $order->id }}</td>
                            <td class="border px-4 py-2">{{ $order->table_number }}</td>
                            <td class="border px-4 py-2">
                                <ul>
                                    @foreach($order->orderDetails as $detail)
                                        <li>{{ $detail->menu->name }} ({{ $detail->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border px-4 py-2">{{ $order->status }}</td>
                            <td class="border px-4 py-2">
                                <form action="{{ route('koki.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                                        Selesai
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection