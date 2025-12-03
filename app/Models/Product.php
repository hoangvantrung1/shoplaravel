<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_hot', 'image', 'description', 'brand_id', 'deal_start_date', 'deal_end_date'];
    
    protected $casts = [
        'deal_start_date' => 'datetime',
        'deal_end_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);   
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function inStock()
    {
        return $this->stock > 0;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function wishlists()
{
    return $this->hasMany(Wishlist::class);
}

public function isInWishlist($userId = null)
{
    if (!$userId && auth()->check()) {
        $userId = auth()->id();
    }
    
    return $userId ? $this->wishlists()->where('user_id', $userId)->exists() : false;
}

    /**
     * Quan hệ với ProductLog (lịch sử thay đổi)
     */
    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }
}

