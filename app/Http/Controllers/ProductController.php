<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ... (Fungsi index tidak berubah)
    public function index(Request $request)
    {
        $query = Product::query()->with('category');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }
        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'harga_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'harga_desc') {
                $query->orderBy('price', 'desc');
            } elseif ($sort === 'nama_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sort === 'nama_desc') {
                $query->orderBy('name', 'desc');
            }
        } else {
            $query->latest();
        }
        $products = $query->paginate(12)->withQueryString();
        return view('products.list', compact('products'));
    }

    public function create()
    {
        return view('products.form', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->except('photo');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('product-photos', 'public');
            $data['image'] = $path;
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // --- PERUBAHAN DI SINI ---
    // Menggunakan Route Model Binding (Product $product)
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // --- PERUBAHAN DI SINI ---
    public function edit(Product $product)
    {
        return view('products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    // --- PERUBAHAN DI SINI ---
    public function update(Request $request, Product $product)
    {
        $data = $request->except('photo');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('photo')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('photo')->store('product-photos', 'public');
            $data['image'] = $path;
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // --- PERUBAHAN DI SINI ---
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
