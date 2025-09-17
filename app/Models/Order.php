<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total',
        'status',
        'payment_method', 
        'payment_date',   
        'transaction_id'  
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute()
    {
        $map = [
            'unpaid' => 'Chưa thanh toán', // THÊM TRẠNG THÁI NÀY
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
            'paid' => 'Đã thanh toán',     // THÊM TRẠNG THÁI NÀY
            'failed' => 'Thanh toán thất bại' // THÊM TRẠNG THÁI NÀY
        ];
        return $map[$this->status] ?? $this->status;
    }
}