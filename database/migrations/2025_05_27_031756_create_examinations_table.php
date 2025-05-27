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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->string('examination_code')->unique(); // Mã phiếu khám
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); // Bệnh nhân
            $table->json('services')->nullable(); // Dịch vụ sử dụng (JSON: [{service_id, quantity, price}])
            $table->json('medicines')->nullable(); // Thuốc kê theo (JSON: [{medicine_id, quantity, dosage, note}])
            $table->text('diagnosis')->nullable(); // Chuẩn đoán
            $table->text('symptoms')->nullable(); // Triệu chứng
            $table->text('treatment_plan')->nullable(); // Kế hoạch điều trị
            $table->date('next_appointment')->nullable(); // Lịch tái khám
            $table->bigInteger('service_fee')->default(0); // Tiền dịch vụ
            $table->bigInteger('medicine_fee')->default(0); // Tiền thuốc
            $table->bigInteger('total_fee'); // Tổng tiền
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending'); // Trạng thái thanh toán
            $table->string('payment_method')->nullable(); // Phương thức thanh toán
            $table->string('qr_code')->nullable(); // Mã QR thanh toán
            $table->string('transaction_id')->nullable(); // Mã giao dịch
            $table->timestamp('payment_date')->nullable(); // Ngày thanh toán
            $table->date('examination_date'); // Ngày khám
            $table->text('notes')->nullable(); // Ghi chú
            $table->enum('status', ['waiting', 'examining', 'completed', 'cancelled'])->default('waiting'); // Trạng thái khám
            $table->timestamps();
            
            // Indexes
            $table->index('patient_id');
            $table->index('examination_date');
            $table->index('payment_status');
            $table->index('status');
            $table->index('examination_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};