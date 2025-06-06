<?php

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'is_featured',
        'meta_data',
        'views_count',
        'published_at',
        'author_id'
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean', // true = hiện, false = ẩn
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship với User (tác giả)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scope cho bài viết hiện
    public function scopeVisible($query)
    {
        return $query->where('status', true);
    }

    // Scope cho bài viết ẩn
    public function scopeHidden($query)
    {
        return $query->where('status', false);
    }

    // Scope cho bài viết nổi bật
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Tự động tạo slug khi tạo/cập nhật bài viết
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
            
            if (empty($post->author_id)) {
                $post->author_id = Auth::check() ? Auth::id() : null;
            }

            if ($post->status && empty($post->published_at)) {
                $post->published_at = now();
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && !$post->isDirty('slug')) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
            
            if ($post->isDirty('status') && $post->status && empty($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    // Tạo slug unique
    public static function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    // Accessor cho trạng thái (tiếng Việt)
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Hiện' : 'Ẩn';
    }

    // FIX: Accessor cho đường dẫn ảnh đại diện
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image && Storage::disk('public')->exists($this->featured_image)) {
            return url('storage/' . $this->featured_image);
        }

        return url('images/default-post.jpg');
    }

    // Method để tăng lượt xem
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    // Method để lấy URL của bài viết
    public function getUrlAttribute()
    {
        return route('posts.show', $this->slug);
    }

    // Accessor để tạo excerpt từ content
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }
}