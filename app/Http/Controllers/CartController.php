<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = collect();
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $item = (object) [
                    'id' => $id,
                    'name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'image' => $details['image'] ?? $product->image,
                    'product' => $product
                ];
                $cartItems->push($item);
                $total += $details['price'] * $details['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang (untuk route cart.add)
     */
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        
        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok produk tidak mencukupi untuk jumlah yang diminta.');
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menambahkan produk ke keranjang (untuk route cart.store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        
        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok produk tidak mencukupi untuk jumlah yang diminta.');
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity produk di keranjang
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->id;
        $quantity = $request->quantity;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $product->stock >= $quantity) {
                $cart[$productId]['quantity'] = $quantity;
                session()->put('cart', $cart);
                return back()->with('success', 'Keranjang berhasil diperbarui.');
            } else {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    /**
     * Hapus produk dari keranjang
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    /**
     * Kosongkan keranjang
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
