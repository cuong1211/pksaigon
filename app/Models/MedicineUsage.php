<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineUsage extends Model
{
    use HasFactory;

    protected $table = 'medicine_usage';

    protected $fillable = [
        'examination_id',
        'medicine_id',
        'quantity_used',
        'unit_price',
        'total_price',
        'dosage',
        'usage_note'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // Relationship với Examination
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    // Relationship với Medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Boot method để tự động tính total_price
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($usage) {
            $usage->total_price = $usage->quantity_used * $usage->unit_price;
        });

        static::updating(function ($usage) {
            if ($usage->isDirty(['quantity_used', 'unit_price'])) {
                $usage->total_price = $usage->quantity_used * $usage->unit_price;
            }
        });
    }
}
