<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Bổ sung HasFactory
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Category extends Model
{
    use HasFactory; // Bổ sung trait HasFactory

    protected $fillable = ['name', 'type', 'slug', 'description', 'parent_id'];

    public function scopeDoUong($query)
    {
        return Schema::hasColumn('categories', 'type')
            ? $query->where('type', 'do_uong')
            : $query;
    }

    public function scopeThucPham($query)
    {
        return Schema::hasColumn('categories', 'type')
            ? $query->where('type', 'thuc_pham')
            : $query;
    }

    public function scopeGiaDung($query)
    {
        return Schema::hasColumn('categories', 'type')
            ? $query->where('type', 'gia_dung')
            : $query;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // BỔ SUNG: Accessor lấy số lượng sản phẩm trong danh mục từ file cũ
    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }
}