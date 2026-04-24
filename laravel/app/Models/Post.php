<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
<<<<<<< Updated upstream
    // Cấp quyền cho phép lưu 3 cột này (Chuẩn theo file Migration mới nhất)
    protected $fillable = ['title', 'content', 'image'];
    protected $fillable = [
        'title',
        'content',
        'image',
        'type',
        'priority'
    ];
=======
    // Cấp quyền cho phép lưu các cột này (Chuẩn theo file Migration mới nhất)
    protected $fillable = ['title', 'content', 'image', 'type', 'priority'];
    //
>>>>>>> Stashed changes
}