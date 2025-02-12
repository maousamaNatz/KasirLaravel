@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-blue-100 pb-4">✏️ Edit Pengguna</h2>

            <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-3">
                        Nama Lengkap
                    </label>
                    <input type="text" name="nama_user"
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                           value="{{ old('nama_user', $user->nama_user) }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-3">
                        Username
                    </label>
                    <input type="text" name="username"
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                           value="{{ old('username', $user->username) }}" required>
                </div>

                <div class="mb-6 relative">
                    <label class="block text-gray-700 text-sm font-semibold mb-3">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg shadow-sm pr-12 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                               placeholder="Biarkan kosong untuk tetap menggunakan password lama">
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
                            👁️
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-400">
                        Kosongkan field ini jika tidak ingin mengubah password
                    </p>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-semibold mb-3">
                        Level Akses
                    </label>
                    <select name="id_level" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 appearance-none" required>
                        <option value="">Pilih Level Pengguna</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->id_level }}"
                                {{ old('id_level', $user->id_level) == $level->id_level ? 'selected' : '' }}
                                class="hover:bg-blue-50">
                            {{ $level->nama_level }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 transform hover:scale-[1.02] shadow-md">
                        🔄 Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
    }
</script>
@endsection
