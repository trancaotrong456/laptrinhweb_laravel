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
        'image',
        'category_id'
    ];

    // Quan hệ với danh mục (n-1)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}