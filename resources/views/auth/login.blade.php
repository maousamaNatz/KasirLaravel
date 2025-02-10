<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resto Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Resto" class="w-20 h-20 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
            <p class="text-gray-500">Silakan masuk ke akun Anda</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first('username') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-user text-gray-500 mr-2"></i>Username
                </label>
                <input type="text" name="username"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan username" required>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-lock text-gray-500 mr-2"></i>Password
                </label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="••••••••" required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                Masuk Sekarang
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <footer class="mt-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} Resto Kasir. All rights reserved.
        </footer>
    </div>
</body>
</html>
