<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','slug','price','sale_price','is_hot','image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

