<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
                           ->orderBy('stock', 'asc')
                           ->paginate(10);
        
        return view('admin.stock.index', compact('products'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);
        
        $product->update(['stock' => $request->stock]);
        
        return back()->with('success', 'Stok produk berhasil diperbarui!');
    }
    
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'stocks' => 'required|array',
            'stocks.*' => 'integer|min:0',
        ]);
        
        foreach ($request->stocks as $id => $stock) {
            Product::where('id', $id)->update(['stock' => $stock]);
        }
        
        return back()->with('success', 'Stok semua produk berhasil diperbarui!');
    }
}