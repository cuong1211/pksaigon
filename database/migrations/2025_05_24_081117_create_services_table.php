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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên dịch vụ
            $table->string('slug')->unique(); // Slug cho SEO
            $table->text('description')->nullable(); // Mô tả ngắn dịch vụ
            $table->longText('content')->nullable(); // Nội dung chi tiết dịch vụ
            $table->decimal('price', 15, 2)->default(0); // Giá dịch vụ
            $table->string('duration')->nullable(); // Thời gian thực hiện (VD: 30 phút, 1 giờ)
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->string('image')->nullable(); // Hình ảnh dịch vụ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};