<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    public $timestamps = true;
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
        'transaction_id',
        'created_at',
        'updated_at',
        'user_id',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Tự động set thời gian theo timezone Việt Nam
            if (empty($model->created_at)) {
                $model->created_at = now('Asia/Ho_Chi_Minh');
            }
            if (empty($model->updated_at)) {
                $model->updated_at = now('Asia/Ho_Chi_Minh');
            }

            // Đảm bảo payment_date cũng được set đúng timezone
            if (!empty($model->payment_date)) {
                $model->payment_date = now('Asia/Ho_Chi_Minh');
            }
        });

        static::updating(function ($model) {
            $model->updated_at = now('Asia/Ho_Chi_Minh');
        });
    }
    public function setPaymentDateAttribute($value)
    {
        $this->attributes['payment_date'] = $value ?
            \Carbon\Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh') :
            now('Asia/Ho_Chi_Minh');
    }
}