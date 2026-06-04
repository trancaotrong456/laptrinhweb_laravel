<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('posts') || Schema::hasColumn('posts', 'priority')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->integer('priority')->default(0);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('posts') || !Schema::hasColumn('posts', 'priority')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
