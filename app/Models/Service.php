<?php

// app/Models/Service.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'price',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // FIX: Cập nhật accessor để lấy URL đầy đủ của ảnh
    // Accessor để lấy URL đầy đủ của ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            $url = app()->environment('production')
                ? url('public/storage/' . $this->image)
                : url('storage/' . $this->image);
            return $url;
        }

        $defaultUrl = app()->environment('production')
            ? url('public/images/default-service.png')
            : url('images/default-service.png');
        return $defaultUrl;
    }

    // Accessor để lấy tên loại dịch vụ
    public function getTypeNameAttribute()
    {
        $typeLabels = [
            'procedure' => 'Thủ thuật',
            'laboratory' => 'Xét nghiệm',
            'other' => 'Khác'
        ];

        return $typeLabels[$this->type] ?? $this->type;
    }

    // Accessor để lấy giá format
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, '.', '.') . ' VNĐ';
    }

    // Scope để lọc dịch vụ đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để lọc theo loại dịch vụ
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope để lọc theo khoảng giá
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    // Scope để tìm theo slug
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Boot method để tự động tạo slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug) && !empty($service->name)) {
                $service->slug = static::generateUniqueSlug($service->name);
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name') && !$service->isDirty('slug')) {
                $service->slug = static::generateUniqueSlug($service->name, $service->id);
            }
        });
    }

    // Tạo slug unique
    public static function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
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

    // Method để lấy URL của service
    public function getUrlAttribute()
    {
        return route('service.show', $this->slug);
    }
}
