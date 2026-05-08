<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // ================= CART PAGE =================
    public function index()
    {
        $cart = Session::get('cart', []);

        // Tính tổng tiền
        $total = array_sum(array_map(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }, $cart));

        return view('cart.index', compact('cart', 'total'));
    }

    // ================= ADD =================
    public function add(Request $request)
    {
        // Validate
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $id = $request->product_id;

        // Lấy sản phẩm từ DB
        $product = Product::findOrFail($id);

        $cart = Session::get('cart', []);

        // Nếu đã có thì tăng số lượng
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Thêm mới
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price ?? 0,
                'quantity' => 1,
                'image' => $product->image ?? null
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ!',
            'totalQuantity' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        $productId = $request->product_id;
        $quantity = (int) $request->quantity;

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index');
    }

    // ================= REMOVE =================
    public function remove($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Đã xóa sản phẩm!');
    }

    // ================= CLEAR =================
    public function clear()
    {
        Session::forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Giỏ hàng đã trống!');
    }

    // ================= CHECKOUT =================
    public function checkout(Request $request)
    {
        // Validate
        $request->validate([
            'payment_method' => 'required|in:cod,bank,wallet',
            'notes' => 'nullable|string|max:500'
        ]);

        // Lấy giỏ hàng
        $cart = Session::get('cart', []);

        // Kiểm tra giỏ hàng có trống không
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống, không thể thanh toán!');
        }

        // Tính tổng tiền
        $total = array_sum(array_map(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }, $cart));

        // Lưu thông tin order (tạm thời lưu vào session, sau này tạo DB table Order)
        Session::put('order', [
            'cart' => $cart,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes ?? '',
            'customer_id' => auth()->id(),
            'created_at' => now()->toDateTimeString(),
            'status' => 'pending'
        ]);

        // Xóa giỏ hàng sau khi đặt hàng
        Session::forget('cart');

        // Chuyển hướng đến trang xác nhận đơn hàng
        return redirect()->route('order.confirmation')
            ->with('success', 'Đơn hàng được tạo thành công!');
    }

    // ================= ORDER CONFIRMATION =================
    public function orderConfirmation()
    {
        $order = Session::get('order');

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy đơn hàng!');
        }

        return view('cart.order-confirmation', compact('order'));
    }
}