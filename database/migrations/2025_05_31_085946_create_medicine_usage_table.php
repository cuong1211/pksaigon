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
        Schema::create('medicine_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained('examinations')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->integer('quantity_used'); // Số lượng đã sử dụng
            $table->decimal('unit_price', 10, 2); // Giá bán tại thời điểm sử dụng
            $table->decimal('total_price', 10, 2); // Tổng tiền = quantity_used * unit_price
            $table->string('dosage')->nullable(); // Liều lượng
            $table->text('usage_note')->nullable(); // Ghi chú cách dùng
            $table->timestamps();

            // Indexes
            $table->index('examination_id');
            $table->index('medicine_id');
            $table->index(['medicine_id', 'created_at']); // Để query usage history
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_usage');
    }
};
