<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'shipping_address',
        'notes'
    ];
    
    /**
     * Mendapatkan pengguna yang memiliki pesanan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Mendapatkan item-item dalam pesanan.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
