<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index()
    {
        // Mengambil semua pesanan dengan data pengguna dan item pesanan terkait
        $orders = Order::with(['user', 'orderItems.product'])->latest()->paginate(10);
                       
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        
        $order->status = $validated['status'];
        $order->save();
        
        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
