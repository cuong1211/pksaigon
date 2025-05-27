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
        'unit_price',
        'total_price',
        'invoice_image',
        'import_date',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'integer',
        'total_price' => 'integer', 
        'import_date' => 'date'
    ];

    // Relationship with Medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Accessor để lấy URL đầy đủ của ảnh hóa đơn
    public function getInvoiceImageUrlAttribute()
    {
        if ($this->invoice_image && Storage::disk('public')->exists($this->invoice_image)) {
            return asset('storage/' . $this->invoice_image);
        }
        return null;
    }

    // Accessor để format giá (không có decimal)
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0, '.', '.') . ' VNĐ';
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, '.', '.') . ' VNĐ';
    }

    // Boot method để tự động tính total_price và cập nhật kho
    protected static function boot()
    {
        parent::boot();

        // Tự động tạo mã phiếu nhập khi tạo mới
        static::creating(function ($import) {
            if (empty($import->import_code)) {
                $import->import_code = 'IMP' . date('YmdHis') . rand(100, 999);
            }
            
            // Tính total_price
            $import->total_price = $import->quantity * $import->unit_price;
        });

        // Cập nhật số lượng thuốc trong kho khi tạo phiếu nhập
        static::created(function ($import) {
            $medicine = Medicine::find($import->medicine_id);
            if ($medicine) {
                $medicine->increment('quantity', $import->quantity);
            }
        });

        // Cập nhật khi sửa phiếu nhập
        static::updating(function ($import) {
            // Tính lại total_price
            $import->total_price = $import->quantity * $import->unit_price;
            
            // Nếu thay đổi số lượng, cập nhật lại kho
            if ($import->isDirty('quantity') || $import->isDirty('medicine_id')) {
                $original = $import->getOriginal();
                
                // Trừ số lượng cũ
                if ($original['medicine_id']) {
                    $oldMedicine = Medicine::find($original['medicine_id']);
                    if ($oldMedicine) {
                        $oldMedicine->decrement('quantity', $original['quantity']);
                    }
                }
                
                // Cộng số lượng mới
                $newMedicine = Medicine::find($import->medicine_id);
                if ($newMedicine) {
                    $newMedicine->increment('quantity', $import->quantity);
                }
            }
        });

        // Xóa số lượng khỏi kho khi xóa phiếu nhập
        static::deleting(function ($import) {
            $medicine = Medicine::find($import->medicine_id);
            if ($medicine) {
                $medicine->decrement('quantity', $import->quantity);
            }
        });
    }
}