<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use Illuminate\Http\Request;

class KokiController extends Controller
{
    public function dashboard()
    {
        // Statistik pesanan hari ini
        $totalOrders = Order::whereDate('tanggal', today())->count();
        $pendingOrders = DetailOrder::whereHas('order', function($query) {
            $query->whereDate('tanggal', today());
        })->where('status_detail_order', 'pending')->count();
        
        $prosesOrders = DetailOrder::whereHas('order', function($query) {
            $query->whereDate('tanggal', today());
        })->where('status_detail_order', 'diproses')->count();
        
        $completedOrders = DetailOrder::whereHas('order', function($query) {
            $query->whereDate('tanggal', today());
        })->where('status_detail_order', 'selesai')->count();

        // Daftar pesanan yang perlu diproses
        $activeOrders = Order::with(['detailOrders.makanan', 'user'])
            ->whereHas('detailOrders', function($query) {
                $query->whereIn('status_detail_order', ['pending', 'diproses']);
            })
            ->whereDate('tanggal', today())
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('koki.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'prosesOrders',
            'completedOrders',
            'activeOrders'
        ));
    }

    public function orderList()
    {
        $orders = Order::with(['detailOrders.makanan', 'user'])
            ->whereHas('detailOrders', function($query) {
                $query->whereIn('status_detail_order', ['pending', 'diproses']);
            })
            ->orderBy('tanggal', 'asc')
            ->paginate(10);

        return view('koki.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, DetailOrder $detailOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai'
        ]);

        $detailOrder->update([
            'status_detail_order' => $request->status
        ]);

        // Cek apakah semua detail order sudah selesai
        $allCompleted = $detailOrder->order->detailOrders()
            ->where('status_detail_order', '!=', 'selesai')
            ->doesntExist();

        if ($allCompleted) {
            $detailOrder->order->update(['status_order' => 'siap']);
        } elseif ($request->status === 'diproses') {
            $detailOrder->order->update(['status_order' => 'proses']);
        }

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui');
    }
}