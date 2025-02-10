<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Makanan;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalMenus = Makanan::count();
        $totalOrders = Order::count();
        $completedOrders = Order::where('status_order', 'selesai')->count();
        $newUsersLastMonth = User::whereDate('created_at', '>=', now()->subDays(30))->count();
        $newMenusThisWeek = Makanan::whereDate('created_at', '>=', now()->subDays(7))->count();

        // Perbaikan perhitungan total revenue
        $totalRevenue = Order::with(['detailOrders.makanan'])
            ->where('status_order', 'selesai')
            ->get()
            ->sum(function($order) {
                return $order->detailOrders->sum(fn($detail) => $detail->makanan->harga);
            });

        // Perbaikan perhitungan rata-rata harian
        $avgDailyRevenue = $totalRevenue / max(Order::where('status_order', 'selesai')
            ->distinct('tanggal')
            ->count('tanggal'), 1);

        // Data untuk grafik penjualan 6 bulan ter  akhir
        $salesData = $this->getSalesData();

        // Data untuk grafik kategori menu
        $categoryData = $this->getCategoryData();

        // Data 10 menu terlaris
        $topMenus = $this->getTopMenus();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalMenus',
            'totalOrders',
            'completedOrders',
            'totalRevenue',
            'avgDailyRevenue',
            'newUsersLastMonth',
            'newMenusThisWeek',
            'salesData',
            'categoryData',
            'topMenus'
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
    public function userIndex()
    {
        $users = User::with('level')->get();
        return view('admin.users.index', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'id_level' => 'required|exists:levels,id_level'
        ]);

        User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_level' => $request->id_level
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,'.$user->id_user.',id_user',
            'id_level' => 'required|exists:levels,id_level'
        ]);

        $user->update($request->all());
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function userDestroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
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
}
