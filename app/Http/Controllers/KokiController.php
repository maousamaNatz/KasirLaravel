<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use Illuminate\Http\Request;

class KokiController extends Controller
{
    public function dashboard()
    {
        return view('koki.dashboard');
    }

    public function orderList()
    {
        $orders = DetailOrder::with(['order', 'makanan'])
            ->where('status_detail_order', 'pending')
            ->get();
        return view('koki.orders.index', compact('orders'));
    }

    public function updateStatus(DetailOrder $detailOrder, Request $request)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai'
        ]);

        $detailOrder->update([
            'status_detail_order' => $request->status
        ]);

        // Cek apakah semua detail order sudah selesai
        $allComplete = $detailOrder->order->detailOrders()
            ->where('status_detail_order', '!=', 'selesai')
            ->count() === 0;

        if ($allComplete) {
            $detailOrder->order->update(['status_order' => 'siap']);
        }

        return redirect()->route('koki.orders')->with('success', 'Status pesanan berhasil diperbarui');
    }
}