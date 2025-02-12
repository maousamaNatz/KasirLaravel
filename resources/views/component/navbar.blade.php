<div class="md:fixed md:w-full md:top-0 md:z-20 flex flex-row flex-wrap items-center bg-white p-4 border-b border-gray-300 shadow-sm">
    <!-- logo -->
    <div class="flex-none w-48 flex items-center space-x-3">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <img src="{{ asset('img/logo.png') }}" alt="Logo KasirResto" class="w-8 flex-none">
            <span class="text-xl font-bold text-indigo-600">KasirResto</span>
        </a>
    </div>

    <!-- navbar content -->
    <div id="navbar" class="md:hidden md:fixed md:top-0 md:w-full md:left-0 md:mt-16 md:border-t md:border-b md:border-gray-200 md:p-10 md:bg-white flex-1 pl-3 flex flex-row flex-wrap justify-between items-center md:flex-col md:items-center">
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
            <div class="dropdown relative">
                <button id="userMenuButton" class="flex items-center space-x-2 cursor-pointer focus:outline-none" aria-haspopup="true" aria-expanded="false">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="capitalize flex items-center">
                        <span class="text-sm text-gray-800 font-medium">
                            {{ Session::get('nama_user') ?? 'Pengguna' }}
                        </span>
                        <svg id="dropdownArrow" class="w-4 h-4 ml-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <div id="userMenu" class="hidden absolute right-0 z-20 mt-2 w-48 bg-white rounded-lg shadow-lg py-1">
                    <!-- Profile -->
                    <a class="px-4 py-2 block text-gray-700 hover:bg-indigo-50 text-sm font-medium transition-colors"
                       href="{{ route('profile.edit') }}">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Pengguna
                    </a>

                    <hr class="my-2 border-gray-200">

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 block text-gray-700 hover:bg-indigo-50 text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');

    // Toggle dropdown menu
    menuButton.addEventListener('click', function(e) {
        e.stopPropagation();
        const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
        userMenu.classList.toggle('hidden', isExpanded);
        dropdownArrow.classList.toggle('rotate-180', !isExpanded);
        menuButton.setAttribute('aria-expanded', String(!isExpanded));
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!userMenu.contains(e.target) && !menuButton.contains(e.target)) {
            userMenu.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
            menuButton.setAttribute('aria-expanded', 'false');
        }
    });

    // Close menu on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !userMenu.classList.contains('hidden')) {
            userMenu.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
            menuButton.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>
