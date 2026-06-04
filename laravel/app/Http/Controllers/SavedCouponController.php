<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Tắt khóa ngoại để xóa sạch dữ liệu cũ trong bảng products một cách an toàn
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();
        
        // Mảng chứa toàn bộ sản phẩm được phân loại chuẩn xác theo ID từ 1 đến 6 của bạn
        $products = [
            // --- DANH MỤC ID 5: BÁNH KẸO (8 sản phẩm) ---
            ['name' => 'Bánh quy bơ Danisa 454g', 'category_id' => 5, 'price' => 135000],
            ['name' => 'Snack khoai tây Lays vị tự nhiên', 'category_id' => 5, 'price' => 12000],
            ['name' => 'Kẹo dẻo Chupa Chups', 'category_id' => 5, 'price' => 25000],
            ['name' => 'Bánh xốp phô mai Nabati', 'category_id' => 5, 'price' => 18000],
            ['name' => 'Socola đen Lindt 70%', 'category_id' => 5, 'price' => 95000],
            ['name' => 'Bánh gạo nướng One One', 'category_id' => 5, 'price' => 32000],
            ['name' => 'Kẹo cao su Doublemint', 'category_id' => 5, 'price' => 35000],
            ['name' => 'Bánh Chocopie hộp 12 cái', 'category_id' => 5, 'price' => 55000],

            // --- DANH MỤC ID 6: ĐỒ UỐNG (8 sản phẩm) ---
            ['name' => 'Nước tinh khiết Aquafina 500ml', 'category_id' => 6, 'price' => 5000],
            ['name' => 'Nước ngọt Coca-Cola 320ml', 'category_id' => 6, 'price' => 10000],
            ['name' => 'Trà xanh Không Độ', 'category_id' => 6, 'price' => 11000],
            ['name' => 'Nước ép cam Vfresh 1L', 'category_id' => 6, 'price' => 45000],
            ['name' => 'Bia Heineken lon 330ml', 'category_id' => 6, 'price' => 19000],
            ['name' => 'Nước tăng lực Red Bull', 'category_id' => 6, 'price' => 12000],
            ['name' => 'Cà phê G7 hòa tan đen', 'category_id' => 6, 'price' => 48000],
            ['name' => 'Sữa đậu nành Fami nguyên chất', 'category_id' => 6, 'price' => 18000],

            // --- DANH MỤC ID 2: RAU CỦ (8 sản phẩm) ---
            ['name' => 'Rau muống VietGAP 500g', 'category_id' => 2, 'price' => 15000],
            ['name' => 'Cà chua chery Đà Lạt 250g', 'category_id' => 2, 'price' => 25000],
            ['name' => 'Khoai tây vàng Đà Lạt 1kg', 'category_id' => 2, 'price' => 35000],
            ['name' => 'Cà rốt giống Nhật 500g', 'category_id' => 2, 'price' => 18000],
            ['name' => 'Bắp cải xanh trái tim 1kg', 'category_id' => 2, 'price' => 22000],
            ['name' => 'Hành tây Đà Lạt 500g', 'category_id' => 2, 'price' => 16000],
            ['name' => 'Súp lơ xanh (Broccoli) 500g', 'category_id' => 2, 'price' => 30000],
            ['name' => 'Nấm kim châm Hàn Quốc 150g', 'category_id' => 2, 'price' => 14000],

            // --- DANH MỤC ID 4: SỮA & TRỨNG (8 sản phẩm) ---
            ['name' => 'Sữa tươi tiệt trùng Vinamilk 1L', 'category_id' => 4, 'price' => 32000],
            ['name' => 'Sữa chua nha đam TH True Milk (Lốc 4)', 'category_id' => 4, 'price' => 28000],
            ['name' => 'Trứng gà Ba Huân (Hộp 10 quả)', 'category_id' => 4, 'price' => 33000],
            ['name' => 'Trứng vịt lộn làm sạch (Hộp 6 quả)', 'category_id' => 4, 'price' => 42000],
            ['name' => 'Sữa đặc Ông Thọ nhãn đỏ', 'category_id' => 4, 'price' => 23000],
            ['name' => 'Bơ lạt TH True Butter 200g', 'category_id' => 4, 'price' => 65000],
            ['name' => 'Phô mai Con Bò Cười (Hộp 8 miếng)', 'category_id' => 4, 'price' => 38000],
            ['name' => 'Trứng cút CP (Hộp 30 quả)', 'category_id' => 4, 'price' => 25000],

            // --- DANH MỤC ID 3: THỊT & HẢI SẢN (9 sản phẩm) ---
            ['name' => 'Thịt ba chỉ heo CP 500g', 'category_id' => 3, 'price' => 75000],
            ['name' => 'Thịt bò Úc nhập khẩu 300g', 'category_id' => 3, 'price' => 115000],
            ['name' => 'Đùi gà công nghiệp làm sạch 500g', 'category_id' => 3, 'price' => 45000],
            ['name' => 'Cá hồi Na Uy phi lê 200g', 'category_id' => 3, 'price' => 135000],
            ['name' => 'Mực ống tươi sống 500g', 'category_id' => 3, 'price' => 160000],
            ['name' => 'Tôm sú biển đông lạnh 500g', 'category_id' => 3, 'price' => 190000],
            ['name' => 'Sườn non heo MEATDeli 500g', 'category_id' => 3, 'price' => 125000],
            ['name' => 'Thịt nạc dăm heo xay 300g', 'category_id' => 3, 'price' => 42000],
            ['name' => 'Cá lăng cắt khúc 500g', 'category_id' => 3, 'price' => 85000],

            // --- DANH MỤC ID 1: TRÁI CÂY (9 sản phẩm) ---
            ['name' => 'Táo Fuji Nam Phi 1kg', 'category_id' => 1, 'price' => 65000],
            ['name' => 'Chuối tiêu hồng xuất khẩu 1 nải', 'category_id' => 1, 'price' => 25000],
            ['name' => 'Nho đen không hạt Mỹ 500g', 'category_id' => 1, 'price' => 120000],
            ['name' => 'Dưa hấu không hạt ruột đỏ 1kg', 'category_id' => 1, 'price' => 22000],
            ['name' => 'Xoài cát Hòa Lộc loại 1 1kg', 'category_id' => 1, 'price' => 85000],
            ['name' => 'Cam sành Vĩnh Long vắt nước 1kg', 'category_id' => 1, 'price' => 35000],
            ['name' => 'Dưa lưới ruột xanh 1kg', 'category_id' => 1, 'price' => 60000],
            ['name' => 'Thanh long ruột đỏ Bình Thuận 1kg', 'category_id' => 1, 'price' => 30000],
            ['name' => 'Bơ sáp Đắk Lắk 1kg', 'category_id' => 1, 'price' => 55000],
        ];

        $insertData = [];
        foreach ($products as $product) {
            $insertData[] = [
                'name'        => $product['name'],
                'category_id' => $product['category_id'],
                'price'       => $product['price'],
                'description' => 'Mô tả cho sản phẩm ' . $product['name'] . '. Đảm bảo hàng tươi ngon, chất lượng tuyệt đối.',
                'image'       => null, 
                'quantity'    => rand(20, 100),
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::table('products')->insert($insertData);
    }
}