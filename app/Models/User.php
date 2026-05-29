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

    // No hashed cast - plain text passwords
}