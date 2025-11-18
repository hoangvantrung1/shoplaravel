<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'user_id',
        'view_count',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Lấy URL ảnh đại diện của bài viết
     * Xử lý nhiều trường hợp: null, đường dẫn đầy đủ, đường dẫn tương đối
     */
    public function getFeaturedImageUrlAttribute()
    {
        // Nếu không có ảnh, trả về placeholder
        if (empty($this->featured_image)) {
            // Sử dụng placeholder từ placeholder.com hoặc data URI
            return 'https://via.placeholder.com/800x450/9ca3af/ffffff?text=No+Image';
        }
        
        $imagePath = trim($this->featured_image);
        
        // Nếu đã là URL đầy đủ (http/https), trả về trực tiếp
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }
        
        // Loại bỏ dấu / ở đầu nếu có (ví dụ: /images/xxx.jpg -> images/xxx.jpg)
        $imagePath = ltrim($imagePath, '/');
        
        // Chuẩn hóa đường dẫn: đảm bảo bắt đầu bằng 'images/'
        if (!str_starts_with($imagePath, 'images/')) {
            $imagePath = 'images/' . $imagePath;
        }
        
        // Kiểm tra file có tồn tại không
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        // Nếu không tìm thấy file, trả về placeholder
        return 'https://via.placeholder.com/800x450/9ca3af/ffffff?text=Image+Not+Found';
    }

    public function featuredImageExists()
    {
        if (!$this->featured_image) {
            return false;
        }
        
        // Đảm bảo đường dẫn đúng
        $imagePath = $this->featured_image;
        if (!str_starts_with($imagePath, 'images/')) {
            $imagePath = 'images/' . $imagePath;
        }
        
        return file_exists(public_path($imagePath));
    }
}