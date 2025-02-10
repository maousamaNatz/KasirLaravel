<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasirResto - @yield('title')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl font-bold text-indigo-600">KasirResto</span>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @if(auth()->check())
                        <div class="relative group">
                            <button class="flex items-center space-x-2 focus:outline-none">
                                <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ Session::get('nama_user') }}</span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content Area -->
        <main class="flex-1 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Page Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('title')</h2>
                </div>

                <!-- Content -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
