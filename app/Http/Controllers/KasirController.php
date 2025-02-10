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
            'no_meja' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:makanans,id_masakan',
            'items.*.qty' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ]);

        // Hitung total_harga
        $total_harga = 0;
        foreach ($request->items as $item) {
            $makanan = Makanan::find($item['id']);
            $total_harga += $makanan->harga * $item['qty'];
        }

        // Buat order baru
        $order = Order::create([
            'no_meja' => $request->no_meja,
            'id_user' => AuthController::userId(),
            'tanggal' => now(),
            'keterangan' => $request->notes,
            'status_order' => 'pending',
            'total_harga' => $total_harga
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

        return redirect()->route('kasir.orders.invoice', $order->id_order)
            ->with('success', 'Order berhasil dibuat');
    }

    public function show(Order $order)
    {
        $order->load(['detailOrders.makanan', 'user']);
        return view('kasir.orders.show', compact('order'));
    }

    public function completeOrder(Order $order)
    {
        // Validasi apakah order milik kasir yang login
        if ($order->id_user !== AuthController::userId()) {
            return redirect()->route('kasir.orders.index')
                ->with('error', 'Anda tidak memiliki akses untuk menyelesaikan order ini');
        }

        // Update status order
        $order->update(['status_order' => 'selesai']);

        // Buat transaksi
        Transaksi::create([
            'id_user' => AuthController::userId(),
            'id_order' => $order->id_order,
            'tanggal' => now(),
            'total_bayar' => $order->total_harga
        ]);

        return redirect()->route('kasir.orders.invoice', $order->id_order)
            ->with('success', 'Pesanan berhasil diselesaikan');
    }

    public function riwayatOrder()
    {
        $orders = Order::with(['detailOrders.makanan'])
            ->whereIn('status_order', ['selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('kasir.orders.riwayatOrder', compact('orders'));
    }

    public function invoice($id_order)
    {
        $order = Order::with(['detailOrders.makanan', 'user'])
            ->findOrFail($id_order);

        // Jika order bukan milik kasir yang sedang login
        if ($order->id_user !== AuthController::userId()) {
            return redirect()->route('kasir.orders.index')
                ->with('error', 'Anda tidak memiliki akses ke invoice ini');
        }

        return view('kasir.orders.invoice', compact('order'));
    }

    public function riwayatTransaksi(Request $request)
    {
        $query = Transaksi::with(['order.detailOrders.makanan', 'user'])
            ->orderBy('tanggal', 'desc');

        // Filter berdasarkan tanggal jika ada
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $transaksis = $query->paginate(10);

        // Hitung total pendapatan hari ini
        $totalHariIni = Transaksi::whereDate('tanggal', today())
            ->sum('total_bayar');

        return view('kasir.transaksi.riwayat', compact('transaksis', 'totalHariIni'));
    }

    public function prosesPembayaran(Request $request, Order $order)
    {
        $request->validate([
            'uang_bayar' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999999.99' // Sesuai dengan decimal(15,2)
            ],
            'metode_pembayaran' => 'required|in:tunai,debit,kredit,qris'
        ]);

        try {
            $uang_bayar = $request->uang_bayar;
            $total_harga = $order->total_harga;
            $uang_kembali = $uang_bayar - $total_harga;

            // Tentukan status pembayaran
            $status_pembayaran = 'lunas';
            if ($uang_bayar < $total_harga) {
                $status_pembayaran = 'kurang';
                $uang_kembali = 0;
            }

            // Update order
            $order->update([
                'uang_bayar' => $uang_bayar,
                'uang_kembali' => max(0, $uang_kembali),
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $status_pembayaran
            ]);

            // Jika pembayaran lunas, buat transaksi
            if ($status_pembayaran === 'lunas') {
                $order->update(['status_order' => 'selesai']);
                
                Transaksi::create([
                    'id_user' => AuthController::userId(),
                    'id_order' => $order->id_order,
                    'tanggal' => now(),
                    'total_bayar' => $total_harga
                ]);
            }

            return redirect()->route('kasir.orders.invoice', $order->id_order)
                ->with('success', 'Pembayaran berhasil diproses');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Pastikan jumlah uang valid.')
                ->withInput();
        }
    }

    public function showPembayaran(Order $order)
    {
        // Validasi apakah order milik kasir yang login
        if ($order->id_user !== AuthController::userId()) {
            return redirect()->route('kasir.orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pembayaran ini');
        }

        // Validasi status pembayaran
        if ($order->status_pembayaran === 'lunas') {
            return redirect()->route('kasir.orders.invoice', $order->id_order)
                ->with('error', 'Pesanan ini sudah lunas');
        }

        return view('kasir.orders.pembayaran', compact('order'));
    }
}
