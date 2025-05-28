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
        'type',
        'description',
        'import_price',
        'sale_price',
        'expiry_date',
        'image',
        'is_active'
    ];

    protected $casts = [
        'import_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
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

    // Accessor để lấy tên loại thuốc
    public function getTypeNameAttribute()
    {
        $types = [
            'supplement' => 'Thực phẩm chức năng',
            'medicine' => 'Thuốc điều trị',
            'other' => 'Khác'
        ];
        return $types[$this->type] ?? $this->type;
    }

    // Accessor để format giá
    public function getFormattedImportPriceAttribute()
    {
        return number_format($this->import_price, 0, '.', '.') . ' VNĐ';
    }

    public function getFormattedSalePriceAttribute()
    {
        return number_format($this->sale_price, 0, '.', '.') . ' VNĐ';
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

    // Scope để lọc thuốc đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để lọc theo loại
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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

    // Relationship với medicine imports
    public function imports()
    {
        return $this->hasMany(MedicineImport::class);
    }

    // Method để lấy tổng số lượng đã nhập
    public function getTotalImportedQuantityAttribute()
    {
        return $this->imports()->sum('quantity');
    }
}