<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Accessor để lấy tên bệnh nhân (cần thiết cho backend list)
    public function getPatientNameAttribute()
    {
        return $this->patient ? $this->patient->full_name : 'N/A';
    }

    // Accessor để lấy số điện thoại bệnh nhân
    public function getPatientPhoneAttribute()
    {
        return $this->patient ? $this->patient->phone : 'N/A';
    }

    // Accessor để lấy tên dịch vụ
    public function getServiceNameAttribute()
    {
        return $this->service ? $this->service->name : 'Không có';
    }

    // Accessor để format ngày giờ hẹn cho hiển thị
    public function getFormattedAppointmentDateAttribute()
    {
        return $this->appointment_date->format('d/m/Y') . ' - ' . $this->appointment_time;
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
    public function confirm($userId = null)
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => $userId
        ]);
    }

    // Method để hủy lịch hẹn
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    // Method để hoàn thành lịch hẹn
    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    // Boot method để tự động tạo mã appointment nếu cần
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            // Có thể thêm logic tự động tạo mã appointment nếu cần
            if (empty($appointment->source)) {
                $appointment->source = 'website';
            }
        });
    }
}