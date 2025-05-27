<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VietQRTransaction extends Model
{
    use HasFactory;

    protected $table = 'viet_qr_transactions';

    protected $fillable = [
        'ref_transaction_id',
        'bank_account',
        'amount',
        'transaction_type',
        'content',
        'status',
        'raw_data',
        'processed_at',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_data' => 'array',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot method để tự động tạo ref_transaction_id
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->ref_transaction_id)) {
                $transaction->ref_transaction_id = 'TXN' . date('YmdHis') . Str::random(6);
            }
        });
    }

    /**
     * Accessor để format số tiền
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, '.', '.') . ' VNĐ';
    }

    /**
     * Accessor để lấy tên loại giao dịch
     */
    public function getTransactionTypeNameAttribute()
    {
        return $this->transaction_type === 'D' ? 'Trừ tiền (Debit)' : 'Cộng tiền (Credit)';
    }

    /**
     * Accessor để lấy tên trạng thái
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'failed' => 'Thất bại'
        ];

        return $statuses[$this->status] ?? 'Không xác định';
    }

    /**
     * Scope để lọc giao dịch theo loại
     */
    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope để lọc giao dịch hoàn thành
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope để lọc giao dịch theo tài khoản ngân hàng
     */
    public function scopeByBankAccount($query, $bankAccount)
    {
        return $query->where('bank_account', $bankAccount);
    }

    /**
     * Scope để lọc theo khoảng thời gian
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('processed_at', [$startDate, $endDate]);
    }

    /**
     * Method để đánh dấu giao dịch hoàn thành
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);
    }

    /**
     * Method để đánh dấu giao dịch thất bại
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $reason,
            'processed_at' => now()
        ]);
    }
}