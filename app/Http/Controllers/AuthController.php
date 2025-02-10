<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        // Tambahkan pengecekan jika sudah login
        if (AuthController::checkAuth()) {
            return $this->redirectToDashboard(AuthController::userLevel());
        }

        return view('auth.login');
    }

    /**
     * Memproses data login
     */
    public function login(Request $request)
    {
        // Validasi input dengan pesan error kustom
        $validated = $request->validate([
            'username' => 'required|string|max:30',
            'password' => 'required|string|min:6'
        ]);

        // Cari user dengan eager loading relasi level
        $user = User::with('level')->where('username', $validated['username'])->first();

        // Tambahkan pengecekan user ditemukan atau tidak
        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan',
                'password' => 'Password tidak ditemukan',
            ])->withInput($request->except('password'));
        }

        // Cek password
        if (!Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan',
                'password' => 'Password tidak ditemukan',
            ])->withInput($request->except('password'));
        }

        // Perbaikan session handling
        Session::regenerate(true); // Regenerasi lebih kuat
        Session::put([
            'id_user' => $user->id_user,
            'nama_user' => $user->nama_user,
            'id_level' => $user->id_level
        ]);
        Session::save(); // Simpan session secara eksplisit

        // Redirect ke dashboard sesuai level
        return $this->redirectToDashboard($user->id_level);
    }

    /**
     * Logout pengguna dengan membersihkan session dan redirect ke halaman login
     */
    public function logout()
    {
        // Perbaikan logout handling
        Session::flush();
        Session::regenerate(true); // Regenerasi dengan menghapus data lama
        Session::regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Redirect berdasarkan level pengguna
     */
    protected function redirectToDashboard($level)
    {
        switch ($level) {
            case 1: // Admin
                return redirect()->route('admin.dashboard');
            case 2: // Kasir
                return redirect()->route('kasir.dashboard');
            case 3: // Koki
                return redirect()->route('koki.dashboard');
            default:
                return redirect('/');
        }
    }

    /**
     * Cek status login pengguna
     */
    public static function checkAuth()
    {
        return Session::has('id_user');
    }

    /**
     * Cek level pengguna
     */
    public static function userLevel()
    {
        return Session::get('id_level');
    }

    /**
     * Dapatkan ID pengguna yang sedang login
     */
    public static function userId()
    {
        return Session::get('id_user');
    }
}
