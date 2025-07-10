<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        return view('admin.coupons.create');
    }

    /**
     * Menyimpan kupon baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'code' => 'required|string|unique:coupons,code', // Kode kupon wajib, max 50 karakter, harus unik
            'type' => ['required', Rule::in(['fixed', 'percent'])],                 // Jenis diskon harus 'percent' atau 'nominal'
            'value' => 'required|numeric|min:0',                     // Nilai diskon minimal 1
            'expires_at' => 'nullable|date',             // Tanggal kadaluarsa harus setelah hari ini
        ]);
        
        // Simpan kupon baru ke database
        Coupon::create($validatedData);
        
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk kupon tertentu
     */
    public function edit(Coupon $coupon)
    {
        // Return view form dengan data kupon yang akan diedit
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update kupon yang sudah ada
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Validasi input dengan pengecualian untuk kode kupon saat ini (ignore current id)
        $validatedData = $request->validate([
            'code' => ['required', 'string', Rule::unique('coupons')->ignore($coupon->id)],
            'type' => ['required', Rule::in(['fixed', 'percent'])],
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);
        
        // Update data kupon
        $coupon->update($validatedData);
        
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
