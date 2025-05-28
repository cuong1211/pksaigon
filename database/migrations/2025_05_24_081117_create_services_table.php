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
            $table->string('slug')->unique(); // Slug để SEO
            $table->text('description')->nullable(); // Mô tả dịch vụ
            $table->enum('type', ['procedure', 'laboratory', 'other']); // Loại dịch vụ: thủ thuật, xét nghiệm, khác
            $table->decimal('price', 15, 2)->default(0); // Giá dịch vụ
            $table->string('image')->nullable(); // Đường dẫn ảnh
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('type');
            $table->index('is_active');
            $table->index(['is_active', 'type']);
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