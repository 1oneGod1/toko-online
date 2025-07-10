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
    }
}
