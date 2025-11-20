<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /**
     * Run the migrations.
     * Thêm cột sale_price vào bảng products
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kiểm tra xem cột đã tồn tại chưa để tránh lỗi
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Xóa cột sale_price khỏi bảng products
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kiểm tra xem cột có tồn tại không trước khi xóa
            if (Schema::hasColumn('products', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
        });
    }
};
