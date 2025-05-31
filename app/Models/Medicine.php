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
        return asset('images/default-medicine.png');
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

    // Kiểm tra thuốc sắp hết số lượng
    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= 10; // Ngưỡng cảnh báo 10
    }

    // Kiểm tra thuốc có đủ số lượng không
    public function getIsInStockAttribute()
    {
        return $this->current_stock > 0;
    }

    // Tính số lượng tồn kho hiện tại
    public function getCurrentStockAttribute()
    {
        $totalImported = $this->imports()->sum('quantity');
        $totalUsed = $this->usages()->sum('quantity_used');
        return $totalImported - $totalUsed;
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

    // Scope để lọc thuốc có trong kho
    public function scopeInStock($query)
    {
        return $query->whereHas('imports')->whereDoesntHave('usages', function ($q) {
            $q->selectRaw('SUM(quantity_used)')->havingRaw('SUM(quantity_used) >= (SELECT SUM(quantity) FROM medicine_imports WHERE medicine_id = medicines.id)');
        });
    }

    // Scope để lọc thuốc sắp hết (có thể customize logic này)
    public function scopeLowStock($query)
    {
        // Logic phức tạp hơn để tính toán low stock
        return $query->whereHas('imports');
    }

    // Relationship với medicine imports
    public function imports()
    {
        return $this->hasMany(MedicineImport::class);
    }

    // Relationship với medicine usage
    public function usages()
    {
        return $this->hasMany(MedicineUsage::class);
    }

    // Method để kiểm tra có đủ số lượng để sử dụng không
    public function hasEnoughStock($quantity)
    {
        return $this->current_stock >= $quantity;
    }

    // Method để lấy tổng số lượng đã nhập
    public function getTotalImportedQuantityAttribute()
    {
        return $this->imports()->sum('quantity');
    }

    // Method để lấy tổng số lượng đã sử dụng
    public function getTotalUsedQuantityAttribute()
    {
        return $this->usages()->sum('quantity_used');
    }
}
