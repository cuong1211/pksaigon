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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề bài viết
            $table->string('slug')->unique(); // Slug cho SEO
            $table->text('excerpt')->nullable(); // Tóm tắt bài viết
            $table->longText('content'); // Nội dung bài viết
            $table->string('featured_image')->nullable(); // Ảnh đại diện
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Trạng thái
            $table->boolean('is_featured')->default(false); // Bài viết nổi bật
            $table->json('meta_data')->nullable(); // Metadata cho SEO
            $table->unsignedInteger('views_count')->default(0); // Số lượt xem
            $table->timestamp('published_at')->nullable(); // Thời gian xuất bản
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); // Tác giả
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index('is_featured');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};