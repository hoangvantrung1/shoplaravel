<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm cột images (JSON) và video (nullable string) để lưu ảnh/video từ người dùng
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->json('images')->nullable()->after('comment')->comment('Mảng đường dẫn ảnh từ người dùng');
            $table->string('video')->nullable()->after('images')->comment('Đường dẫn video từ người dùng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['images', 'video']);
        });
    }
};
