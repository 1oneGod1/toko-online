<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Method untuk mengecek validitas kupon yang digunakan di checkout
     * Dipanggil via AJAX dari halaman checkout
     */
    public function check(Request $request)
    {
        // Validasi input: pastikan coupon_code ada dan berupa string
        $request->validate([
            'coupon_code' => 'required|string',
        ]);
        
        // Ambil user yang sedang login
        $user = auth()->user();
        
        // Cari kupon berdasarkan kode yang diinput user
        $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
        
        // Jika kupon tidak ditemukan, return response error
        if (!$coupon) {
            return response()->json([
                'success' => false, 
                'message' => 'Kupon tidak ditemukan atau tidak valid.'
            ]);
        }
        
        // Cek apakah kupon sudah kadaluarsa
        if (now()->gt($coupon->expires_at)) {
            return response()->json([
                'success' => false, 
                'message' => 'Kupon sudah kadaluarsa.'
            ]);
        }
        
        // Cek apakah user sudah pernah menggunakan kupon ini sebelumnya
        if ($coupon->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false, 
                'message' => 'Kupon hanya bisa digunakan satu kali per user.'
            ]);
        }
        
        // Cek apakah kupon sudah mencapai batas maksimal penggunaan
        $totalUsed = $coupon->users()->count();
        if ($totalUsed >= $coupon->max_uses) {
            return response()->json([
                'success' => false, 
                'message' => 'Kupon sudah habis/batas penggunaan sudah tercapai.'
            ]);
        }
        
        // Jika semua validasi lolos, return data kupon untuk dikalkulasi di frontend
        return response()->json([
            'success' => true,
            'type' => $coupon->type,        // 'percent' atau 'nominal'
            'value' => $coupon->value,      // Nilai diskon
            'message' => 'Kupon valid!'
        ]);
    }    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);

        $code = strtoupper(trim($request->input('code')));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Kupon tidak valid atau tidak ditemukan.');
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            return redirect()->back()->with('error', 'Kupon sudah kadaluarsa.');
        }

        // Ambil total keranjang dari session manual
        $cart = session()->get('cart', []);
        $cartTotal = 0;
        
        foreach ($cart as $details) {
            $cartTotal += $details['price'] * $details['quantity'];
        }
        
        if ($cartTotal <= 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }
        
        // Hitung diskon berdasarkan tipe kupon
        $discount = 0;
        if ($coupon->type == 'percent') {
            $discount = ($cartTotal * floatval($coupon->value)) / 100;
        } else {
            $discount = floatval($coupon->value);
        }
        
        // Pastikan diskon tidak lebih besar dari total belanja
        $discount = min($discount, $cartTotal);
        
        // Simpan informasi kupon ke session dengan struktur yang lengkap
        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => round($discount, 0) // Pastikan ada key 'discount'
        ]);
        
        return redirect()->back()->with('success', "Kupon {$coupon->code} berhasil diterapkan! Diskon Rp " . number_format($discount, 0, ',', '.'));
    }

    public function destroy()
    {
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Kupon berhasil dihapus.');
    }
}
