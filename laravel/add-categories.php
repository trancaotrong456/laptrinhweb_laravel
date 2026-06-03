#!/usr/bin/env php
<?php

$basePath = __DIR__;
require_once $basePath . '/vendor/autoload.php';
$app = require_once $basePath . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

$categories = [
    ['name' => 'Rau củ', 'type' => 'thuc_pham', 'slug' => 'rau-cu', 'description' => 'Rau xanh và các loại củ quả tươi mới, hữu cơ, trồng theo tiêu chuẩn nông nghiệp hiện đại.'],
    ['name' => 'Thịt & hải sản', 'type' => 'thuc_pham', 'slug' => 'thit-hai-san', 'description' => 'Thịt tươi, hải sản sạch, được chọn lọc kỹ càng từ các nguồn cung ứng uy tín.'],
    ['name' => 'Sữa & Trứng', 'type' => 'thuc_pham', 'slug' => 'sua-trung', 'description' => 'Sữa, trứng và các sản phẩm từ động vật, đảm bảo dinh dưỡng và sức khỏe cho cả gia đình.'],
    ['name' => 'Bánh kẹo', 'type' => 'do_uong', 'slug' => 'banh-keo', 'description' => 'Bánh, kẹo, snack vặt chất lượng, thích hợp cho tất cả mọi người trong gia đình.'],
    ['name' => 'Đồ uống', 'type' => 'do_uong', 'slug' => 'do-uong', 'description' => 'Nước uống, nước trái cây, soda, cà phê và các loại đồ uống khác, mát lạnh và ngon miệng.'],
];

echo "📦 Tạo danh mục...\n";
$created = 0;
foreach ($categories as $cat) {
    if (!Category::where('name', $cat['name'])->exists()) {
        Category::create($cat);
        echo "✓ {$cat['name']}\n";
        $created++;
    }
}
echo "Hoàn thành! Tạo $created danh mục\n";
?>
