<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
        'image',
        'category_id'
    ];

    // Quan hệ với category (nhiều products thuộc về 1 category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
