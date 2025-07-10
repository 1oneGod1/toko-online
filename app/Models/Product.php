<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'description', 
        'price', 
        'image',
        'stock',
        'discount_price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function discussions()
    {
        return $this->hasMany(Discussion::class)->whereNull('parent_id')->latest();
    }
    
    public function getFinalPriceAttribute()
    {
        return $this->discount_price > 0 ? $this->discount_price : $this->price;
    }
    
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
    
    public function getRatingCountAttribute()
    {
        return $this->reviews()->count();
    }
    
    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price > 0 && $this->price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }
}