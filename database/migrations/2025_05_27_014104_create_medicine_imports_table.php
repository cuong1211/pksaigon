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
        Schema::create('medicine_imports', function (Blueprint $table) {
            $table->id();
            $table->string('import_code')->unique(); // Mã phiếu nhập
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade'); // Liên kết với thuốc
            $table->integer('quantity'); // Số lượng nhập
            $table->bigInteger('unit_price'); // Giá nhập đơn vị (lưu dạng integer, VD: 50000 thay vì 50000.00)
            $table->bigInteger('total_price'); // Tổng tiền (quantity * unit_price)
            $table->string('invoice_image')->nullable(); // Ảnh hóa đơn
            $table->date('import_date'); // Ngày nhập
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
            
            // Indexes
            $table->index('medicine_id');
            $table->index('import_date');
            $table->index('import_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_imports');
    }
};