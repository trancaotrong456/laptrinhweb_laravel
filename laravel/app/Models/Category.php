<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',          // Thêm dòng này
        'slug',
        'description'
    ];

    // Quan hệ với sản phẩm (1 category có nhiều products)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Accessor để lấy số lượng sản phẩm trong danh mục
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }
}