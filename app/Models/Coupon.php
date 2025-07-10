<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    
    // Field yang bisa diisi mass assignment
    protected $fillable = [
        'code',       // Kode kupon (contoh: DISKON10)
        'type',       // Jenis diskon: 'percent' atau 'nominal'
        'value',      // Nilai diskon (10 untuk 10% atau 50000 untuk Rp 50.000)
        'max_uses',   // Maksimal berapa kali kupon bisa digunakan
        'expires_at'  // Tanggal kadaluarsa kupon
    ];
    
    // Cast kolom expires_at menjadi Carbon date
    protected $dates = ['expires_at'];

    // Relasi many-to-many dengan User (untuk tracking siapa saja yang sudah pakai kupon)
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('used_at')    // Tambahan field kapan kupon digunakan
                    ->withTimestamps();       // Tambahan created_at dan updated_at pada pivot table
    }
}
