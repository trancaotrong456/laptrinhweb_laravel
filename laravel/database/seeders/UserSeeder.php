<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Xóa sạch dữ liệu cũ trong bảng users trước khi nạp tài khoản mới
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name'              => 'Quản trị viên (Admin)',
                'email'             => 'admin@gmail.com',
                'email_verified_at' => $now,
                'password'          => Hash::make('admin123456789'), // Mã hóa mật khẩu bảo mật
                'role'              => 1, // Quyền Admin
                'phone'             => '0987654321',
                'address'           => 'Hồ Chí Minh, Việt Nam',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'name'              => 'Khách Hàng Mẫu',
                'email'             => 'khachhang@gmail.com',
                'email_verified_at' => $now,
                'password'          => Hash::make('khachhang123456789'), // Mã hóa mật khẩu bảo mật
                'role'              => 0, // Quyền Khách hàng (User thường)
                'phone'             => '0123456789',
                'address'           => 'Hà Nội, Việt Nam',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ]);
    }
}