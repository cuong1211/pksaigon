<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'unit',
        'price',
        'quantity',
        'min_quantity',
        'manufacturer',
        'expiry_date',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Accessor để lấy URL đầy đủ của ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-medicine.png'); // Ảnh mặc định
    }

    // Kiểm tra thuốc sắp hết hạn (trong vòng 30 ngày)
    public function getIsExpiringSoonAttribute()
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->diffInDays(now()) <= 30 && $this->expiry_date->isFuture();
    }

    // Kiểm tra thuốc đã hết hạn
    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->isPast();
    }

    // Kiểm tra thuốc sắp hết
    public function getIsLowStockAttribute()
    {
        return $this->quantity <= $this->min_quantity;
    }

    // Scope để lọc thuốc đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để lọc thuốc sắp hết hạn
    public function scopeExpiringSoon($query)
    {
        return $query->whereDate('expiry_date', '<=', now()->addDays(30))
                    ->whereDate('expiry_date', '>', now());
    }

    // Scope để lọc thuốc đã hết hạn
    public function scopeExpired($query)
    {
        return $query->whereDate('expiry_date', '<', now());
    }

    // Scope để lọc thuốc sắp hết
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= min_quantity');
    }
}