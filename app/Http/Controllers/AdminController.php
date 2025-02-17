<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Models\Order;
use App\Models\Makanan;
use App\Models\Transaksi;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik User
        $totalUsers = User::count();
        $totalKasir = User::where('id_level', 2)->count();
        $totalKoki = User::where('id_level', 3)->count();

        // Statistik Menu
        $totalMenu = Makanan::count();
        $menuTersedia = Makanan::where('status_masakan', 'tersedia')->count();
        $menuHabis = Makanan::where('status_masakan', 'habis')->count();

        // Statistik Transaksi Hari Ini
        $transaksiHariIni = Transaksi::whereDate('tanggal', today())->count();
        $pendapatanHariIni = Transaksi::whereDate('tanggal', today())->sum('total_bayar');

        // Statistik Order
        $orderHariIni = Order::whereDate('tanggal', today())->count();
        $orderPending = Order::where('status_order', 'pending')->count();
        $orderProses = Order::where('status_order', 'proses')->count();
        $orderSelesai = Order::where('status_order', 'selesai')->count();

        // Transaksi Terbaru
        $transaksiTerbaru = Transaksi::with(['order.user', 'user'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Menu Terlaris
        $menuTerlaris = Makanan::withCount(['detailOrders as total_dipesan'])
            ->orderBy('total_dipesan', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalKasir',
            'totalKoki',
            'totalMenu',
            'menuTersedia',
            'menuHabis',
            'transaksiHariIni',
            'pendapatanHariIni',
            'orderHariIni',
            'orderPending',
            'orderProses',
            'orderSelesai',
            'transaksiTerbaru',
            'menuTerlaris'
        ));
    }

    // Fungsi helper untuk mendapatkan data penjualan 6 bulan terakhir
    private function getSalesData()
    {
        $months = collect(range(5, 0))->map(function($i) {
            $date = now()->subMonths($i);
            return [
                'label' => $date->format('M Y'),
                'date' => $date
            ];
        });

        $sales = Order::where('status_order', 'selesai')
            ->whereDate('tanggal', '>=', now()->subMonths(6))
            ->with(['detailOrders.makanan'])
            ->get()
            ->groupBy(function($order) {
                return $order->tanggal->format('M Y');
            })
            ->map(function($orders) {
                return $orders->sum(function($order) {
                    return $order->detailOrders->sum(fn($detail) => $detail->makanan->harga);
                });
            });

        return [
            'labels' => $months->pluck('label')->toArray(),
            'data' => $months->map(function($month) use ($sales) {
                return $sales[$month['label']] ?? 0;
            })->toArray()
        ];
    }

    // Fungsi helper untuk mendapatkan data kategori menu
    private function getCategoryData()
    {
        // Contoh sederhana - sesuaikan dengan struktur kategori yang sebenarnya
        $categories = DetailOrder::with('makanan')
            ->whereHas('order', function($query) {
                $query->where('status_order', 'selesai');
            })
            ->get()
            ->groupBy('makanan.nama_masakan')
            ->map->count();

        return [
            'labels' => $categories->keys()->toArray(),
            'data' => $categories->values()->toArray()
        ];
    }

    // Fungsi helper untuk mendapatkan 10 menu terlaris
    private function getTopMenus()
    {
        return DetailOrder::with('makanan')
            ->whereHas('order', function($query) {
                $query->where('status_order', 'selesai');
            })
            ->select('detail_orders.id_masakan')
            ->selectRaw('COUNT(*) as total_sold')
            ->selectRaw('SUM(makanans.harga) as total_revenue')
            ->selectRaw('makanans.nama_masakan as nama_menu')
            ->join('makanans', 'detail_orders.id_masakan', '=', 'makanans.id_masakan')
            ->groupBy('detail_orders.id_masakan', 'makanans.nama_masakan')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
    }

    // CRUD User
    public function index()
    {
        $users = User::with('level')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $levels = Level::all();
        return view('admin.users.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'id_level' => 'required|exists:levels,id_level'
        ]);

        User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_level' => $request->id_level
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $levels = Level::all();
        return view('admin.users.edit', compact('user', 'levels'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'id_level' => 'required|exists:levels,id_level'
        ]);

        // Siapkan data untuk update
        $data = [
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'id_level' => $request->id_level
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data user
        try {
            $user->update($data);
            return redirect()->route('admin.users.index')
                ->with('success', 'Data pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data pengguna: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        if ($user->id_level === 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus user admin');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    // CRUD Makanan
    public function makananIndex()
    {
        $makanan = Makanan::all();
        return view('admin.makanan.index', compact('makanan'));
    }

    public function makananStore(Request $request)
    {
        $request->validate([
            'nama_masakan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'status_masakan' => 'required|in:tersedia,tidak_tersedia'
        ]);

        Makanan::create($request->all());
        return redirect()->route('admin.makanan.index')->with('success', 'Makanan berhasil ditambahkan');
    }

    public function makananUpdate(Request $request, Makanan $makanan)
    {
        $request->validate([
            'nama_masakan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'status_masakan' => 'required|in:tersedia,tidak_tersedia'
        ]);

        $makanan->update($request->all());
        return redirect()->route('admin.makanan.index')->with('success', 'Makanan berhasil diperbarui');
    }

    public function makananDestroy(Makanan $makanan)
    {
        $makanan->delete();
        return redirect()->route('admin.makanan.index')->with('success', 'Makanan berhasil dihapus');
    }

    public function menuIndex()
    {
        $menus = Makanan::orderBy('nama_masakan')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function menuCreate()
    {
        return view('admin.menu.create');
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'nama_masakan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'status_masakan' => 'required|in:tersedia,habis',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Makanan::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    public function menuEdit(Makanan $makanan)
    {
        return view('admin.menu.edit', compact('makanan'));
    }

    public function menuDestroy(Makanan $makanan)
    {
        if ($makanan->gambar) {
            Storage::disk('public')->delete($makanan->gambar);
        }

        $makanan->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }
}
