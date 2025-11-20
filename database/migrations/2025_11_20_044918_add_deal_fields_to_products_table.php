<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm các trường quản lý Deal theo thời gian
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kiểm tra xem cột đã tồn tại chưa để tránh lỗi
            if (!Schema::hasColumn('products', 'deal_start_date')) {
                $table->dateTime('deal_start_date')->nullable()->after('sale_price')->comment('Thời gian bắt đầu deal');
            }
            if (!Schema::hasColumn('products', 'deal_end_date')) {
                $table->dateTime('deal_end_date')->nullable()->after('deal_start_date')->comment('Thời gian kết thúc deal');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Xóa các trường deal
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kiểm tra xem cột có tồn tại không trước khi xóa
            if (Schema::hasColumn('products', 'deal_end_date')) {
                $table->dropColumn('deal_end_date');
            }
            if (Schema::hasColumn('products', 'deal_start_date')) {
                $table->dropColumn('deal_start_date');
            }
        });
    }
};
