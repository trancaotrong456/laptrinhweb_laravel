<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'type', 'slug', 'description'];

    public function scopeDoUong($query)
    {
        return $query->where('type', 'do_uong');
    }

    public function scopeThucPham($query)
    {
        return $query->where('type', 'thuc_pham');
    }
}