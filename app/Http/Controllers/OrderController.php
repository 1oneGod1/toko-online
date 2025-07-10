<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Menampilkan riwayat pesanan user
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Menampilkan halaman form checkout
    public function create()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        return view('orders.create', compact('cartItems', 'subtotal'));
    }

    // Memproses checkout dan menyimpan pesanan
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        DB::transaction(function () use ($request, $totalAmount, $cartItems) {
            // 1. Buat pesanan (order)
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
            ]);

            // 2. Pindahkan item dari keranjang ke item pesanan
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // 3. Kosongkan keranjang
            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan Anda berhasil dibuat!');
    }
}
