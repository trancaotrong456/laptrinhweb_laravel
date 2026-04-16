<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Đã xóa bỏ các từ khóa bị lặp
   protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'role', // Nhớ thêm role vào đây để có thể lưu dữ liệu
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}