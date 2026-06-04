<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categories = [
            [
                'name' => 'Rau củ',
                'type' => 'thuc_pham',
                'slug' => 'rau-cu',
                'description' => 'Rau xanh và các loại củ quả tươi mới, hữu cơ, trồng theo tiêu chuẩn nông nghiệp hiện đại.'
            ],
            [
                'name' => 'Thịt & hải sản',
                'type' => 'thuc_pham',
                'slug' => 'thit-hai-san',
                'description' => 'Thịt tươi, hải sản sạch, được chọn lọc kỹ càng từ các nguồn cung ứng uy tín.'
            ],
            [
                'name' => 'Sữa & Trứng',
                'type' => 'thuc_pham',
                'slug' => 'sua-trung',
                'description' => 'Sữa, trứng và các sản phẩm từ động vật, đảm bảo dinh dưỡng và sức khỏe cho cả gia đình.'
            ],
            [
                'name' => 'Bánh kẹo',
                'type' => 'do_uong',
                'slug' => 'banh-keo',
                'description' => 'Bánh, kẹo, snack vặt chất lượng, thích hợp cho tất cả mọi người trong gia đình.'
            ],
            [
                'name' => 'Đồ uống',
                'type' => 'do_uong',
                'slug' => 'do-uong',
                'description' => 'Nước uống, nước trái cây, soda, cà phê và các loại đồ uống khác, mát lạnh và ngon miệng.'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Category::whereIn('name', ['Rau củ', 'Thịt & hải sản', 'Sữa & Trứng', 'Bánh kẹo', 'Đồ uống'])->delete();
    }
};
