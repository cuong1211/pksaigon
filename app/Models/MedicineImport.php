<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MedicineImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_code',
        'medicine_id',
        'quantity',
        'total_amount',
        'invoice_image',
        'import_date',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'import_date' => 'date'
    ];

    // Relationship with Medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Accessor để format tổng tiền
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 0, '.', '.') . ' VNĐ';
    }
    public function getInvoiceImageUrlAttribute()
    {
        if ($this->invoice_image && Storage::disk('public')->exists($this->invoice_image)) {
            return asset('storage/' . $this->invoice_image);
        }
        return null;
    }

    // Boot method để tự động tạo mã phiếu nhập
    protected static function boot()
    {
        parent::boot();

        // Tự động tạo mã phiếu nhập khi tạo mới
        static::creating(function ($import) {
            if (empty($import->import_code)) {
                $import->import_code = 'IMP' . date('YmdHis') . rand(100, 999);
            }
        });
    }
}