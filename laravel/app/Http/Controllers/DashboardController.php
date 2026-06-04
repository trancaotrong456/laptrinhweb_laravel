<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | CẤU HÌNH CỘT TỔNG TIỀN
        |--------------------------------------------------------------------------
        | 🛑 Hãy đổi 'total_amount' thành tên cột lưu tổng tiền thực tế trong bảng 
        | 'orders' của bạn nếu bạn đặt tên khác (ví dụ: total_price, grand_total, amount...)
        */
        $totalColumn = 'total';
        $hasOrdersTable = Schema::hasTable('orders');
        $hasOrderItemsTable = Schema::hasTable('order_items');

        /*
        |--------------------------------------------------------------------------
        | THỐNG KÊ TỔNG QUAN
        |--------------------------------------------------------------------------
        */
        $ordersToday = $hasOrdersTable
            ? Order::whereDate('created_at', Carbon::today())->count()
            : 0;

        $revenueThisMonth = $hasOrdersTable ? Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum($totalColumn) : 0;

        $totalUsers = User::count();

        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $totalPosts = Post::count();

        $totalCoupons = Coupon::count();

        $pendingOrdersCount = $hasOrdersTable
            ? Order::where('status', 'pending')->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | BIỂU ĐỒ DOANH THU 14 NGÀY (Tối ưu: Chỉ chạy 1 câu SQL gom nhóm dữ liệu)
        |--------------------------------------------------------------------------
        */
        $labels14Days = [];
        $revenue14Days = [];

        // Lấy tất cả doanh thu 14 ngày qua chỉ với 1 query duy nhất
        $rawRevenue14Days = $hasOrdersTable
            ? Order::where('status', 'delivered')
                ->whereDate('created_at', '>=', Carbon::today()->subDays(13))
                ->selectRaw('DATE(created_at) as date, SUM(' . $totalColumn . ') as total_revenue')
                ->groupBy('date')
                ->pluck('total_revenue', 'date')
                ->toArray()
            : [];

        // Đổ dữ liệu vào mảng theo đúng thứ tự thời gian tăng dần
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->toDateString(); // Định dạng Y-m-d

            $labels14Days[] = $date->format('d/m');
            $revenue14Days[] = $rawRevenue14Days[$dateString] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | TOP 5 SẢN PHẨM BÁN CHẠY
        |--------------------------------------------------------------------------
        */
        $topProducts = collect();

        if ($hasOrdersTable && $hasOrderItemsTable && class_exists(OrderItem::class)) {
            $topProducts = OrderItem::selectRaw('
                    product_id,
                    product_name,
                    product_price,
                    SUM(quantity) as total_sold
                ')
                ->whereHas('order', function ($query) {
                    $query->where('status', '!=', 'cancelled');
                })
                ->groupBy('product_id', 'product_name', 'product_price')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            // Lazy Eager Loading ảnh sản phẩm để tránh N+1 Query
            $topProducts->load(['product' => function ($query) {
                $query->select('id', 'image');
            }]);

            foreach ($topProducts as $item) {
                $item->image = $item->product->image ?? null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ĐƠN HÀNG GẦN ĐÂY
        |--------------------------------------------------------------------------
        */
        $recentOrders = $hasOrdersTable
            ? Order::with('user')
                ->latest()
                ->take(6)
                ->get()
            : collect();

        /*
        |--------------------------------------------------------------------------
        | DOANH THU THEO DANH MỤC (Tối ưu: Tính toán trực tiếp bằng SQL JOIN)
        |--------------------------------------------------------------------------
        */
        $categoryRevenue = [];

        if ($hasOrdersTable && $hasOrderItemsTable && class_exists(OrderItem::class)) {
            // Dùng Query Builder để tính toán trực tiếp từ database thay vì kéo hàng ngàn bản ghi vào RAM PHP
            $categoryRevenue = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('orders.status', 'delivered')
                ->selectRaw('COALESCE(categories.name, "Khác") as cat_name, SUM(order_items.product_price * order_items.quantity) as revenue')
                ->groupBy('cat_name')
                ->orderByDesc('revenue')
                ->pluck('revenue', 'cat_name')
                ->toArray();
        }

        $totalDeliveredRevenue = array_sum($categoryRevenue);

        /*
        |--------------------------------------------------------------------------
        | TRẢ VỀ VIEW
        |--------------------------------------------------------------------------
        */
        return view('crud_user.dashboard', compact(
            'ordersToday',
            'revenueThisMonth',
            'totalUsers',
            'totalProducts',
            'totalCategories',
            'totalPosts',
            'totalCoupons',
            'pendingOrdersCount',
            'labels14Days',
            'revenue14Days',
            'topProducts',
            'recentOrders',
            'categoryRevenue',
            'totalDeliveredRevenue'
        ));
    }
}
