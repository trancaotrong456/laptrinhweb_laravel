<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'status')) {
                $table->string('status', 20)->default('published');
            }

            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'published_at')) {
                $table->dropColumn('published_at');
            }

            if (Schema::hasColumn('posts', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
