<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Thêm cột vào posts (kiểm tra trước để tránh lỗi) ──
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('priority');
            }
            if (!Schema::hasColumn('posts', 'tags')) {
                $table->string('tags')->nullable()->after('views');
            }
            if (!Schema::hasColumn('posts', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('tags');
            }
        });

        // ── 2. Bảng bình luận bài viết ────────────────────────
        if (!Schema::hasTable('post_comments')) {
            Schema::create('post_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->text('content');
                $table->timestamps();
            });
        }

        // ── 3. Bảng like bài viết ─────────────────────────────
        if (!Schema::hasTable('post_likes')) {
            Schema::create('post_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['post_id', 'user_id']); // Mỗi người chỉ like 1 lần
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('post_comments');
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'views')) $table->dropColumn('views');
            if (Schema::hasColumn('posts', 'tags')) $table->dropColumn('tags');
            if (Schema::hasColumn('posts', 'expires_at')) $table->dropColumn('expires_at');
        });
    }
};
