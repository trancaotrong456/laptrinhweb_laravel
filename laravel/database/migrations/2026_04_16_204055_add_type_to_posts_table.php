<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('posts') || Schema::hasColumn('posts', 'type')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->tinyInteger('type')->default(0)->comment('1: Banner, 0: Small');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('posts') || !Schema::hasColumn('posts', 'type')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
