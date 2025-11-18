<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Lưu ghi chú nội bộ và timeline cho đơn hàng
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->text('note'); // Nội dung ghi chú
            $table->string('status')->nullable(); // Trạng thái khi thêm ghi chú (nếu có)
            $table->boolean('is_internal')->default(true); // Ghi chú nội bộ (true) hoặc công khai (false)
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null'); // Admin tạo ghi chú
            $table->timestamps();
            
            // Index để tìm kiếm nhanh
            $table->index('order_id');
            $table->index('is_internal');
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
        Schema::dropIfExists('order_notes');
    }
};
