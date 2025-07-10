<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- PENTING: Import model Product
use Illuminate\Http\Request;
use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan produk unggulan dari database.
     */
    public function index()
    {
        // Ambil pengaturan halaman utama
        $settings = Setting::pluck('value', 'key');
        
        // Cek apakah tabel products ada, jika tidak, tampilkan halaman tanpa query produk
        try {
            // Ambil 4 produk secara acak dari database untuk ditampilkan
            $featuredProducts = Product::with('category') // Eager load kategori untuk efisiensi
                                        ->inRandomOrder()
                                        ->limit(4)
                                        ->get();

        return view('home', compact('featuredProducts', 'settings'));
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan halaman tanpa produk
            return view('home', ['featuredProducts' => collect(), 'settings' => $settings]);
        }
    }
}
