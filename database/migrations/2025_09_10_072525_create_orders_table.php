<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->decimal('total', 15, 2);
            $table->enum('status', ['unpaid', 'pending', 'processing', 'completed', 'cancelled', 'paid', 'failed'])->default('unpaid');
            $table->enum('payment_method', ['cod', 'vnpay']);
            $table->timestamp('payment_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
