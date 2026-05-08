<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Cho phép lưu các cột này
    protected $fillable = [
        'title',
        'content',
        'image',
        'type',
        'priority'
    ];
}