<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insertOrIgnore([
            [
                'name' => 'Rau củ',
                'type' => 'thuc_pham',
                'slug' => 'rau-cu',
                'description' => 'Rau xanh và các loại củ quả tươi mới, hữu cơ, trồng theo tiêu chuẩn nông nghiệp hiện đại.',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thịt & hải sản',
                'type' => 'thuc_pham',
                'slug' => 'thit-hai-san',
                'description' => 'Thịt tươi, hải sản sạch, được chọn lọc kỹ càng từ các nguồn cung ứng uy tín.',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sữa & Trứng',
                'type' => 'thuc_pham',
                'slug' => 'sua-trung',
                'description' => 'Sữa, trứng và các sản phẩm từ động vật, đảm bảo dinh dưỡng và sức khỏe cho cả gia đình.',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bánh kẹo',
                'type' => 'do_uong',
                'slug' => 'banh-keo',
                'description' => 'Bánh, kẹo, snack vặt chất lượng, thích hợp cho tất cả mọi người trong gia đình.',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đồ uống',
                'type' => 'do_uong',
                'slug' => 'do-uong',
                'description' => 'Nước uống, nước trái cây, soda, cà phê và các loại đồ uống khác, mát lạnh và ngon miệng.',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

