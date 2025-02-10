<div class="md:fixed md:w-full md:top-0 md:z-20 flex flex-row flex-wrap items-center bg-white p-4 border-b border-gray-300 shadow-sm">
    <!-- logo -->
    <div class="flex-none w-48 flex items-center space-x-3">
        <img src="{{ asset('img/logo.png') }}" alt="Logo KasirResto" class="w-8 flex-none">
        <span class="text-xl font-bold text-indigo-600">KasirResto</span>
    </div>
    <!-- end logo -->

    <!-- navbar content -->
    <div id="navbar" class="animated md:hidden md:fixed md:top-0 md:w-full md:left-0 md:mt-16 md:border-t md:border-b md:border-gray-200 md:p-10 md:bg-white flex-1 pl-3 flex flex-row flex-wrap justify-between items-center md:flex-col md:items-center">
        <!-- right -->
        <div class="flex items-center space-x-6">
            <!-- Notifikasi Pesanan -->
            <a href="{{ route('kasir.orders.index') }}" class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="ml-2 font-medium">Pesanan Aktif</span>
            </a>

            <!-- user dropdown -->
            <div class="dropdown relative md:static">
                <button class="menu-btn focus:outline-none flex items-center space-x-2">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="capitalize flex items-center">
                        <span class="text-sm text-gray-800 font-medium">
                            {{ Session::get('nama_user') ?? 'Kasir' }}
                        </span>
                    </div>
                </button>

                <div class="menu hidden md:mt-10 md:w-full rounded-lg bg-white shadow-lg absolute z-20 right-0 w-48 mt-2 py-1">
                    <!-- Profile -->
                    <a class="px-4 py-2 block text-gray-700 hover:bg-gray-100 text-sm font-medium"
                       href="{{ route('profile.edit') }}">
                        <i class="fad fa-user-edit text-xs mr-2"></i>
                        Profil Kasir
                    </a>

                    <hr class="my-2 border-gray-200">

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 block text-gray-700 hover:bg-gray-100 text-sm font-medium">
                            <i class="fad fa-sign-out-alt text-xs mr-2"></i>
                            Logout Kasir
                        </button>
                    </form>
                </div>
            </div>
            <!-- end user dropdown -->
        </div>
        <!-- end right -->
    </div>
    <!-- end navbar content -->
</div>
