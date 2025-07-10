<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- PENTING: Import model Product
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            // Coba ambil data produk unggulan dari database
            $featuredProducts = Product::where('is_featured', true)->take(4)->get();

        } catch (\Exception $e) {
            // JIKA GAGAL: Hentikan semua proses dan tampilkan pesan error yang jelas.
            // Ini akan memaksa error muncul di layar, bahkan di ngrok.
            dd("Terjadi Error Saat Mengambil Data:", $e->getMessage());
        }

        // Jika berhasil, lanjutkan seperti biasa
        return view('welcome', [
            'featuredProducts' => $featuredProducts
        ]);
    }
}
