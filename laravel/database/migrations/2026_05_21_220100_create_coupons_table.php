<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->enum('type', ['percent', 'fixed']);
            $table->decimal('value', 12, 2);
            $table->decimal('min_order_value', 12, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('coupons')->insert([
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10,
                'min_order_value' => 100000,
                'max_discount' => 50000,
                'starts_at' => null,
                'ends_at' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GIAM30K',
                'type' => 'fixed',
                'value' => 30000,
                'min_order_value' => 200000,
                'max_discount' => null,
                'starts_at' => null,
                'ends_at' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
