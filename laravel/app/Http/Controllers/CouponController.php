<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderByDesc('id')->paginate(10);

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateCoupon($request);
        $data = $this->normalizeCouponPayload($request, $data);
        $data['code'] = strtoupper($data['code']);

        Coupon::create($data);

        return redirect()->route('coupons.index')->with('success', 'Created coupon successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateCoupon($request, $coupon->id);
        $data = $this->normalizeCouponPayload($request, $data);
        $data['code'] = strtoupper($data['code']);

        $coupon->update($data);

        return redirect()->route('coupons.index')->with('success', 'Updated coupon successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Deleted coupon successfully.');
    }

    public function saveForUser(Coupon $coupon)
    {
        if (! $coupon->isCurrentlyValid()) {
            return redirect()->route('home')->with('error', 'Coupon is not valid right now.');
        }

        UserSavedCoupon::firstOrCreate([
            'user_id' => auth()->id(),
            'coupon_id' => $coupon->id,
        ]);

        return redirect()->route('home')->with('success', 'Saved coupon to your account.');
    }

    public function unsaveForUser(Coupon $coupon)
    {
        UserSavedCoupon::where('user_id', auth()->id())
            ->where('coupon_id', $coupon->id)
            ->delete();

        return redirect()->route('home')->with('success', 'Removed saved coupon.');
    }

    private function validateCoupon(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:coupons,code';

        if ($ignoreId) {
            $uniqueRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'code' => ['required', 'string', 'max:100', $uniqueRule],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function normalizeCouponPayload(Request $request, array $data): array
    {
        $data['is_active'] = $request->boolean('is_active');

        foreach (['min_order_value', 'max_discount', 'starts_at', 'ends_at', 'usage_limit'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '') {
                $data[$field] = null;
            }
        }

        return $data;
    }
}
