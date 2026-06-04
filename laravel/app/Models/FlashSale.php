<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FlashSale extends Model
{
    protected $fillable = [
        'product_id',
        'sale_price',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'sale_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStatusAttribute(): string
    {
        $now = Carbon::now();
        if ($now->lt($this->starts_at)) return 'upcoming';
        if ($now->gt($this->ends_at))   return 'ended';
        return 'active';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'upcoming' => 'Sắp diễn ra',
            'active'   => 'Đang diễn ra',
            default    => 'Đã kết thúc',
        };
    }

    /* Scope: chỉ lấy flash sale đang hoạt động */
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('starts_at', '<=', $now)
                     ->where('ends_at', '>=', $now);
    }
}
