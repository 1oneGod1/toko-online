<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Menampilkan daftar semua kupon dengan jumlah penggunaan
     */
    public function index()
    {
        // Ambil semua kupon dengan menghitung berapa kali sudah digunakan (withCount)
        // Latest() untuk mengurutkan dari yang terbaru
        // Paginate(15) untuk membagi menjadi 15 data per halaman
        $coupons = Coupon::withCount('users')->latest()->paginate(15);
        
        // Return view admin dengan data kupon
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Menampilkan form untuk membuat kupon baru
     */
    public function create()
    {
        return view('admin.coupons.form');
    }

    /**
     * Menyimpan kupon baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code', // Kode kupon wajib, max 50 karakter, harus unik
            'type' => 'required|in:percent,nominal',                 // Jenis diskon harus 'percent' atau 'nominal'
            'value' => 'required|integer|min:1',                     // Nilai diskon minimal 1
            'max_uses' => 'required|integer|min:1',                  // Maksimal penggunaan minimal 1
            'expires_at' => 'required|date|after:today',             // Tanggal kadaluarsa harus setelah hari ini
        ]);
        
        // Simpan kupon baru ke database
        Coupon::create($validated);
        
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk kupon tertentu
     */
    public function edit(Coupon $coupon)
    {
        // Return view form dengan data kupon yang akan diedit
        return view('admin.coupons.form', compact('coupon'));
    }

    /**
     * Update kupon yang sudah ada
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Validasi input dengan pengecualian untuk kode kupon saat ini (ignore current id)
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percent,nominal',
            'value' => 'required|integer|min:1',
            'max_uses' => 'required|integer|min:1',
            'expires_at' => 'required|date|after:today',
        ]);
        
        // Update data kupon
        $coupon->update($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil diperbarui.');
    }

    /**
     * Hapus kupon dari database
     */
    public function destroy(Coupon $coupon)
    {
        // Hapus kupon dari database
        $coupon->delete();
        
        // Redirect dengan pesan sukses
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dihapus.');
    }
}
