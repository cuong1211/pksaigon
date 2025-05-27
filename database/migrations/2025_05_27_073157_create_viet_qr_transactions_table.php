<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vietqr_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // ID giao dịch tham chiếu
            $table->string('bank_account'); // Tài khoản ngân hàng
            $table->string('amount'); // Số tiền giao dịch
            $table->enum('trans_type', ['D', 'C']); // D: Debit (trừ tiền), C: Credit (cộng tiền)
            $table->text('content'); // Nội dung giao dịch
            $table->enum('status', ['SUCCESS', 'FAILED', 'PENDING'])->default('SUCCESS'); // Trạng thái xử lý
            $table->timestamp('processed_at')->nullable(); // Thời gian xử lý
            $table->json('raw_data')->nullable(); // Dữ liệu thô từ VietQR
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_id');
            $table->index('bank_account');
            $table->index('trans_type');
            $table->index('status');
            $table->index('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vietqr_transactions');
    }
};