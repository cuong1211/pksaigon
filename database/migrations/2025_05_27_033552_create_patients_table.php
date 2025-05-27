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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code')->unique(); // Mã bệnh nhân tự động
            $table->string('full_name'); // Họ tên
            $table->string('phone')->unique(); // Số điện thoại
            $table->text('address')->nullable(); // Địa chỉ
            $table->string('citizen_id')->unique()->nullable(); // Số căn cước công dân
            $table->date('date_of_birth')->nullable(); // Ngày sinh
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Giới tính
            $table->string('email')->nullable(); // Email
            $table->string('emergency_contact')->nullable(); // Người liên hệ khẩn cấp
            $table->string('emergency_phone')->nullable(); // SĐT khẩn cấp
            $table->text('allergies')->nullable(); // Dị ứng
            $table->text('medical_history')->nullable(); // Tiền sử bệnh
            $table->text('notes')->nullable(); // Ghi chú
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps();
            
            // Indexes
            $table->index('phone');
            $table->index('citizen_id');
            $table->index('patient_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};