<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Thêm cột type để phân biệt Banner và Ảnh nhỏ
            $table->tinyInteger('type')->default(0)->after('content')->comment('1: Banner, 0: Small');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Xóa cột type khi rollback
            $table->dropColumn('type');
        });
    }
};