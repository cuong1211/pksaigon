<?php
// database/migrations/xxxx_xx_xx_create_appointments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); // Liên kết với bệnh nhân
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null'); // Dịch vụ
            $table->date('appointment_date'); // Ngày hẹn
            $table->string('appointment_time'); // Giờ hẹn (08:00, 09:30, etc.)
            $table->text('symptoms')->nullable(); // Triệu chứng
            $table->text('notes')->nullable(); // Ghi chú
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending'); // Trạng thái
            $table->enum('source', ['website', 'phone', 'walk-in'])->default('website'); // Nguồn đặt lịch
            $table->timestamps();
            
            // Indexes
            $table->index('patient_id');
            $table->index('service_id');  
            $table->index('appointment_date');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};