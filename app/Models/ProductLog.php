<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProductLog
 * Lưu lịch sử thay đổi giá và tồn kho sản phẩm
 */
class ProductLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'field_changed',
        'old_value',
        'new_value',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với Admin (người thay đổi)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }

    /**
     * Lấy nhãn hiển thị cho field_changed
     */
    public function getFieldLabelAttribute()
    {
        $labels = [
            'price' => 'Giá',
            'stock' => 'Tồn kho',
        ];
        return $labels[$this->field_changed] ?? $this->field_changed;
    }
}
