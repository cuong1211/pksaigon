<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'service_id',
        'appointment_date',
        'appointment_time',
        'symptoms',
        'notes',
        'status',
        'source',
        'confirmed_at',
        'confirmed_by'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'confirmed_at' => 'datetime'
    ];

    // Relationship với Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

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

    // Scope để lọc theo trạng thái
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope cho lịch hẹn đang chờ
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope cho lịch hẹn đã xác nhận
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Scope cho lịch hẹn đã hoàn thành
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope cho lịch hẹn đã hủy
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Accessor để lấy tên trạng thái
    public function getStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->status] ?? 'Không xác định';
    }

    // Accessor để lấy tên nguồn
    public function getSourceNameAttribute()
    {
        $sources = [
            'website' => 'Website',
            'phone' => 'Điện thoại',
            'walk-in' => 'Đến trực tiếp'
        ];
        return $sources[$this->source] ?? 'Không xác định';
    }

    // Accessor để format ngày giờ hẹn
    public function getFormattedAppointmentDateTimeAttribute()
    {
        return $this->appointment_date->format('d/m/Y') . ' - ' . $this->appointment_time;
    }

    // Method để xác nhận lịch hẹn

    
}