<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserCartItem;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private const SHIPPING_FEE = 25000;
    private const FREE_SHIPPING_THRESHOLD = 300000;

    // ================= CART PAGE =================
    public function index()
    {
        $cart = $this->getCart();

        $total = $this->calculateSubtotal($cart);

        $this->maybeAutoApplyCoupon($cart, $total);

        $couponView = $this->buildCouponViewData($total);

        return view('cart.index', [

            'cart' => $cart,

            'total' => $total,

            'shippingFee' => self::SHIPPING_FEE,

            'freeShippingThreshold' => self::FREE_SHIPPING_THRESHOLD,

            'appliedCouponCode' => $couponView['code'],

            'couponDiscount' => $couponView['discount'],

            'couponMeta' => $couponView['meta'],

            'couponHint' => $couponView['hint'],
        ]);
    }

   // ================= ADD =================
public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'nullable|integer|min:1',
    ]);

    // CHƯA LOGIN
    if (!auth()->check()) {

        return response()->json([

            'success' => false,

            'message' => 'Vui lòng đăng nhập!',

            'redirect' => route('login')
        ]);
    }

    $product = Product::findOrFail(
        $request->product_id
    );

    $quantity = (int) $request->input('quantity', 1);

    if ($product->quantity <= 0) {
        $message = 'Sản phẩm hiện đã hết hàng!';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return redirect()
            ->back()
            ->with('error', $message);
    }

    if ($quantity > $product->quantity) {
        $message = 'Số lượng thêm vượt quá hàng còn lại!';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return redirect()
            ->back()
            ->with('error', $message);
    }

    $userId = auth()->id();

    $item = UserCartItem::where(
        'user_id',
        $userId
    )
    ->where(
        'product_id',
        $product->id
    )
    ->first();

    // ĐÃ TỒN TẠI
    if ($item) {

        $item->quantity += $quantity;

        $item->product_name =
            $product->name;

        $item->product_price =
            $product->price ?? 0;

        $item->product_image =
            $product->image;

        $item->save();

    } else {

        // CHƯA TỒN TẠI
        UserCartItem::create([

            'user_id' => $userId,

            'product_id' => $product->id,

            'product_name' => $product->name,

            'product_price' => $product->price ?? 0,

            'product_image' => $product->image,

            'quantity' => $quantity,
        ]);
    }

    // SYNC SESSION
    $this->syncSessionCart();

    // LẤY CART
    $cart = $this->getCart();

    // TÍNH TỔNG SỐ LƯỢNG
    $cartCount = array_sum(
        array_column($cart, 'quantity')
    );

    $message = 'Đã thêm sản phẩm vào giỏ hàng!';

    if ($request->expectsJson() || $request->ajax()) {
        return response()->json([

        'success' => true,

        'message' => 'Đã thêm sản phẩm vào giỏ hàng!',

        'cartCount' => $cartCount
    ]);

    }

    return redirect()
        ->back()
        ->with('success', $message);
}

    // ================= UPDATE =================
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $userId = auth()->id();

        $productId = (int) $request->product_id;

        $quantity = (int) $request->quantity;

        $item = UserCartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {

            if ($quantity <= 0) {

                $item->delete();

            } else {

                $item->quantity = $quantity;

                $item->save();
            }
        }

        $this->syncSessionCart();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // ================= REMOVE =================
    public function remove($productId)
    {
        $userId = auth()->id();

        $productId = (int) $productId;

        $item = UserCartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {

            Session::put('cart_last_removed', [

                'product_id' => (string) $item->product_id,

                'item' => [

                    'name' => $item->product_name,

                    'price' => (float) $item->product_price,

                    'quantity' => (int) $item->quantity,

                    'image' => $item->product_image,
                ],

                'removed_at' => now()->timestamp,
            ]);

            $item->delete();
        }

        $this->syncSessionCart();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã xóa sản phẩm!');
    }

    // ================= UNDO REMOVE =================
    public function undoRemove()
    {
        $lastRemoved = Session::get('cart_last_removed');

        if (
            !is_array($lastRemoved) ||
            !isset($lastRemoved['product_id'], $lastRemoved['item'])
        ) {

            return redirect()
                ->route('cart.index')
                ->with('error', 'Không có sản phẩm để hoàn tác.');
        }

        $userId = auth()->id();

        $productId = (int) $lastRemoved['product_id'];

        $restoredItem = $lastRemoved['item'];

        $item = UserCartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {

            $item->quantity +=
                (int) ($restoredItem['quantity'] ?? 1);

            $item->save();

        } else {

            UserCartItem::create([

                'user_id' => $userId,

                'product_id' => $productId,

                'product_name' => $restoredItem['name'] ?? 'Sản phẩm',

                'product_price' => (float) ($restoredItem['price'] ?? 0),

                'product_image' => $restoredItem['image'] ?? null,

                'quantity' => (int) ($restoredItem['quantity'] ?? 1),
            ]);
        }

        Session::forget('cart_last_removed');

        $this->syncSessionCart();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã hoàn tác sản phẩm!');
    }

    // ================= CLEAR =================
    public function clear()
    {
        UserCartItem::where(
            'user_id',
            auth()->id()
        )->delete();

        Session::forget('cart');

        Session::forget('cart_last_removed');

        return redirect()
            ->route('cart.index')
            ->with('success', 'Giỏ hàng đã được làm trống!');
    }

    // ================= APPLY COUPON =================
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $cart = $this->getCart();

        if (empty($cart)) {

            return redirect()
                ->route('cart.index')
                ->with('error', 'Giỏ hàng đang trống.');
        }

        $subtotal = $this->calculateSubtotal($cart);

        $code = strtoupper(
            trim((string) $request->coupon_code)
        );

        $coupon = Coupon::whereRaw(
            'UPPER(code) = ?',
            [$code]
        )->first();

        if (
            !$coupon ||
            !$coupon->isCurrentlyValid()
        ) {

            return redirect()
                ->route('cart.index')
                ->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        if (
            !is_null($coupon->min_order_value) &&
            $subtotal < (float) $coupon->min_order_value
        ) {

            return redirect()
                ->route('cart.index')
                ->with('error', 'Chưa đạt giá trị tối thiểu.');
        }

        Session::put('cart_coupon', [
            'code' => $coupon->code,
        ]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    // ================= REMOVE COUPON =================
    public function removeCoupon()
    {
        Session::forget('cart_coupon');

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã xóa mã giảm giá!');
    }

    // ================= CHECKOUT =================
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,bank,wallet',
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:30',
            'shipping_address' => 'required|string|max:500',
            'shipping_notes' => 'nullable|string|max:500',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'required',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $cart = $this->getCart();

        if (empty($cart)) {

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống!',
                ], 422);
            }

            return redirect()
                ->route('cart.index')
                ->with('error', 'Giỏ hàng trống!');
        }

        $selectedIds = array_map(
            'strval',
            $request->input('selected_items', [])
        );

        $selectedCart = [];

        foreach ($selectedIds as $selectedId) {

            if (isset($cart[$selectedId])) {

                $selectedCart[$selectedId] =
                    $cart[$selectedId];
            }
        }

        if (empty($selectedCart)) {

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn sản phẩm!',
                ], 422);
            }

            return redirect()
                ->route('cart.index')
                ->with('error', 'Vui lòng chọn sản phẩm!');
        }

        $subtotal =
            $this->calculateSubtotal($selectedCart);

        $couponCode = strtoupper(
            trim((string) $request->input('coupon_code', ''))
        );

        $discount = 0;

        $couponUsed = null;

        if ($couponCode !== '') {

            $couponUsed = Coupon::whereRaw(
                'UPPER(code) = ?',
                [$couponCode]
            )->first();

            if (
                !$couponUsed ||
                !$couponUsed->isCurrentlyValid()
            ) {

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá không hợp lệ.',
                    ], 422);
                }

                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Mã giảm giá không hợp lệ.');
            }

            $discount =
                $this->calculateCouponDiscount(
                    $couponUsed,
                    $subtotal
                );
        }

        $shippingFee =
            (($subtotal - $discount) >= self::FREE_SHIPPING_THRESHOLD)
            ? 0
            : self::SHIPPING_FEE;

        $grandTotal =
            max(0, $subtotal - $discount)
            + $shippingFee;

        $order = DB::transaction(function () use (
            $request,
            $selectedCart,
            $couponUsed,
            $subtotal,
            $discount,
            $shippingFee,
            $grandTotal
        ) {

            $userId = auth()->id();

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $userId,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $grandTotal,
                'coupon_code' => $couponUsed?->code,
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_notes' => $request->shipping_notes,
            ]);

            foreach ($selectedCart as $selectedProductId => $item) {
                $order->items()->create([
                    'product_id' => (int) $selectedProductId,
                    'product_name' => $item['name'] ?? 'Sản phẩm',
                    'product_price' => (float) ($item['price'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                ]);
            }

            foreach (
                array_keys($selectedCart)
                as $selectedProductId
            ) {

                UserCartItem::where('user_id', $userId)
                    ->where('product_id', (int) $selectedProductId)
                    ->delete();
            }

            if ($couponUsed) {

                $couponUsed->increment('used_count');
            }

            return $order->load('items');
        });

        Session::put('order', $order);

        if ($couponUsed) {

            Session::forget('cart_coupon');
        }

        $this->syncSessionCart();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thanh toán thành công!',
                'redirect' => route('order.confirmation'),
                'order' => [
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'shipping_fee' => $shippingFee,
                    'total' => $grandTotal,
                    'coupon_code' => $couponUsed?->code,
                ],
            ]);
        }

        return redirect()
            ->route('order.confirmation')
            ->with('success', 'Đã thanh toán thành công!');
    }

    // ================= ORDER CONFIRMATION =================
    public function orderConfirmation()
    {
        $order = Session::get('order');

        if (!$order) {

            return redirect()
                ->route('home')
                ->with('error', 'Không tìm thấy đơn hàng!');
        }

        return view(
            'cart.order-confirmation',
            compact('order')
        );
    }

    public function userOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('cart.user-orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = Order::with('items')
            ->where('user_id', auth()->id())
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('cart.order-confirmation', compact('order'));
    }

    // ================= GET CART =================
    private function getCart(): array
    {
        $items = UserCartItem::where(
            'user_id',
            auth()->id()
        )
            ->orderBy('id')
            ->get();

        $cart = [];

        foreach ($items as $item) {

            $cart[(string) $item->product_id] = [

                'name' => $item->product_name,

                'price' => (float) $item->product_price,

                'quantity' => (int) $item->quantity,

                'image' => $item->product_image,
            ];
        }

        Session::put('cart', $cart);

        return $cart;
    }

    // ================= SYNC =================
    private function syncSessionCart(): void
    {
        $this->getCart();
    }

    // ================= AUTO APPLY COUPON =================
    private function maybeAutoApplyCoupon(array $cart, float $subtotal): void
    {
        if (empty($cart) || $subtotal <= 0) {
            Session::forget('cart_coupon');
            return;
        }

        $currentCode = (string) data_get(Session::get('cart_coupon', []), 'code', '');

        if ($currentCode !== '') {
            $currentCoupon = Coupon::whereRaw(
                'UPPER(code) = ?',
                [strtoupper($currentCode)]
            )->first();

            if (
                $currentCoupon &&
                $currentCoupon->isCurrentlyValid() &&
                (is_null($currentCoupon->min_order_value) || $subtotal >= (float) $currentCoupon->min_order_value)
            ) {
                return;
            }

            Session::forget('cart_coupon');
        }

        $bestCoupon = $this->resolveBestAutoCoupon($subtotal);

        if ($bestCoupon) {
            Session::put('cart_coupon', [
                'code' => $bestCoupon->code,
            ]);
        }
    }

    // ================= BEST COUPON =================
    private function resolveBestAutoCoupon(float $subtotal): ?Coupon
    {
        $candidateCoupons = Coupon::query()
            ->where(function ($query) {
                $query->where('is_active', true)
                    ->orWhereIn('id', UserSavedCoupon::where('user_id', auth()->id())->pluck('coupon_id'));
            })
            ->get()
            ->unique('id');

        $bestCoupon = null;
        $bestDiscount = 0.0;

        foreach ($candidateCoupons as $coupon) {
            if (
                !$coupon->isCurrentlyValid() ||
                (!is_null($coupon->min_order_value) && $subtotal < (float) $coupon->min_order_value)
            ) {
                continue;
            }

            $discount = $this->calculateCouponDiscount($coupon, $subtotal);

            if ($discount <= 0) {
                continue;
            }

            if ($discount > $bestDiscount) {
                $bestDiscount = $discount;
                $bestCoupon = $coupon;
            }
        }

        return $bestCoupon;
    }

    // ================= SUBTOTAL =================
    private function calculateSubtotal(array $cart): float
    {
        return array_sum(
            array_map(function ($item) {

                return
                    ((float) ($item['price'] ?? 0))
                    *
                    ((int) ($item['quantity'] ?? 0));

            }, $cart)
        );
    }

    // ================= COUPON DISCOUNT =================
    private function calculateCouponDiscount(
        Coupon $coupon,
        float $subtotal
    ): float {

        $discount = 0;

        if ($coupon->type === 'percent') {

            $discount =
                $subtotal
                *
                ((float) $coupon->value / 100);

        } else {

            $discount =
                (float) $coupon->value;
        }

        if (!is_null($coupon->max_discount)) {

            $discount = min(
                $discount,
                (float) $coupon->max_discount
            );
        }

        return min($discount, $subtotal);
    }

    // ================= COUPON VIEW =================
    private function buildCouponViewData(float $subtotal): array
    {
        $code = (string) data_get(
            Session::get('cart_coupon', []),
            'code',
            ''
        );

        if ($code === '') {

            return [
                'code' => null,
                'discount' => 0,
                'meta' => null,
                'hint' => null,
            ];
        }

        $coupon = Coupon::whereRaw(
            'UPPER(code) = ?',
            [strtoupper($code)]
        )->first();

        if (
            !$coupon ||
            !$coupon->isCurrentlyValid()
        ) {

            Session::forget('cart_coupon');

            return [

                'code' => null,

                'discount' => 0,

                'meta' => null,

                'hint' => 'Mã giảm giá không còn hợp lệ.',
            ];
        }

        $minOrder =
            (float) ($coupon->min_order_value ?? 0);

        if (
            $minOrder > 0 &&
            $subtotal < $minOrder
        ) {

            return [

                'code' => $coupon->code,

                'discount' => 0,

                'meta' => [

                    'type' => $coupon->type,

                    'value' => (float) $coupon->value,

                    'max_discount' => $coupon->max_discount,

                    'min_order_value' => $coupon->min_order_value,
                ],

                'hint' => 'Chưa đạt giá trị tối thiểu.',
            ];
        }

        return [

            'code' => $coupon->code,

            'discount' => $this->calculateCouponDiscount(
                $coupon,
                $subtotal
            ),

            'meta' => [

                'type' => $coupon->type,

                'value' => (float) $coupon->value,

                'max_discount' => $coupon->max_discount,

                'min_order_value' => $coupon->min_order_value,
            ],

            'hint' => null,
        ];
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD' . now()->format('YmdHis') . random_int(100, 999);
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
