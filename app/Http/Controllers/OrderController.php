<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $cartItems = collect();
        $subtotal = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $item = (object) [
                    'id' => $id,
                    'name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'image' => $details['image'] ?? $product->image,
                    'attributes' => (object) ['image' => $details['image'] ?? $product->image]
                ];
                $cartItems->push($item);
                $subtotal += $details['price'] * $details['quantity'];
            }
        }

        $discount = 0;
        $total = $subtotal;

        // Terapkan diskon jika ada kupon
        if (session()->has('coupon')) {
            $couponData = session('coupon');
            $discount = isset($couponData['discount']) ? $couponData['discount'] : 0;
            $total = $subtotal - $discount;
        }

        // Ambil daftar rekening bank yang aktif
        $bankAccounts = BankAccount::active()->ordered()->get();

        return view('orders.create', compact('cartItems', 'subtotal', 'discount', 'total', 'bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,bank_transfer',
            'bank_account_id' => 'required_if:payment_method,bank_transfer|exists:bank_accounts,id'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Hitung total
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $discount = 0;
        if (session()->has('coupon')) {
            $couponData = session('coupon');
            $discount = isset($couponData['discount']) ? $couponData['discount'] : 0;
        }

        $total = $subtotal - $discount;

        // Buat order
        $orderData = [
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . time() . '-' . auth()->id(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'bank_account_id' => $request->payment_method === 'bank_transfer' ? $request->bank_account_id : null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'status' => 'pending',
            'coupon_code' => session()->has('coupon') ? session('coupon')['code'] : null
        ];

        // Tambahkan shipping_address jika field ada
        if (Schema::hasColumn('orders', 'shipping_address')) {
            $orderData['shipping_address'] = $request->address; // Gunakan address yang sama atau bisa dipisah
        }

        // Cek field mana yang ada di database dan gunakan yang sesuai
        if (Schema::hasColumn('orders', 'total')) {
            $orderData['total'] = $total;
        }
        if (Schema::hasColumn('orders', 'total_amount')) {
            $orderData['total_amount'] = $total;
        }

        $order = Order::create($orderData);

        // Buat order items
        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price']
            ]);
        }

        // Bersihkan cart dan coupon
        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Pastikan user hanya bisa melihat ordernya sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product', 'bankAccount');
        
        return view('orders.show', compact('order'));
    }
}
