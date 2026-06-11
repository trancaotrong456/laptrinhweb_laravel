<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'dob',
        'gender',
        'phone',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tự động ép kiểu dữ liệu và mã hóa bảo mật ngầm
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // 👈 Cơ chế tự động biến chuỗi thường thành Bcrypt khi lưu vào DB
        ];
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}