<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Lưu lịch sử thay đổi giá và tồn kho sản phẩm
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('field_changed'); // 'price' hoặc 'stock'
            $table->decimal('old_value', 15, 2)->nullable(); // Giá trị cũ
            $table->decimal('new_value', 15, 2); // Giá trị mới
            $table->foreignId('changed_by')->nullable()->constrained('admins')->onDelete('set null'); // Admin thay đổi
            $table->text('notes')->nullable(); // Ghi chú (nếu có)
            $table->timestamps();
            
            // Index để tìm kiếm nhanh
            $table->index('product_id');
            $table->index('field_changed');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_logs');
    }
};
