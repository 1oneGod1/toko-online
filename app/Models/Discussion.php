<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'product_id', 'parent_id', 'message'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(Discussion::class, 'parent_id');
    }
    
    public function replies()
    {
        return $this->hasMany(Discussion::class, 'parent_id');
    }
    
    public function isParent()
    {
        return is_null($this->parent_id);
    }
}