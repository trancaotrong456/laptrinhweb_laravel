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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->integer('price');
    $table->integer('quantity');
    $table->text('description')->nullable();
    $table->string('image')->nullable(); // Ảnh sản phẩm (cho phép trống lúc mới tạo)
    // Tạo cột category_id và tự động nối khóa ngoại sang bảng categories
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};