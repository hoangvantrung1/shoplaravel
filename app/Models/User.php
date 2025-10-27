<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_admin', // thêm is_admin
        'is_active' // thêm is_active
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function wishlists()
{
    return $this->hasMany(Wishlist::class);
}

public function wishlistProducts()
{
    return $this->belongsToMany(Product::class, 'wishlists')
                ->withTimestamps();
}

public function getWishlistCountAttribute()
{
    return $this->wishlists()->count();
}
}
