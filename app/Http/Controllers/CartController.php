<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $quantity = (int) $request->quantity;

        // Check if product has enough stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // If cart is empty then this is the first product
        if (!$cart) {
            $cart = [
                $product->id => [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->getFinalPriceAttribute(),
                    "image" => $product->image
                ]
            ];
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }

        // If cart not empty then check if this product exist then increment quantity
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok produk tidak mencukupi untuk jumlah yang diminta.');
            }
            
            $cart[$product->id]['quantity'] = $newQuantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Jumlah produk di keranjang berhasil diperbarui!');
        }

        // If item not exist in cart then add to cart with quantity
        $cart[$product->id] = [
            "name" => $product->name,
            "quantity" => $quantity,
            "price" => $product->getFinalPriceAttribute(),
            "image" => $product->image
        ];
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update product quantity in the cart.
     */
    public function update(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        foreach ($request->quantities as $id => $quantity) {
            if (isset($cart[$id])) {
                $product = Product::find($id);
                if ($product->stock < $quantity) {
                    return back()->with('error', "Stok untuk produk '{$product->name}' tidak mencukupi.");
                }
                $cart[$id]['quantity'] = $quantity;
            }
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
