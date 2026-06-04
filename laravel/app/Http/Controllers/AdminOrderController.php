<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminOrderController extends Controller
{
    /**
     * Danh sách tất cả đơn hàng (có lọc theo status và search)
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = trim((string) $request->get('search', ''));

        $query = Order::with(['user', 'items'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('shipping_name', 'like', "%{$search}%")
                        ->orWhere('shipping_phone', 'like', "%{$search}%")
                        ->orWhere('shipping_email', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $orders = $query->paginate(16)->appends($request->query());

        // Đếm số đơn theo từng trạng thái
        $orderCounts = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'packing'   => Order::where('status', 'packing')->count(),
            'shipping'  => Order::where('status', 'shipping')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $totalRevenue = Order::where('status', 'delivered')->sum('total');

        return view('orders.admin_index', compact(
            'orders',
            'orderCounts',
            'totalRevenue'
        ));
    }

    /**
     * Chi tiết 1 đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('orders.admin_show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validStatuses = ['pending', 'packing', 'shipping', 'delivered', 'cancelled'];
        $newStatus = $request->get('status');

        if (!in_array($newStatus, $validStatuses)) {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $order->update(['status' => $newStatus]);

        $statusLabels = [
            'pending'   => 'Đã đặt',
            'packing'   => 'Đang đóng gói',
            'shipping'  => 'Đang giao hàng',
            'delivered' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return back()->with('success', 'Cập nhật trạng thái đơn hàng sang "' . ($statusLabels[$newStatus] ?? $newStatus) . '" thành công!');
    }
}
