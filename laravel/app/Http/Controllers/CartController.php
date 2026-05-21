<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\UserCartItem;
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

        $couponView = $this->buildCouponViewData($total);

        return view('cart.index', [

            'cart' => $cart,

            'total' => $total,

            'shippingFee' => self::SHIPPING_FEE,

            'freeShippingThreshold'
                => self::FREE_SHIPPING_THRESHOLD,

            'appliedCouponCode'
                => $couponView['code'],

            'couponDiscount'
                => $couponView['discount'],

            'couponMeta'
                => $couponView['meta'],

            'couponHint'
                => $couponView['hint'],
        ]);
    }

    // ================= ADD =================
    public function add(Request $request)
    {
        $request->validate([
            'product_id'
                => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail(
            $request->product_id
        );

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

        if ($item) {

            $item->quantity += 1;

            $item->product_name
                = $product->name;

            $item->product_price
                = $product->price ?? 0;

            $item->product_image
                = $product->image;

            $item->save();

        } else {

            UserCartItem::create([

                'user_id' => $userId,

                'product_id' => $product->id,

                'product_name' => $product->name,

                'product_price'
                    => $product->price ?? 0,

                'product_image'
                    => $product->image,

                'quantity' => 1,
            ]);
        }

        $cart = $this->getCart();

        return response()->json([

            'success' => true,

            'message'
                => 'Da them vao gio!',

            'totalQuantity'
                => array_sum(
                    array_column(
                        $cart,
                        'quantity'
                    )
                ),
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request)
    {
        $request->validate([
            'product_id'
                => 'required|integer',

            'quantity'
                => 'required|integer|min:0',
        ]);

        $userId = auth()->id();

        $productId = (int) $request->product_id;

        $quantity = (int) $request->quantity;

        $item = UserCartItem::where(
            'user_id',
            $userId
        )
        ->where(
            'product_id',
            $productId
        )
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
            ->route('cart.index');
    }

    // ================= REMOVE =================
    public function remove($productId)
    {
        $userId = auth()->id();

        $productId = (int) $productId;

        $item = UserCartItem::where(
            'user_id',
            $userId
        )
        ->where(
            'product_id',
            $productId
        )
        ->first();

        if ($item) {

            Session::put(
                'cart_last_removed',
                [

                    'product_id'
                        => (string) $item->product_id,

                    'item' => [

                        'name'
                            => $item->product_name,

                        'price'
                            => (float) $item->product_price,

                        'quantity'
                            => (int) $item->quantity,

                        'image'
                            => $item->product_image,
                    ],

                    'removed_at'
                        => now()->timestamp,
                ]
            );

            $item->delete();
        }

        $this->syncSessionCart();

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Da xoa san pham!'
            );
    }

    // ================= UNDO REMOVE =================
    public function undoRemove()
    {
        $lastRemoved = Session::get(
            'cart_last_removed'
        );

        if (
            !is_array($lastRemoved)
            || !isset(
                $lastRemoved['product_id'],
                $lastRemoved['item']
            )
        ) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Khong co san pham nao de hoan tac.'
                );
        }

        $userId = auth()->id();

        $productId = (int)
            $lastRemoved['product_id'];

        $restoredItem
            = $lastRemoved['item'];

        $item = UserCartItem::where(
            'user_id',
            $userId
        )
        ->where(
            'product_id',
            $productId
        )
        ->first();

        if ($item) {

            $item->quantity += (int)
                ($restoredItem['quantity'] ?? 1);

            $item->save();

        } else {

            UserCartItem::create([

                'user_id'
                    => $userId,

                'product_id'
                    => $productId,

                'product_name'
                    => $restoredItem['name']
                    ?? 'San pham',

                'product_price'
                    => (float)
                    ($restoredItem['price'] ?? 0),

                'product_image'
                    => $restoredItem['image']
                    ?? null,

                'quantity'
                    => (int)
                    ($restoredItem['quantity'] ?? 1),
            ]);
        }

        Session::forget(
            'cart_last_removed'
        );

        $this->syncSessionCart();

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Da hoan tac san pham vua xoa.'
            );
    }

    // ================= CLEAR =================
    public function clear()
    {
        UserCartItem::where(
            'user_id',
            auth()->id()
        )->delete();

        Session::forget('cart');

        Session::forget(
            'cart_last_removed'
        );

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Gio hang da trong!'
            );
    }

    // ================= COUPON =================
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code'
                => 'required|string|max:50',
        ]);

        $cart = $this->getCart();

        if (empty($cart)) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Gio hang dang trong.'
                );
        }

        $subtotal = $this->calculateSubtotal(
            $cart
        );

        $code = strtoupper(
            trim(
                (string)
                $request->coupon_code
            )
        );

        $coupon = Coupon::whereRaw(
            'UPPER(code) = ?',
            [$code]
        )->first();

        if (
            !$coupon
            || !$coupon->isCurrentlyValid()
        ) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Ma giam gia khong hop le hoac da het han.'
                );
        }

        if (
            !is_null(
                $coupon->min_order_value
            )
            && $subtotal
                < (float)
                $coupon->min_order_value
        ) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Don hang chua dat muc toi thieu de ap dung ma nay.'
                );
        }

        Session::put(
            'cart_coupon',
            [
                'code'
                    => $coupon->code,
            ]
        );

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Ap dung ma giam gia thanh cong!'
            );
    }

    public function removeCoupon()
    {
        Session::forget(
            'cart_coupon'
        );

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Da bo ma giam gia.'
            );
    }

    // ================= CHECKOUT =================
    public function checkout(Request $request)
    {
        $request->validate([

            'payment_method'
                => 'required|in:cod,bank,wallet',

            'notes'
                => 'nullable|string|max:500',

            'selected_items'
                => 'required|array|min:1',

            'selected_items.*'
                => 'required',

            'coupon_code'
                => 'nullable|string|max:50',
        ]);

        $cart = $this->getCart();

        if (empty($cart)) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Gio hang trong, khong the thanh toan!'
                );
        }

        $selectedIds = array_map(
            'strval',
            $request->input(
                'selected_items',
                []
            )
        );

        $selectedCart = [];

        foreach (
            $selectedIds
            as $selectedId
        ) {

            if (
                isset($cart[$selectedId])
            ) {

                $selectedCart[$selectedId]
                    = $cart[$selectedId];
            }
        }

        if (empty($selectedCart)) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Vui long chon it nhat 1 san pham de thanh toan.'
                );
        }

        $subtotal = $this
            ->calculateSubtotal(
                $selectedCart
            );

        $couponCode = strtoupper(
            trim(
                (string)
                $request->input(
                    'coupon_code',
                    ''
                )
            )
        );

        $discount = 0.0;

        $couponUsed = null;

        if ($couponCode !== '') {

            $couponUsed = Coupon::whereRaw(
                'UPPER(code) = ?',
                [$couponCode]
            )->first();

            if (
                !$couponUsed
                || !$couponUsed->isCurrentlyValid()
            ) {

                return redirect()
                    ->route('cart.index')
                    ->with(
                        'error',
                        'Ma giam gia khong hop le hoac da het han.'
                    );
            }

            if (
                !is_null(
                    $couponUsed->min_order_value
                )
                && $subtotal
                    < (float)
                    $couponUsed->min_order_value
            ) {

                return redirect()
                    ->route('cart.index')
                    ->with(
                        'error',
                        'Don hang duoc chon chua dat muc toi thieu cua ma giam gia.'
                    );
            }

            $discount = $this
                ->calculateCouponDiscount(
                    $couponUsed,
                    $subtotal
                );
        }

        $shippingFee =
            (($subtotal - $discount)
            >= self::FREE_SHIPPING_THRESHOLD)
            ? 0
            : self::SHIPPING_FEE;

        $grandTotal =
            max(0, $subtotal - $discount)
            + $shippingFee;

        DB::transaction(function ()
            use (
                $selectedCart,
                $couponUsed
            ) {

            $userId = auth()->id();

            foreach (
                array_keys($selectedCart)
                as $selectedProductId
            ) {

                UserCartItem::where(
                    'user_id',
                    $userId
                )
                ->where(
                    'product_id',
                    (int) $selectedProductId
                )
                ->delete();
            }

            if ($couponUsed) {

                $couponUsed->increment(
                    'used_count'
                );
            }
        });

        Session::put(
            'order',
            [

                'cart'
                    => $selectedCart,

                'subtotal'
                    => $subtotal,

                'discount'
                    => $discount,

                'shipping_fee'
                    => $shippingFee,

                'total'
                    => $grandTotal,

                'coupon_code'
                    => $couponUsed?->code,

                'payment_method'
                    => $request->payment_method,

                'notes'
                    => $request->notes ?? '',

                'customer_id'
                    => auth()->id(),

                'created_at'
                    => now()->toDateTimeString(),

                'status'
                    => 'pending',
            ]
        );

        if ($couponUsed) {

            Session::forget(
                'cart_coupon'
            );
        }

        $this->syncSessionCart();

        return redirect()
            ->route('order.confirmation')
            ->with(
                'success',
                'Don hang duoc tao thanh cong!'
            );
    }

    // ================= ORDER CONFIRMATION =================
    public function orderConfirmation()
    {
        $order = Session::get('order');

        if (!$order) {

            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Khong tim thay don hang!'
                );
        }

        return view(
            'cart.order-confirmation',
            compact('order')
        );
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

            $cart[(string) $item->product_id]
                = [

                'name'
                    => $item->product_name,

                'price'
                    => (float)
                    $item->product_price,

                'quantity'
                    => (int)
                    $item->quantity,

                'image'
                    => $item->product_image,
            ];
        }

        Session::put(
            'cart',
            $cart
        );

        return $cart;
    }

    // ================= SYNC SESSION =================
    private function syncSessionCart(): void
    {
        $this->getCart();
    }

    // ================= SUBTOTAL =================
    private function calculateSubtotal(
        array $cart
    ): float {

        return array_sum(
            array_map(
                function ($item) {

                    return (
                        (float)
                        ($item['price'] ?? 0)
                    )
                    *
                    (
                        (int)
                        ($item['quantity'] ?? 0)
                    );
                },
                $cart
            )
        );
    }

    // ================= DISCOUNT =================
    private function calculateCouponDiscount(
        Coupon $coupon,
        float $subtotal
    ): float {

        $discount = 0.0;

        if (
            $coupon->type === 'percent'
        ) {

            $discount =
                $subtotal
                *
                (
                    (float)
                    $coupon->value / 100
                );

        } else {

            $discount =
                (float)
                $coupon->value;
        }

        if (
            !is_null(
                $coupon->max_discount
            )
        ) {

            $discount = min(
                $discount,
                (float)
                $coupon->max_discount
            );
        }

        return min(
            $discount,
            $subtotal
        );
    }

    // ================= COUPON VIEW =================
    private function buildCouponViewData(
        float $subtotal
    ): array {

        $code = (string)
            data_get(
                Session::get(
                    'cart_coupon',
                    []
                ),
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
            !$coupon
            || !$coupon->isCurrentlyValid()
        ) {

            Session::forget(
                'cart_coupon'
            );

            return [

                'code' => null,

                'discount' => 0,

                'meta' => null,

                'hint'
                    => 'Ma giam gia khong con hop le.',
            ];
        }

        $minOrder = (float)
            ($coupon->min_order_value ?? 0);

        if (
            $minOrder > 0
            && $subtotal < $minOrder
        ) {

            return [

                'code'
                    => $coupon->code,

                'discount'
                    => 0,

                'meta' => [

                    'type'
                        => $coupon->type,

                    'value'
                        => (float)
                        $coupon->value,

                    'max_discount'
                        => $coupon->max_discount,

                    'min_order_value'
                        => $coupon->min_order_value,
                ],

                'hint'
                    => 'Don hang chua dat gia tri toi thieu de an ma.',
            ];
        }

        return [

            'code'
                => $coupon->code,

            'discount'
                => $this->calculateCouponDiscount(
                    $coupon,
                    $subtotal
                ),

            'meta' => [

                'type'
                    => $coupon->type,

                'value'
                    => (float)
                    $coupon->value,

                'max_discount'
                    => $coupon->max_discount,

                'min_order_value'
                    => $coupon->min_order_value,
            ],

            'hint' => null,
        ];
    }
}