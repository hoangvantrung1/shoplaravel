<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','user_id','rating','comment','is_approved','images','video'];

    /**
     * Cast các trường dữ liệu
     */
    protected $casts = [
        'images' => 'array', // Tự động chuyển JSON thành array
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Quan hệ với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Lấy danh sách ảnh đầy đủ URL
     */
    public function getImagesUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        
        return array_map(function ($image) {
            if (!$image) {
                return 'https://via.placeholder.com/600x600/eeeeee/777777?text=No+Image';
            }

            if (Str::startsWith($image, ['http://', 'https://'])) {
                return $image;
            }

            $normalized = str_replace('\\', '/', ltrim($image, '/'));

            // Nếu path đã nằm trong public/storage (ví dụ: storage/reviews/...)
            if (Str::startsWith($normalized, 'storage/')) {
                return asset($normalized);
            }

            // Nếu file tồn tại trực tiếp trong public
            if (file_exists(public_path($normalized))) {
                return asset($normalized);
            }

            // Mặc định đọc từ disk public (storage/app/public)
            return Storage::disk('public')->exists($normalized)
                ? asset('storage/' . $normalized)
                : 'https://via.placeholder.com/600x600/eeeeee/777777?text=No+Image';
        }, $this->images);
    }

    /**
     * Accessor: Lấy URL video đầy đủ
     */
    public function getVideoUrlAttribute()
    {
        if (!$this->video) {
            return null;
        }

        $path = str_replace('\\', '/', ltrim($this->video, '/'));

        if (Str::startsWith($this->video, ['http://', 'https://'])) {
            return $this->video;
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return Storage::disk('public')->exists($path)
            ? asset('storage/' . $path)
            : null;
    }
}



