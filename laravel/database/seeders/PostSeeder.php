<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Chạy seeder để tạo dữ liệu tin tức và banner khuyến mãi.
     * type = 1 => Banner lớn (hiển thị trên Slider trang chủ)
     * type = 2 => Tin khuyến mãi nhỏ (hiển thị trong danh sách bài viết)
     */
    public function run(): void
    {
        DB::table('posts')->truncate();

        $now = Carbon::now();

        // ─────────────────────────────────────────────────────────
        //  BANNER LỚN (type = 1) — 15 bài
        //  Hiển thị trên carousel/slider trang chủ
        // ─────────────────────────────────────────────────────────
        $banners = [
            [
                'title'            => 'Siêu Sale Cuối Tuần – Giảm đến 50%',
                'content'          => 'Chương trình khuyến mãi lớn nhất tuần! Hàng nghìn sản phẩm tươi sống, thực phẩm sạch, đồ uống đang được giảm giá sốc lên đến 50%. Đặt hàng ngay hôm nay để nhận ưu đãi tốt nhất từ siêu thị trực tuyến của chúng tôi.',
                'type'             => 1,
                'priority'         => 10,
                'tags'             => 'sale,cuoi-tuan,hot',
                'meta_title'       => 'Siêu Sale Cuối Tuần - Giảm 50%',
                'meta_description' => 'Hàng nghìn sản phẩm tươi sống giảm giá sốc đến 50%.',
            ],
            [
                'title'            => 'Rau Củ Sạch – Tươi Ngon Mỗi Ngày',
                'content'          => 'Chúng tôi tự hào cung cấp rau củ quả tươi theo tiêu chuẩn VietGAP, GlobalGAP trực tiếp từ trang trại đến bàn ăn của bạn. Đảm bảo không thuốc trừ sâu, an toàn cho cả gia đình.',
                'type'             => 1,
                'priority'         => 9,
                'tags'             => 'rau-sach,huu-co,vietgap',
                'meta_title'       => 'Rau Củ Sạch Chuẩn VietGAP',
                'meta_description' => 'Rau củ tươi, sạch, an toàn từ trang trại đến bàn ăn.',
            ],
            [
                'title'            => 'Flash Sale Thứ Sáu – Giá Sốc Chỉ 24h',
                'content'          => 'Flash Sale diễn ra mỗi thứ Sáu hàng tuần từ 20:00 đến 23:59. Hàng trăm sản phẩm giảm giá cực sâu, số lượng có hạn. Đặt đồng hồ và đừng bỏ lỡ!',
                'type'             => 1,
                'priority'         => 10,
                'tags'             => 'flash-sale,thu-sau,gia-soc',
                'meta_title'       => 'Flash Sale Thứ Sáu - Giảm Giá Sốc',
                'meta_description' => 'Flash Sale mỗi thứ Sáu, giảm sâu chỉ trong 24h!',
            ],
            [
                'title'            => 'Combo Thực Phẩm Organic Cao Cấp',
                'content'          => 'Bộ sưu tập thực phẩm hữu cơ cao cấp dành cho những gia đình yêu chuộng lối sống lành mạnh. Gồm rau củ, trái cây, thịt hữu cơ được chứng nhận bởi các tổ chức quốc tế uy tín.',
                'type'             => 1,
                'priority'         => 8,
                'tags'             => 'organic,cao-cap,suc-khoe',
                'meta_title'       => 'Combo Thực Phẩm Organic Cao Cấp',
                'meta_description' => 'Thực phẩm hữu cơ chứng nhận quốc tế cho gia đình bạn.',
            ],
            [
                'title'            => 'Hải Sản Tươi Sống – Nhập Bến Mỗi Ngày',
                'content'          => 'Hải sản được khai thác tươi và vận chuyển bằng xe lạnh đến kho hàng trong vòng 6 tiếng. Tôm, cua, cá, mực tươi rói, đảm bảo độ ngon và dinh dưỡng cao nhất.',
                'type'             => 1,
                'priority'         => 7,
                'tags'             => 'hai-san,tuoi-song,sach',
                'meta_title'       => 'Hải Sản Tươi Sống Nhập Bến Mỗi Ngày',
                'meta_description' => 'Tôm, cua, cá, mực tươi rói vận chuyển lạnh 6 tiếng.',
            ],
            [
                'title'            => 'Miễn Phí Giao Hàng Đơn Từ 300k',
                'content'          => 'Đặt hàng từ 300.000đ trở lên, bạn sẽ được miễn phí giao hàng tận nhà trong vòng 2-4 tiếng. Dịch vụ giao hàng hoạt động từ 6:00 sáng đến 22:00 tối mỗi ngày kể cả cuối tuần và ngày lễ.',
                'type'             => 1,
                'priority'         => 6,
                'tags'             => 'giao-hang,mien-phi,tiet-kiem',
                'meta_title'       => 'Miễn Phí Giao Hàng Đơn Từ 300k',
                'meta_description' => 'Giao hàng nhanh 2-4 tiếng, miễn phí khi đơn từ 300k.',
            ],
            [
                'title'            => 'Trái Cây Nhập Khẩu Chất Lượng Cao',
                'content'          => 'Táo, lê, nho, cherry nhập khẩu từ Mỹ, Úc, Hàn Quốc và New Zealand. Tất cả đều có giấy chứng nhận kiểm định chất lượng, an toàn thực phẩm và nguồn gốc rõ ràng.',
                'type'             => 1,
                'priority'         => 8,
                'tags'             => 'trai-cay,nhap-khau,chat-luong',
                'meta_title'       => 'Trái Cây Nhập Khẩu Chất Lượng Cao',
                'meta_description' => 'Táo, nho, cherry, lê nhập khẩu có kiểm định rõ ràng.',
            ],
            [
                'title'            => 'Khuyến Mãi Thành Viên – Giảm Thêm 10%',
                'content'          => 'Đăng ký thành viên ngay hôm nay để nhận ưu đãi giảm thêm 10% cho mọi đơn hàng, tích lũy điểm thưởng, nhận thông báo flash sale sớm nhất và nhiều quyền lợi độc quyền khác.',
                'type'             => 1,
                'priority'         => 9,
                'tags'             => 'thanh-vien,uu-dai,diem-thuong',
                'meta_title'       => 'Ưu Đãi Thành Viên - Giảm Thêm 10%',
                'meta_description' => 'Đăng ký thành viên để giảm thêm 10% và tích điểm thưởng.',
            ],
            [
                'title'            => 'Sữa & Trứng Cao Cấp – Nhãn Hiệu Uy Tín',
                'content'          => 'Danh mục sữa và trứng đa dạng từ các thương hiệu hàng đầu như Vinamilk, TH True Milk, Ba Huân, Nutifood. Sản phẩm đảm bảo ngày sản xuất mới nhất, bảo quản lạnh toàn hành trình.',
                'type'             => 1,
                'priority'         => 5,
                'tags'             => 'sua,trung,vinamilk,th-true-milk',
                'meta_title'       => 'Sữa & Trứng Cao Cấp Nhãn Hiệu Uy Tín',
                'meta_description' => 'Sữa, trứng từ Vinamilk, TH True Milk, Ba Huân chất lượng cao.',
            ],
            [
                'title'            => 'Mua Sắm Đêm Khuya – Sale 0-6h Sáng',
                'content'          => 'Chương trình "Mua Sắm Đêm Khuya" dành riêng cho những tín đồ mua sắm về đêm. Từ 00:00 đến 06:00 sáng mỗi ngày, hàng chọn lọc giảm giá từ 20-40%. Giao hàng ngay sáng sớm.',
                'type'             => 1,
                'priority'         => 7,
                'tags'             => 'dem-khuya,flash-sale,giao-sang',
                'meta_title'       => 'Sale Đêm Khuya 0-6h - Giảm 20-40%',
                'meta_description' => 'Mua sắm đêm khuya giảm 20-40%, giao hàng ngay sáng sớm.',
            ],
            [
                'title'            => 'Thịt Sạch MEATDeli – Kiểm Soát Nguồn Gốc',
                'content'          => 'Thịt heo, bò, gà từ trang trại khép kín, được kiểm soát từ con giống đến bàn ăn. Công nghệ giết mổ và đóng gói hiện đại đảm bảo vệ sinh an toàn thực phẩm ở chuẩn quốc tế.',
                'type'             => 1,
                'priority'         => 6,
                'tags'             => 'thit-sach,meatdeli,nguon-goc',
                'meta_title'       => 'Thịt Sạch MEATDeli - Kiểm Soát Nguồn Gốc',
                'meta_description' => 'Thịt heo, bò, gà từ trang trại khép kín, an toàn 100%.',
            ],
            [
                'title'            => 'Bánh Kẹo Nhập Khẩu Dịp Lễ Tết',
                'content'          => 'Bộ sưu tập bánh kẹo cao cấp nhập khẩu từ Nhật Bản, Hàn Quốc, Châu Âu. Hộp quà tặng sang trọng, thiết kế đẹp – lựa chọn hoàn hảo để tặng người thân và đối tác trong dịp lễ Tết.',
                'type'             => 1,
                'priority'         => 5,
                'tags'             => 'banh-keo,le-tet,qua-tang,nhap-khau',
                'meta_title'       => 'Bánh Kẹo Nhập Khẩu Dịp Lễ Tết',
                'meta_description' => 'Bánh kẹo cao cấp Nhật, Hàn, Châu Âu – hộp quà sang trọng.',
            ],
            [
                'title'            => 'Đồ Uống Giải Nhiệt Mùa Hè',
                'content'          => 'Danh sách đồ uống giải nhiệt được yêu thích nhất mùa hè: nước ép trái cây tươi, sinh tố nguyên chất, trà trái cây, nước detox. Đặt hàng combo tiết kiệm hơn 25% so với mua lẻ.',
                'type'             => 1,
                'priority'         => 7,
                'tags'             => 'do-uong,mua-he,giai-nhiet,combo',
                'meta_title'       => 'Đồ Uống Giải Nhiệt Mùa Hè',
                'meta_description' => 'Nước ép, sinh tố, trà trái cây giải nhiệt combo giảm 25%.',
            ],
            [
                'title'            => 'Chương Trình Khách Hàng Thân Thiết 2026',
                'content'          => 'Ra mắt chương trình tích lũy điểm hoàn toàn mới! Mỗi 10.000đ chi tiêu = 1 điểm tích lũy. Đổi điểm lấy quà tặng, voucher giảm giá hoặc các sản phẩm trong danh mục đặc biệt.',
                'type'             => 1,
                'priority'         => 8,
                'tags'             => 'tich-luy,thanh-thiet,diem-thuong,2026',
                'meta_title'       => 'Chương Trình Khách Hàng Thân Thiết 2026',
                'meta_description' => 'Tích lũy điểm, đổi quà tặng & voucher với mọi đơn hàng.',
            ],
            [
                'title'            => 'Giao Hàng Siêu Tốc – Trong Vòng 2 Tiếng',
                'content'          => 'Với đội ngũ hơn 200 shipper chuyên nghiệp tại TP.HCM và Hà Nội, chúng tôi cam kết giao hàng trong vòng 2 tiếng sau khi xác nhận đơn hàng. Theo dõi trạng thái giao hàng realtime ngay trên app.',
                'type'             => 1,
                'priority'         => 9,
                'tags'             => 'giao-hang,sieu-toc,2-tieng,shipper',
                'meta_title'       => 'Giao Hàng Siêu Tốc Trong 2 Tiếng',
                'meta_description' => 'Đội shipper chuyên nghiệp, cam kết giao trong 2 tiếng.',
            ],
        ];

        // ─────────────────────────────────────────────────────────
        //  TIN KHUYẾN MÃI NHỎ (type = 2) — 10 bài
        //  Hiển thị trong trang danh sách bài viết / tin tức
        // ─────────────────────────────────────────────────────────
        $promotions = [
            [
                'title'            => 'Mã FREESHIP – Miễn Phí Vận Chuyển Toàn Quốc',
                'content'          => 'Nhập mã FREESHIP khi thanh toán để được miễn phí vận chuyển cho đơn hàng bất kỳ trong tháng 6/2026. Áp dụng cho tất cả khách hàng, không giới hạn số lần sử dụng (giới hạn 1 lần/tài khoản/ngày).',
                'type'             => 2,
                'priority'         => 5,
                'tags'             => 'freeship,ma-giam-gia,van-chuyen',
                'meta_title'       => 'Mã FREESHIP - Miễn Phí Vận Chuyển',
                'meta_description' => 'Nhập FREESHIP để miễn phí vận chuyển trong tháng 6/2026.',
            ],
            [
                'title'            => 'Giảm 15% Cho Khách Hàng Mới – Mã WELCOME15',
                'content'          => 'Chào mừng bạn đến với siêu thị trực tuyến! Đơn hàng đầu tiên của bạn sẽ được giảm ngay 15% khi nhập mã WELCOME15. Ưu đãi áp dụng một lần cho mỗi tài khoản, không áp dụng cho sản phẩm đã giảm giá.',
                'type'             => 2,
                'priority'         => 6,
                'tags'             => 'khach-moi,giam-15,welcome',
                'meta_title'       => 'Giảm 15% Cho Đơn Hàng Đầu Tiên',
                'meta_description' => 'Mã WELCOME15 giảm 15% cho khách hàng đặt lần đầu.',
            ],
            [
                'title'            => 'Combo Rau Củ Organic Tuần – Tiết Kiệm 30%',
                'content'          => 'Đăng ký combo rau củ organic theo tuần, nhận hàng mỗi thứ Hai. Bao gồm 5-7 loại rau củ quả theo mùa, đa dạng thực đơn cả tuần. Tiết kiệm lên đến 30% so với mua lẻ tại siêu thị.',
                'type'             => 2,
                'priority'         => 4,
                'tags'             => 'combo,rau-organic,tiet-kiem,tuan',
                'meta_title'       => 'Combo Rau Củ Organic Tiết Kiệm 30%',
                'meta_description' => 'Đăng ký combo rau củ organic tuần, tiết kiệm đến 30%.',
            ],
            [
                'title'            => 'Tặng Quà Khi Mua Sữa Vinamilk – Hộp 1L',
                'content'          => 'Mua 6 hộp sữa tươi tiệt trùng Vinamilk 1L bất kỳ sẽ được tặng kèm 1 hộp sữa chua uống Vinamilk và 1 ly nhựa in logo thương hiệu. Chương trình áp dụng đến hết tháng 6/2026 hoặc hết hàng tặng.',
                'type'             => 2,
                'priority'         => 3,
                'tags'             => 'vinamilk,tang-qua,sua-tuoi',
                'meta_title'       => 'Mua Sữa Vinamilk Nhận Quà Tặng',
                'meta_description' => 'Mua 6 hộp sữa Vinamilk tặng ngay 1 hộp sữa chua uống.',
            ],
            [
                'title'            => 'Flash Sale 12h Trưa – Hải Sản Giảm 40%',
                'content'          => 'Mỗi ngày từ 11:45 đến 12:15, hàng loạt sản phẩm hải sản tươi sẽ được giảm giá sốc 40%. Tôm, cua, ghẹ, cá biển tươi nhập bến sáng sớm giảm giá giờ vàng. Số lượng giới hạn, ai nhanh người đó có!',
                'type'             => 2,
                'priority'         => 5,
                'tags'             => 'flash-sale,hai-san,gio-vang,giam-40',
                'meta_title'       => 'Flash Sale 12h Trưa - Hải Sản Giảm 40%',
                'meta_description' => 'Hải sản tươi giảm sốc 40% mỗi ngày từ 11:45-12:15.',
            ],
            [
                'title'            => 'Mua 2 Tặng 1 – Bánh Kẹo & Đồ Ăn Vặt',
                'content'          => 'Chương trình mua 2 tặng 1 áp dụng cho toàn bộ danh mục bánh kẹo và đồ ăn vặt. Sản phẩm tặng có giá trị thấp hơn hoặc bằng sản phẩm đã mua. Đặt ngay để không bỏ lỡ ưu đãi!',
                'type'             => 2,
                'priority'         => 4,
                'tags'             => 'mua-2-tang-1,banh-keo,an-vat',
                'meta_title'       => 'Mua 2 Tặng 1 Bánh Kẹo & Đồ Ăn Vặt',
                'meta_description' => 'Mua 2 sản phẩm bánh kẹo được tặng 1 sản phẩm giá trị.',
            ],
            [
                'title'            => 'Hoàn Tiền 20k Cho Đơn Thanh Toán Online',
                'content'          => 'Thanh toán đơn hàng qua ví điện tử, thẻ ngân hàng hoặc chuyển khoản sẽ nhận hoàn tiền 20.000đ vào ví tài khoản. Áp dụng cho đơn từ 150.000đ trở lên. Hạn mức 1 lần hoàn/ngày/tài khoản.',
                'type'             => 2,
                'priority'         => 5,
                'tags'             => 'hoan-tien,thanh-toan-online,vi-dien-tu',
                'meta_title'       => 'Hoàn Tiền 20k Khi Thanh Toán Online',
                'meta_description' => 'Thanh toán online nhận ngay 20k hoàn vào ví tài khoản.',
            ],
            [
                'title'            => 'Giảm 50k Cho Đơn Hàng Từ 500k Trở Lên',
                'content'          => 'Nhập mã SAVE50K khi thanh toán đơn hàng từ 500.000đ trở lên để được giảm ngay 50.000đ. Không giới hạn danh mục sản phẩm, áp dụng kể cả sản phẩm đang sale. Hiệu lực đến 30/06/2026.',
                'type'             => 2,
                'priority'         => 6,
                'tags'             => 'save50k,giam-gia,don-lon',
                'meta_title'       => 'Giảm Ngay 50k Cho Đơn Từ 500k',
                'meta_description' => 'Nhập SAVE50K giảm 50k cho đơn hàng từ 500k trở lên.',
            ],
            [
                'title'            => 'Thứ Ba Vui Vẻ – Trái Cây Nhập Khẩu -25%',
                'content'          => 'Mỗi thứ Ba hàng tuần, toàn bộ danh mục trái cây nhập khẩu được giảm giá 25%. Táo Fuji, nho Mỹ, cherry, lê Hàn Quốc... Cơ hội tuyệt vời để nạp vitamin cho cả gia đình với chi phí tiết kiệm.',
                'type'             => 2,
                'priority'         => 3,
                'tags'             => 'thu-ba,trai-cay,nhap-khau,giam-25',
                'meta_title'       => 'Thứ Ba Vui Vẻ - Trái Cây Nhập Khẩu -25%',
                'meta_description' => 'Mỗi thứ Ba, trái cây nhập khẩu giảm 25% toàn bộ danh mục.',
            ],
            [
                'title'            => 'Ưu Đãi Sinh Nhật – Giảm Thêm 10% Đúng Ngày',
                'content'          => 'Vào đúng ngày sinh nhật của bạn, hệ thống sẽ tự động kích hoạt mã giảm giá 10% đặc biệt. Mã sẽ được gửi vào email đã đăng ký trước 1 ngày. Nhớ cập nhật ngày sinh nhật trong hồ sơ tài khoản nhé!',
                'type'             => 2,
                'priority'         => 4,
                'tags'             => 'sinh-nhat,uu-dai,giam-10,ca-nhan',
                'meta_title'       => 'Ưu Đãi Sinh Nhật - Giảm 10% Đúng Ngày',
                'meta_description' => 'Nhận ngay mã giảm giá 10% vào đúng ngày sinh nhật của bạn.',
            ],
        ];

        $allPosts = [];

        foreach ($banners as $banner) {
            $allPosts[] = array_merge($banner, [
                'status'       => 'published',
                'is_pinned'    => 0,
                'views'        => rand(150, 5000),
                'published_at' => $now->copy()->subDays(rand(1, 30)),
                'expires_at'   => $now->copy()->addDays(rand(30, 90)),
                'image'        => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        foreach ($promotions as $promo) {
            $allPosts[] = array_merge($promo, [
                'status'       => 'published',
                'is_pinned'    => 0,
                'views'        => rand(50, 2000),
                'published_at' => $now->copy()->subDays(rand(1, 20)),
                'expires_at'   => $now->copy()->addDays(rand(14, 60)),
                'image'        => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        DB::table('posts')->insert($allPosts);

        $this->command->info('✅ PostSeeder: Đã tạo ' . count($banners) . ' banner lớn và ' . count($promotions) . ' tin khuyến mãi nhỏ!');
    }
}
