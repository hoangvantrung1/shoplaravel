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

        // trong app/Models/Post.php
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('images/default-blog.jpg');
        }
        
        // Nếu đã là đường dẫn đầy đủ
        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }
        
        // Đảm bảo đường dẫn bắt đầu bằng 'images/'
        $imagePath = $this->featured_image;
        if (!str_starts_with($imagePath, 'images/')) {
            $imagePath = 'images/' . $imagePath;
        }
        
        return asset($imagePath);
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