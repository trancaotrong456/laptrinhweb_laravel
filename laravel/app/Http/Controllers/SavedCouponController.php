<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;

class SavedCouponController extends Controller
{
    /**
     * Hiển thị danh sách các voucher user đã lưu (Trang ví voucher)
     */
    public function index()
    {
        $userId = auth()->id();

        $savedCouponIds = UserSavedCoupon::where('user_id', $userId)->pluck('coupon_id');

        $coupons = Coupon::whereIn('id', $savedCouponIds)
            ->orderByDesc('id')
            ->paginate(10);

        return view('coupons.saved', compact('coupons'));
    }

    /**
     * Xử lý LƯU MÃ bằng AJAX từ trang chủ hoặc trang quản lý
     */
    public function save(Request $request, $id)
    {
        // Kiểm tra xem coupon có tồn tại và đang hoạt động không
        $coupon = Coupon::where('id', $id)->where('is_active', 1)->first();
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa.'], 442);
        }

        $userId = auth()->id();

        // Kiểm tra xem đã lưu mã này trước đó chưa để tránh trùng lặp dữ liệu
        $exists = UserSavedCoupon::where('user_id', $userId)->where('coupon_id', $id)->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Bạn đã lưu mã giảm giá này rồi.'], 400);
        }

        // Thực hiện lưu vào database
        UserSavedCoupon::create([
            'user_id' => $userId,
            'coupon_id' => $id
        ]);

        // Nếu là request gọi từ AJAX, trả về JSON thành công để Javascript xử lý đổi màu nút
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lưu mã giảm giá thành công!'
            ]);
        }

        // Tự động fallback về trang cũ nếu không phải request AJAX (đề phòng)
        return back()->with('success', 'Lưu mã giảm giá thành công!');
    }

    /**
     * Xử lý HỦY LƯU MÃ (Xóa khỏi ví voucher) bằng AJAX
     */
    public function unsave(Request $request, $id)
    {
        $userId = auth()->id();

        $savedCoupon = UserSavedCoupon::where('user_id', $userId)->where('coupon_id', $id)->first();

        if (!$savedCoupon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy mã giảm giá này trong ví của bạn.'], 404);
        }

        // Thực hiện xóa khỏi database
        $savedCoupon->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa mã giảm giá khỏi ví thành công!'
            ]);
        }

        return back()->with('success', 'Đã xóa mã giảm giá khỏi ví thành công!');
    }
}