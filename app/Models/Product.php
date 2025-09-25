<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_hot', 'image', 'description', 'brand_id', 'stock'];

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


}

