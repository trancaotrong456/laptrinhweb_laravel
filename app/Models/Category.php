<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Category extends Model
{
    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'type',
        'slug',
        'description',
        'parent_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | PARENT CATEGORY
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        if (Schema::hasColumn('categories', 'parent_id')) {
            return $this->belongsTo(
                Category::class,
                'parent_id'
            );
        }

        return $this->belongsTo(
            Category::class,
            'id',
            'id'
        )->whereRaw('1 = 0');
    }

    /*
    |--------------------------------------------------------------------------
    | CHILD CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        if (Schema::hasColumn('categories', 'parent_id')) {
            return $this->hasMany(
                Category::class,
                'parent_id'
            );
        }

        return $this->hasMany(
            Category::class,
            'id',
            'id'
        )->whereRaw('1 = 0');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function hasChildren(): bool
    {
        if (!Schema::hasColumn('categories', 'parent_id')) {
            return false;
        }

        return $this->children()->exists();
    }

    public function isParent(): bool
    {
        if (!Schema::hasColumn('categories', 'parent_id')) {
            return false;
        }

        return is_null($this->parent_id);
    }
}