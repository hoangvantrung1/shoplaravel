<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod')->after('status');
            $table->timestamp('payment_date')->nullable()->after('payment_method');
            $table->string('transaction_id')->nullable()->after('payment_date');
            $table->string('order_code')->unique()->after('id'); // Thêm nếu chưa có
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_date', 'transaction_id', 'order_code']);
        });
    }
}