<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'max_discount', 'min_order_total', 
        'usage_limit', 'usage_count', 'starts_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'float',
        'max_discount' => 'float',
        'min_order_total' => 'float',
    ];

    public function isValidForTotal(float $orderTotal): bool
    {
        // Kiểm tra coupon có active không
        if (!$this->is_active) {
            return false;
        }

        // Kiểm tra thời gian hiệu lực
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // Kiểm tra số lần sử dụng
        if (!is_null($this->usage_limit) && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if (!is_null($this->min_order_total) && $orderTotal < $this->min_order_total) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        $discount = 0.0;

        if ($this->type === 'percent') {
            // Giảm giá theo phần trăm
            $discount = $orderTotal * ($this->value / 100);
            
            // Áp dụng giới hạn tối đa nếu có
            if (!is_null($this->max_discount) && $this->max_discount > 0) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            // Giảm giá cố định
            $discount = min($this->value, $orderTotal);
        }

        return max(0, $discount);
    }

    // Tăng số lần sử dụng
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}