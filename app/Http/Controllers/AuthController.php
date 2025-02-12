<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk menangani autentikasi pengguna
 * Controller ini berisi fungsi-fungsi untuk login dan logout user
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     * Method ini akan merender view auth.login untuk form login
     */
    public function index()
    {
        // Mengembalikan view login.blade.php yang ada di folder auth
        return view('auth.login');
    }

    /**
     * Melakukan proses login pengguna
     * Method ini memvalidasi input username dan password
     * Jika valid, user akan diarahkan sesuai levelnya
     *
     * @param Request $request Request yang berisi username dan password
     * @return \Illuminate\Http\JsonResponse Response berupa token dan data user jika berhasil
     */
    public function login(Request $request)
    {
        // Validasi input username dan password harus diisi
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah credentials valid menggunakan Auth::attempt()
        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Ambil data user yang login
            $user = Auth::user();

            // Redirect berdasarkan level user menggunakan switch case
            switch($user->level->nama_level) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'kasir':
                    return redirect()->intended(route('kasir.dashboard'));
                case 'koki':
                    return redirect()->intended(route('koki.dashboard'));
                default:
                    return redirect()->intended('/');
            }
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->except('password'));
    }

    /**
     * Melakukan proses logout pengguna
     * Method ini akan menghapus session dan token user
     * Kemudian redirect ke halaman utama
     *
     * @param Request $request Request dari user yang sedang login
     * @return \Illuminate\Http\JsonResponse Response berupa pesan sukses
     */
    public function logout(Request $request)
    {
        // Logout user yang sedang login
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Generate token baru
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/');
    }
}
