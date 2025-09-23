<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','type','value','max_discount','min_order_total','usage_limit','usage_count','starts_at','expires_at','is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValidForTotal(float $orderTotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if (!is_null($this->usage_limit) && $this->usage_count >= $this->usage_limit) return false;
        if (!is_null($this->min_order_total) && $orderTotal < (float)$this->min_order_total) return false;
        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        $discount = 0.0;
        if ($this->type === 'percent') {
            $discount = $orderTotal * ((float)$this->value) / 100.0;
        } else {
            $discount = (float)$this->value;
        }
        if (!is_null($this->max_discount)) {
            $discount = min($discount, (float)$this->max_discount);
        }
        return max(0.0, min($discount, $orderTotal));
    }
}



