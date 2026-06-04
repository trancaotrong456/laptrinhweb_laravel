<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('categories')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Trái Cây',
                'type' => 'thuc_pham',
                'slug' => 'trai-cay',
                'description' => 'Trái Cây Nhập Khẩu',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Rau củ',
                'type' => 'thuc_pham',
                'slug' => 'rau-cu',
                'description' => 'Rau củ tươi, Siêu Sạch',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Thịt & Hải sản',
                'type' => 'thuc_pham',
                'slug' => 'thit-hai-san',
                'description' => 'Thịt và Hải sản tươi',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Sữa & Trứng',
                'type' => 'thuc_pham',
                'slug' => 'sua-trung',
                'description' => 'Sữa và Trứng',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Bánh Kẹo',
                'type' => 'do_uong',
                'slug' => 'banh-keo',
                'description' => 'Các loại bánh kẹo',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Đồ uống',
                'type' => 'do_uong',
                'slug' => 'do-uong',
                'description' => 'Các loại đồ uống',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}