<aside class="w-64 bg-white shadow-lg h-screen sticky top-0">
    <div class="p-4">
        <ul class="space-y-2">
            {{-- Menu Admin --}}
            @if (auth()->user()->level->nama_level == 'admin')
                <li class="group">
                    <details class="dropdown" open>
                        <summary class="flex items-center justify-between p-2 hover:bg-gray-100 rounded cursor-pointer">
                            <span class="font-medium text-gray-700">Administrator</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="ml-4 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Manajemen User</a>
                            </li>
                            <li>
                                <a href="{{ route('daftar.makanans') }}"
                                    class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Manajemen Menu</a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif

            {{-- Menu Kasir --}}
            @if (auth()->user()->level->nama_level == 'kasir')
                <li class="">
                    <a href="{{ route('kasir.dashboard') }}"
                        class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('kasir.orders.create') }}"
                        class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Transaksi Baru</a>
                </li>
                <li>
                    <a href="{{ route('kasir.orders.riwayats') }}"
                        class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Riwayat Transaksi</a>
                </li>
                <li class="">
                    <a href="{{ route('kasir.laporan') }}"
                        class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Laporan</a>
                </li>
            @endif

            {{-- Menu Koki --}}
            @if (auth()->user()->level->nama_level == 'koki')
                <li class="group">
                    <details class="dropdown" open>
                        <summary class="flex items-center justify-between p-2 hover:bg-gray-100 rounded cursor-pointer">
                            <span class="font-medium text-gray-700">Dapur</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="ml-4 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('koki.orders.index') }}"
                                    class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Antrian Pesanan</a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif

            {{-- Menu Shared --}}
            @if (auth()->user()->level->nama_level == 'admin' || auth()->user()->level->nama_level == 'kasir')
                <li class="">
                    <a href="{{ route('daftar.makanans') }}"
                        class="block p-2 text-gray-600 hover:bg-gray-100 rounded">Manajemen Menu</a>
                </li>
            @endif
        </ul>
    </div>
</aside>
