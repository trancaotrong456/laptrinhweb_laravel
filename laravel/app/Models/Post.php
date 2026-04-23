<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Cấp quyền cho phép lưu 3 cột này (Chuẩn theo file Migration mới nhất)
    protected $fillable = ['title', 'content', 'image'];
}