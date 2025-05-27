<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_code',
        'full_name',
        'phone',
        'address',
        'citizen_id',
        'date_of_birth',
        'gender',
        'email',
        'emergency_contact',
        'emergency_phone',
        'allergies',
        'medical_history',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationship với examinations
    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    // Accessor để lấy tuổi
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) return null;
        return $this->date_of_birth->diffInYears(now());
    }

    // Accessor để lấy tên giới tính
    public function getGenderNameAttribute()
    {
        $genders = [
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác'
        ];
        return $genders[$this->gender] ?? '';
    }

    // Scope để lọc bệnh nhân đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để tìm theo số điện thoại
    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    // Scope để tìm theo căn cước
    public function scopeByCitizenId($query, $citizenId)
    {
        return $query->where('citizen_id', $citizenId);
    }

    // Tự động tạo mã bệnh nhân
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->patient_code)) {
                $patient->patient_code = 'BN' . date('Y') . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Method để lấy lịch sử khám gần nhất
    public function getLatestExaminations($limit = 5)
    {
        return $this->examinations()
                    ->with(['services', 'medicines'])
                    ->orderBy('examination_date', 'desc')
                    ->limit($limit)
                    ->get();
    }

    // Method để đếm tổng số lần khám
    public function getTotalExaminationsAttribute()
    {
        return $this->examinations()->count();
    }

    // Method để lấy lần khám gần nhất
    public function getLastExaminationAttribute()
    {
        return $this->examinations()
                    ->orderBy('examination_date', 'desc')
                    ->first();
    }
}