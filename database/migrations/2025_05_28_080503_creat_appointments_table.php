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
        Schema::dropIfExists('appointments');

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name'); // Tên bệnh nhân
            $table->string('patient_phone'); // SĐT bệnh nhân
            $table->enum('patient_gender', ['male', 'female', 'other'])->default('other'); // Giới tính
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null'); // Dịch vụ
            $table->datetime('appointment_date'); // Ngày giờ hẹn
            $table->timestamps();

            // Indexes
            $table->index(['appointment_date', 'status']);
            $table->index('patient_phone');
            $table->index('status');
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
