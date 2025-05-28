<?php
// database/migrations/2025_05_29_update_medicines_table_fix.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Bước 1: Tạo bảng mới với cấu trúc mong muốn
        Schema::create('medicines_new', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên thuốc
            $table->enum('type', ['supplement', 'medicine', 'other'])->default('medicine'); // Loại thuốc
            $table->text('description')->nullable(); // Mô tả
            $table->decimal('import_price', 10, 2)->default(0); // Giá nhập
            $table->decimal('sale_price', 10, 2)->default(0); // Giá bán
            $table->date('expiry_date')->nullable(); // Hạn sử dụng
            $table->string('image')->nullable(); // Đường dẫn ảnh
            $table->boolean('is_active')->default(true); // Trạng thái
            $table->timestamps();
        });

        // Bước 2: Copy dữ liệu từ bảng cũ sang bảng mới (chỉ các trường tương ứng)
        if (Schema::hasTable('medicines')) {
            $oldData = DB::table('medicines')->get();

            foreach ($oldData as $record) {
                DB::table('medicines_new')->insert([
                    'id' => $record->id,
                    'name' => $record->name,
                    'type' => 'medicine', // Default type
                    'description' => $record->description ?? null,
                    'import_price' => 0, // Default value
                    'sale_price' => $record->price ?? 0, // Map price to sale_price
                    'expiry_date' => $record->expiry_date ?? null,
                    'image' => $record->image ?? null,
                    'is_active' => $record->is_active ?? true,
                    'created_at' => $record->created_at ?? now(),
                    'updated_at' => $record->updated_at ?? now(),
                ]);
            }
        }

        // Bước 3: Xóa bảng cũ
        Schema::dropIfExists('medicines');

        // Bước 4: Đổi tên bảng mới thành tên ban đầu
        Schema::rename('medicines_new', 'medicines');
    }

    public function down()
    {
        // Khôi phục lại cấu trúc cũ
        Schema::create('medicines_old', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('unit');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->integer('min_quantity')->default(0);
            $table->string('manufacturer')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Copy dữ liệu ngược lại (chỉ các trường có thể map được)
        if (Schema::hasTable('medicines')) {
            $newData = DB::table('medicines')->get();

            foreach ($newData as $record) {
                DB::table('medicines_old')->insert([
                    'id' => $record->id,
                    'name' => $record->name,
                    'code' => 'AUTO_' . $record->id, // Generate code
                    'description' => $record->description,
                    'unit' => 'viên', // Default unit
                    'price' => $record->sale_price,
                    'quantity' => 0, // Default quantity
                    'min_quantity' => 0, // Default min_quantity
                    'manufacturer' => null, // No manufacturer data
                    'expiry_date' => $record->expiry_date,
                    'image' => $record->image,
                    'is_active' => $record->is_active,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ]);
            }
        }

        Schema::dropIfExists('medicines');
        Schema::rename('medicines_old', 'medicines');
    }
};
