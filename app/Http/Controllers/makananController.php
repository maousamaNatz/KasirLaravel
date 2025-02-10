<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;

class makananController extends Controller
{
    /**
     * Menampilkan daftar menu makanan
     */
    public function index()
    {
        // Cek autentikasi dan level pengguna
        if (!AuthController::checkAuth() || (AuthController::userLevel() != 1 && AuthController::userLevel() != 2)) {
            return redirect()->route('login')->withErrors(['auth' => 'Anda tidak memiliki akses']);
        }

        $makanans = Makanan::paginate(10);
        return view('makanans', compact('makanans'));
    }

    /**
     * Menyimpan data makanan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_masakan' => 'required|max:50',
            'harga' => 'required|numeric|min:0',
            'status_masakan' => 'required|boolean'
        ]);

        try {
            Makanan::create($validated);
            return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan menu: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form edit makanan
     */
    public function edit($id)
    {

        $makanan = Makanan::findOrFail($id);
        return view('admin.editMakanan', compact('makanan'));
    }

    /**
     * Mengupdate data makanan
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_masakan' => 'required|max:50',
            'harga' => 'required|numeric|min:0',
            'status_masakan' => 'required|boolean'
        ]);

        try {
            $makanan = Makanan::findOrFail($id);
            $makanan->update($validated);
            return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui menu: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghapus data makanan
     */
    public function destroy($id)
    {
        try {
            $makanan = Makanan::findOrFail($id);
            $makanan->delete();
            return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus menu: ' . $e->getMessage()]);
        }
    }
}
