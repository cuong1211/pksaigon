<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
        'is_featured',
        'meta_data',
        'views_count',
        'published_at',
        'author_id'
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship với User (tác giả)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scope cho bài viết đã xuất bản
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    // Scope cho bài viết nổi bật
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope cho bài viết draft
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Tự động tạo slug khi tạo bài viết
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);

                // Đảm bảo slug là unique
                $originalSlug = $post->slug;
                $counter = 1;

                while (static::where('slug', $post->slug)->exists()) {
                    $post->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
            if (empty($post->author_id)) {
                $post->author_id = Auth::check() ? Auth::id() : null;
            }

            if ($post->status === 'published' && empty($post->published_at)) {
                $post->published_at = now();
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('status') && $post->status === 'published' && empty($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    // Accessor cho excerpt
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Nếu không có excerpt, tạo từ content
        return Str::limit(strip_tags($this->content), 150);
    }

    // Accessor cho trạng thái (tiếng Việt)
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'Nháp',
            'published' => 'Đã xuất bản',
            'archived' => 'Lưu trữ'
        ];

        return $statuses[$this->status] ?? 'Không xác định';
    }

    // Accessor cho đường dẫn ảnh đại diện
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return asset('images/default-post.jpg'); // Ảnh mặc định
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
}
