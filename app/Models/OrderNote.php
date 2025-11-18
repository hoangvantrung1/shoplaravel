<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model OrderNote
 * Lưu ghi chú nội bộ và timeline cho đơn hàng
 */
class OrderNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'note',
        'status',
        'is_internal',
        'created_by',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với Admin (người tạo ghi chú)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
