<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'patient_phone',
        'service_id',
        'appointment_date'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    // Relationship với Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Scope để lọc theo ngày
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }

    // Scope để lọc lịch hẹn hôm nay
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    // Scope để lọc lịch hẹn tuần này
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('appointment_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    // Scope để lọc lịch hẹn tháng này
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year);
    }
}
