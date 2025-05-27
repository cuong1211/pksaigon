<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_code',
        'patient_id',
        'services',
        'medicines',
        'diagnosis',
        'symptoms',
        'treatment_plan',
        'next_appointment',
        'service_fee',
        'medicine_fee',
        'total_fee',
        'payment_status',
        'payment_method',
        'qr_code',
        'qr_content', // FIX: Thêm field để lưu nội dung QR thực tế
        'transaction_id',
        'payment_date',
        'examination_date',
        'notes',
        'status'
    ];

    protected $casts = [
        'services' => 'array',
        'medicines' => 'array',
        'service_fee' => 'integer',
        'medicine_fee' => 'integer',
        'total_fee' => 'integer',
        'examination_date' => 'date',
        'next_appointment' => 'date',
        'payment_date' => 'datetime'
    ];

    // Relationship với patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Accessor để format tiền
    public function getFormattedServiceFeeAttribute()
    {
        return number_format($this->service_fee, 0, '.', '.') . ' VNĐ';
    }

    public function getFormattedMedicineFeeAttribute()
    {
        return number_format($this->medicine_fee, 0, '.', '.') . ' VNĐ';
    }

    public function getFormattedTotalFeeAttribute()
    {
        return number_format($this->total_fee, 0, '.', '.') . ' VNĐ';
    }

    // Accessor để lấy tên trạng thái
    public function getStatusNameAttribute()
    {
        $statuses = [
            'waiting' => 'Chờ khám',
            'examining' => 'Đang khám',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->status] ?? '';
    }

    public function getPaymentStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->payment_status] ?? '';
    }

    // Scope để lọc theo trạng thái
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    // Scope để lọc theo ngày
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('examination_date', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('examination_date', today());
    }

    // Tự động tạo mã phiếu khám
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($examination) {
            if (empty($examination->examination_code)) {
                $examination->examination_code = 'PK' . date('YmdHis') . rand(100, 999);
            }
            
            // Tính tổng tiền
            $examination->total_fee = $examination->service_fee + $examination->medicine_fee;
        });

        static::updating(function ($examination) {
            // Tính lại tổng tiền khi cập nhật
            if ($examination->isDirty(['service_fee', 'medicine_fee'])) {
                $examination->total_fee = $examination->service_fee + $examination->medicine_fee;
            }
        });
    }

    // Method để lấy thông tin dịch vụ đã sử dụng
    public function getServicesDetails()
    {
        if (!$this->services) return collect();
        
        $serviceIds = collect($this->services)->pluck('service_id');
        $services = Service::whereIn('id', $serviceIds)->get();
        
        return collect($this->services)->map(function ($item) use ($services) {
            $service = $services->firstWhere('id', $item['service_id']);
            return [
                'service' => $service,
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
                'total' => ($item['quantity'] ?? 1) * ($item['price'] ?? 0)
            ];
        });
    }

    // Method để lấy thông tin thuốc đã kê
    public function getMedicinesDetails()
    {
        if (!$this->medicines) return collect();
        
        $medicineIds = collect($this->medicines)->pluck('medicine_id');
        $medicines = Medicine::whereIn('id', $medicineIds)->get();
        
        return collect($this->medicines)->map(function ($item) use ($medicines) {
            $medicine = $medicines->firstWhere('id', $item['medicine_id']);
            return [
                'medicine' => $medicine,
                'quantity' => $item['quantity'] ?? 1,
                'dosage' => $item['dosage'] ?? '',
                'note' => $item['note'] ?? '',
                'price' => $item['price'] ?? 0
            ];
        });
    }
}