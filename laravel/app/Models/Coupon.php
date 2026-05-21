<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [

        'code',

        'type',

        'value',

        'min_order_value',

        'max_discount',

        'starts_at',

        'ends_at',

        'usage_limit',

        'used_count',

        'is_active',
    ];

    protected $casts = [

        'value' => 'float',

        'min_order_value' => 'float',

        'max_discount' => 'float',

        'starts_at' => 'datetime',

        'ends_at' => 'datetime',

        'is_active' => 'boolean',
    ];

    public function isCurrentlyValid(): bool
    {
        $now = Carbon::now();

        // inactive
        if (!$this->is_active) {
            return false;
        }

        // chưa tới ngày bắt đầu
        if (
            $this->starts_at
            && $now->lt($this->starts_at)
        ) {
            return false;
        }

        // hết hạn
        if (
            $this->ends_at
            && $now->gt($this->ends_at)
        ) {
            return false;
        }

        // vượt giới hạn sử dụng
        if (
            !is_null($this->usage_limit)
            && $this->used_count >= $this->usage_limit
        ) {
            return false;
        }

        return true;
    }
}