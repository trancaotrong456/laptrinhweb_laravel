<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_HIDDEN = 'hidden';

    protected $fillable = [
        'title',
        'content',
        'image',
        'type',
        'priority',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Ban nhap',
            self::STATUS_PUBLISHED => 'Cong khai',
            self::STATUS_HIDDEN => 'Dang an',
        ];
    }

    public function isVisible(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && (is_null($this->published_at) || $this->published_at->lte(now()));
    }
}
