<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
        'image',
        'category_id',
        'status'
    ];

    // Quan hệ với category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Str::contains($this->image, '/')) {
            return asset('storage/' . $this->image);
        }

        return asset('images/' . $this->image);
    }
    public function wishlists()
    {
        return $this->hasMany(
            Wishlist::class,
            'product_id'
        );
    }
}
