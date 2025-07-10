<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- PENTING: Import model Product
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan produk unggulan dari database.
     */
    public function index()
    {
        // Ambil 4 produk secara acak dari database untuk ditampilkan
        $featuredProducts = Product::with('category') // Eager load kategori untuk efisiensi
                                    ->where('is_featured', true)
                                    ->latest()
                                    ->take(4)
                                    ->get();

        return view('welcome', compact('featuredProducts'));
    }
}
