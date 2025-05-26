<?php
// database/migrations/xxxx_xx_xx_create_medicines_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên thuốc
            $table->string('code')->unique(); // Mã thuốc
            $table->text('description')->nullable(); // Mô tả
            $table->string('unit'); // Đơn vị (viên, chai, hộp...)
            $table->decimal('price', 10, 2); // Giá
            $table->integer('quantity')->default(0); // Số lượng tồn kho
            $table->integer('min_quantity')->default(0); // Số lượng tối thiểu
            $table->string('manufacturer')->nullable(); // Nhà sản xuất
            $table->date('expiry_date')->nullable(); // Hạn sử dụng
            $table->string('image')->nullable(); // Đường dẫn ảnh
            $table->boolean('is_active')->default(true); // Trạng thái
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};