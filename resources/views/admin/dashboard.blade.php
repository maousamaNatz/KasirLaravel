@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="bg-white rounded-lg shadow-xl p-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Administrasi</h2>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Total Pengguna</h3>
                <p class="text-4xl font-bold text-blue-600">{{ $totalUsers }}</p>
                <div class="mt-2 text-sm text-blue-500">+{{ $newUsersLastMonth }} bulan lalu</div>
            </div>

            <div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Total Menu</h3>
                <p class="text-4xl font-bold text-green-600">{{ $totalMenus }}</p>
                <div class="mt-2 text-sm text-green-500">{{ $newMenusThisWeek }} menu baru minggu ini</div>
            </div>

            <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Total Transaksi</h3>
                <p class="text-4xl font-bold text-purple-600">{{ $totalOrders }}</p>
                <div class="mt-2 text-sm text-purple-500">{{ $completedOrders }} selesai</div>
            </div>

            <div class="bg-gradient-to-br from-amber-100 to-amber-200 p-6 rounded-xl shadow-sm">
                <h3 class="font-semibold text-gray-600 mb-2">Total Pendapatan</h3>
                <p class="text-4xl font-bold text-amber-600">@currency($totalRevenue)</p>
                <div class="mt-2 text-sm text-amber-500">@currency($avgDailyRevenue)/hari</div>
            </div>
        </div>

        <!-- Grafik dan Visualisasi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Tren Penjualan -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h4 class="text-xl font-semibold mb-4">Tren Penjualan 6 Bulan Terakhir</h4>
                <canvas id="salesChart" class="w-full h-64"></canvas>
            </div>

            <!-- Grafik Kategori Menu -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h4 class="text-xl font-semibold mb-4">Distribusi Kategori Menu</h4>
                <canvas id="categoryChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <!-- Daftar Menu Terlaris -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h4 class="text-xl font-semibold mb-4">10 Menu Terlaris</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach ($topMenus as $menu)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="font-medium text-gray-700 truncate">{{ $menu->nama_menu }}</div>
                        <div class="text-sm text-gray-500">Terjual: {{ $menu->total_sold }}</div>
                        <div class="text-sm text-green-600">Pendapatan: @currency($menu->total_revenue)</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Inisialisasi Grafik Tren Penjualan
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($salesData['labels']),
                    datasets: [{
                        label: 'Total Penjualan',
                        data: @json($salesData['data']),
                        borderColor: '#3B82F6',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.05)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp' + value.toLocaleString()
                            }
                        }
                    }
                }
            });

            // Inisialisasi Grafik Kategori
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: @json($categoryData['labels']),
                    datasets: [{
                        label: 'Jumlah Penjualan',
                        data: @json($categoryData['data']),
                        backgroundColor: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
