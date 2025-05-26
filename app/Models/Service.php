<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'duration',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Accessor để lấy URL đầy đủ của ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        return asset('images/default-service.png'); // Ảnh mặc định
    }

    // Accessor để format thời gian dịch vụ
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return null;
        
        $hours = intval($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}p";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}p";
        }
    }

    // Accessor để lấy tên loại dịch vụ
    public function getTypeNameAttribute()
    {
        $typeLabels = [
            'consultation' => 'Tư vấn',
            'treatment' => 'Điều trị',
            'examination' => 'Khám bệnh',
            'surgery' => 'Phẫu thuật'
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
}