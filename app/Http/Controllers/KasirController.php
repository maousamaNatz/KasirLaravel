<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Makanan;
use App\Models\Transaksi;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

class KasirController extends Controller
{
    public function dashboard()
    {
        $totalTransactions = Order::whereDate('tanggal', today())->count();
        $completedTransactions = Order::whereDate('tanggal', today())
            ->where('status_order', 'selesai')->count();
        $dailyRevenue = Order::whereDate('tanggal', today())
            ->where('status_order', 'selesai')
            ->with(['detailOrders.makanan'])
            ->get()
            ->sum(function($order) {
                return $order->detailOrders->sum(fn($detail) => $detail->makanan->harga * $detail->jumlah);
            });
        $averageTransaction = $completedTransactions > 0
            ? $dailyRevenue / $completedTransactions
            : 0;

        $activeOrders = Order::whereNotIn('status_order', ['selesai', 'dibatalkan'])->count();
        $pendingOrders = Order::where('status_order', 'proses')->count();

        $recentOrders = Order::with('detailOrders.makanan')
            ->whereDate('tanggal', today())
            ->orderBy('tanggal', 'desc')
            ->take(10)
            ->get();

        return view('kasir.dashboard', compact(
            'totalTransactions',
            'completedTransactions',
            'dailyRevenue',
            'averageTransaction',
            'activeOrders',
            'pendingOrders',
            'recentOrders'
        ));
    }

    public function index()
    {
        $orders = Order::with(['user', 'detailOrders.makanan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('kasir.orders.index', compact('orders'));
    }

    public function create()
    {
        $makanans = Makanan::where('status_masakan', 'tersedia')->get();
        return view('kasir.orders.create', compact('makanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'no_meja' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:makanans,id_masakan',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,debit,credit,e-wallet',
            'payment_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255'
        ]);

        // Hitung total_harga
        $total_harga = 0;
        foreach ($request->items as $item) {
            $makanan = Makanan::find($item['id']);
            $total_harga += $makanan->harga * $item['qty'];
        }

        // Buat order baru dengan menambahkan total_harga
        $order = Order::create([
            'no_meja' => $request->no_meja,
            'id_user' => AuthController::userId(), // Gunakan AuthController untuk mendapatkan ID user
            'tanggal' => now(),
            'keterangan' => $request->notes,
            'status_order' => 'pending',
            'total_harga' => $total_harga // Tambahkan total_harga
        ]);

        // Buat detail order
        foreach ($request->items as $item) {
            $makanan = Makanan::find($item['id']);
            DetailOrder::create([
                'id_order' => $order->id_order,
                'id_masakan' => $item['id'],
                'jumlah' => $item['qty'],
                'keterangan' => $request->notes,
                'status_detail_order' => 'pending'
            ]);
        }

        return redirect()->route('kasir.orders.index')
            ->with('success', 'Order berhasil dibuat');
    }

    public function show(Order $order)
    {
        $order->load(['detailOrders.makanan', 'user']);
        return view('kasir.orders.show', compact('order'));
    }

    public function completeOrder(Order $order)
    {
        $total = $order->detailOrders->sum(function($detail) {
            return $detail->makanan->harga * $detail->quantity;
        });

        Transaksi::create([
            'id_user' => Auth::id(),
            'id_order' => $order->id,
            'tanggal' => now(),
            'total_bayar' => $total
        ]);

        $order->update(['status_order' => 'selesai']);

        return redirect()->route('kasir.orders.index')
            ->with('success', 'Transaksi berhasil disimpan');
    }

    public function riwayatOrder()
    {
        $orders = Order::with(['detailOrders.makanan'])
            ->whereIn('status_order', ['selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('kasir.orders.riwayatOrder', compact('orders'));
    }
}
