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
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Quan hệ với OrderNote (ghi chú và timeline)
     */
    public function notes()
    {
        return $this->hasMany(OrderNote::class)->orderBy('created_at', 'desc');
    }

    /**
     * Lấy ghi chú nội bộ
     */
    public function internalNotes()
    {
        return $this->hasMany(OrderNote::class)->where('is_internal', true)->orderBy('created_at', 'desc');
    }

    public function getStatusLabelAttribute()
    {
        $map = [
            'unpaid' => 'Chưa thanh toán', // THÊM TRẠNG THÁI NÀY
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Giao thành công',
            'shipping' => 'Đang giao',
            'cancelled' => 'Đã hủy',
            'paid' => 'Đã thanh toán',     // THÊM TRẠNG THÁI NÀY
            'failed' => 'Thanh toán thất bại' // THÊM TRẠNG THÁI NÀY
        ];
        return $map[$this->status] ?? $this->status;
    }
    public function getCanCancelAttribute()
    {
        return in_array($this->status, ['pending', 'unpaid']);
    }

    // Thêm accessor để hiển thị màu sắc trạng thái
    public function getStatusColorAttribute()
    {
        $colors = [
            'unpaid' => 'bg-yellow-100 text-yellow-800',
            'pending' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'confirmed' => 'bg-indigo-100 text-indigo-700',
            'completed' => 'bg-green-100 text-green-800',
            'shipping' => 'bg-indigo-100 text-indigo-700',
            'cancelled' => 'bg-red-100 text-red-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800'
        ];
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
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