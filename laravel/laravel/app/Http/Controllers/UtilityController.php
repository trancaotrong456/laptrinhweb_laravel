<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    /**
     * Seed categories (Admin only)
     */
    public function seedCategories()
    {
        if (!auth()->check() || (int) auth()->user()->role !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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

        $created = 0;
        $skipped = 0;

        foreach ($categories as $cat) {
            if (!Category::where('name', $cat['name'])->exists()) {
                Category::create($cat);
                $created++;
            } else {
                $skipped++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Tạo $created danh mục, bỏ qua $skipped danh mục đã tồn tại.",
            'created' => $created,
            'skipped' => $skipped
        ]);
    }
}
