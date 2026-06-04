<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;

class SavedCouponController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $savedCouponIds = UserSavedCoupon::where('user_id', $userId)->pluck('coupon_id');

        $coupons = Coupon::whereIn('id', $savedCouponIds)
            ->orderByDesc('id')
            ->paginate(10);

        return view('coupons.saved', compact('coupons'));
    }
}