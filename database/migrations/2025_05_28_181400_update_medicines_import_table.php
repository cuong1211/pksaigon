<?php
// database/migrations/2025_05_29_update_medicine_imports_table_fix.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Bước 1: Tạo bảng mới với cấu trúc mong muốn (bỏ unit_price và total_price)
        Schema::create('medicine_imports_new', function (Blueprint $table) {
            $table->id();
            $table->string('import_code')->unique(); // Mã phiếu nhập
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade'); // Liên kết với thuốc
            $table->integer('quantity'); // Số lượng nhập
            $table->decimal('total_amount', 15, 2)->default(0); // Tổng tiền nhập
            $table->string('invoice_image')->nullable(); // Ảnh hóa đơn
            $table->date('import_date'); // Ngày nhập
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
            
            // Indexes
            $table->index('medicine_id');
            $table->index('import_date');
            $table->index('import_code');
        });

        // Bước 2: Copy dữ liệu từ bảng cũ sang bảng mới (bỏ qua unit_price và total_price)
        if (Schema::hasTable('medicine_imports')) {
            $oldData = DB::table('medicine_imports')->get();
            
            foreach ($oldData as $record) {
                DB::table('medicine_imports_new')->insert([
                    'id' => $record->id,
                    'import_code' => $record->import_code,
                    'medicine_id' => $record->medicine_id,
                    'quantity' => $record->quantity,
                    'total_amount' => $record->total_price ?? 0, // Map từ total_price cũ
                    'invoice_image' => $record->invoice_image ?? null,
                    'import_date' => $record->import_date,
                    'notes' => $record->notes ?? null,
                    'created_at' => $record->created_at ?? now(),
                    'updated_at' => $record->updated_at ?? now(),
                ]);
            }
        }

        // Bước 3: Xóa bảng cũ
        Schema::dropIfExists('medicine_imports');

        // Bước 4: Đổi tên bảng mới thành tên ban đầu
        Schema::rename('medicine_imports_new', 'medicine_imports');
    }

    public function down()
    {
        // Khôi phục lại cấu trúc cũ
        Schema::create('medicine_imports_old', function (Blueprint $table) {
            $table->id();
            $table->string('import_code')->unique();
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->integer('quantity');
            $table->bigInteger('unit_price'); // Khôi phục unit_price
            $table->bigInteger('total_price'); // Khôi phục total_price
            $table->string('invoice_image')->nullable();
            $table->date('import_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('medicine_id');
            $table->index('import_date');
            $table->index('import_code');
        });

        // Copy dữ liệu ngược lại
        if (Schema::hasTable('medicine_imports')) {
            $newData = DB::table('medicine_imports')->get();
            
            foreach ($newData as $record) {
                DB::table('medicine_imports_old')->insert([
                    'id' => $record->id,
                    'import_code' => $record->import_code,
                    'medicine_id' => $record->medicine_id,
                    'quantity' => $record->quantity,
                    'unit_price' => 0, // Default value
                    'total_price' => 0, // Default value
                    'invoice_image' => $record->invoice_image,
                    'import_date' => $record->import_date,
                    'notes' => $record->notes,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ]);
            }
        }

        Schema::dropIfExists('medicine_imports');
        Schema::rename('medicine_imports_old', 'medicine_imports');
    }
};