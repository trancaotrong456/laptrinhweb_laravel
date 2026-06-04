<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_HIDDEN    = 'hidden';

    protected $fillable = [
        'title',
        'content',
        'image',
        'type',
        'is_pinned',
        'priority',
        'status',
        'published_at',
        'views',
        'tags',
        'expires_at',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
        'views'        => 'integer',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT     => 'Bản nháp',
            self::STATUS_PUBLISHED => 'Công khai',
            self::STATUS_HIDDEN    => 'Đang ẩn',
        ];
    }

    public function isVisible(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }
        if ($this->published_at && $this->published_at->gt(now())) {
            return false;
        }
        // Tự ẩn khi hết hạn
        if ($this->expires_at && $this->expires_at->lt(now())) {
            return false;
        }
        return true;
    }

    /** Lấy mảng tags từ chuỗi "flash-sale,hot" */
    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) return [];
        return array_filter(array_map('trim', explode(',', $this->tags)));
    }

    // ── Relationships ─────────────────────────────────────────
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class)->latest();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }
}
