<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tạo bảng posts mới với cấu trúc mong muốn
        Schema::create('posts_new', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->boolean('status')->default(false); // false = ẩn, true = hiện
            $table->boolean('is_featured')->default(false);
            $table->json('meta_data')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['status', 'published_at']);
            $table->index('is_featured');
            $table->index('author_id');
        });

        // Copy dữ liệu từ bảng cũ sang bảng mới
        if (Schema::hasTable('posts')) {
            $posts = DB::table('posts')->get();
            
            foreach ($posts as $post) {
                // Tạo slug từ title nếu chưa có
                $slug = $this->generateSlug($post->title);
                
                // Chuyển đổi status từ string sang boolean
                $status = false;
                if (isset($post->status)) {
                    $status = in_array($post->status, ['published', '1', 1, true]);
                }
                
                DB::table('posts_new')->insert([
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $slug,
                    'content' => $post->content ?? '',
                    'featured_image' => $post->featured_image ?? null,
                    'status' => $status,
                    'is_featured' => $post->is_featured ?? false,
                    'meta_data' => $post->meta_data ?? null,
                    'views_count' => $post->views_count ?? 0,
                    'published_at' => $post->published_at ?? null,
                    'author_id' => $post->author_id ?? null,
                    'created_at' => $post->created_at ?? now(),
                    'updated_at' => $post->updated_at ?? now(),
                ]);
            }
        }

        // Xóa bảng cũ và đổi tên bảng mới
        Schema::dropIfExists('posts');
        Schema::rename('posts_new', 'posts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tạo lại bảng posts với cấu trúc cũ
        Schema::create('posts_old', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->json('meta_data')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Copy dữ liệu ngược lại
        if (Schema::hasTable('posts')) {
            $posts = DB::table('posts')->get();
            
            foreach ($posts as $post) {
                // Chuyển đổi status từ boolean sang string
                $status = $post->status ? 'published' : 'draft';
                
                // Tạo excerpt từ content
                $excerpt = $this->generateExcerpt($post->content ?? '');
                
                DB::table('posts_old')->insert([
                    'id' => $post->id,
                    'title' => $post->title,
                    'excerpt' => $excerpt,
                    'content' => $post->content ?? '',
                    'featured_image' => $post->featured_image ?? null,
                    'status' => $status,
                    'is_featured' => $post->is_featured ?? false,
                    'meta_data' => $post->meta_data ?? null,
                    'views_count' => $post->views_count ?? 0,
                    'published_at' => $post->published_at ?? null,
                    'author_id' => $post->author_id ?? null,
                    'created_at' => $post->created_at ?? now(),
                    'updated_at' => $post->updated_at ?? now(),
                ]);
            }
        }

        Schema::dropIfExists('posts');
        Schema::rename('posts_old', 'posts');
    }

    /**
     * Generate unique slug from title
     */
    private function generateSlug($title, $id = null)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = DB::table('posts_new')->where('slug', $slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            $query = DB::table('posts_new')->where('slug', $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
        }

        return $slug;
    }

    /**
     * Generate excerpt from content
     */
    private function generateExcerpt($content)
    {
        return \Illuminate\Support\Str::limit(strip_tags($content), 150);
    }
};