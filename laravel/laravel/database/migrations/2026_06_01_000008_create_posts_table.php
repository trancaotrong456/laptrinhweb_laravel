<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                if (!Schema::hasColumn('posts', 'type')) {
                    $table->tinyInteger('type')->default(0);
                }

                if (!Schema::hasColumn('posts', 'priority')) {
                    $table->integer('priority')->default(0);
                }

                if (!Schema::hasColumn('posts', 'status')) {
                    $table->string('status', 20)->default('published');
                }

                if (!Schema::hasColumn('posts', 'published_at')) {
                    $table->timestamp('published_at')->nullable();
                }
            });

            return;
        }

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->string('image')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->integer('priority')->default(0);
            $table->string('status', 20)->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
