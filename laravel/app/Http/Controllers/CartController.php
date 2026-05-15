<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // ================= HIỂN THỊ GIỎ HÀNG =================
    public function index()
    {
        $cart = Session::get('cart', []);

        // Tính tổng tiền
        $total = array_sum(array_map(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }, $cart));

        return view('cart.index', compact('cart', 'total'));
    }

    // ================= THÊM VÀO GIỎ =================
    public function add(Request $request)
    {
        // Validate
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $id = $request->product_id;

        // Lấy sản phẩm
        $product = Product::findOrFail($id);

        // Lấy cart hiện tại
        $cart = Session::get('cart', []);

        // Nếu sản phẩm đã tồn tại
        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;

        } else {

            // Thêm mới
            $cart[$id] = [
                'name'     => $product->name,
                'price'    => $product->price ?? 0,
                'quantity' => 1,
                'image'    => $product->image ?? null
            ];
        }

        // Lưu session
        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // ================= CẬP NHẬT SỐ LƯỢNG =================
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity  = (int) $request->quantity;

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {

            $cart[$productId]['quantity'] = $quantity;
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // ================= XÓA 1 SẢN PHẨM =================
    public function remove($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {

            // Lưu item vừa xóa để undo
            Session::put('cart_last_removed', [
                'product_id' => $productId,
                'item'       => $cart[$productId],
            ]);

            // Xóa item
            unset($cart[$productId]);

            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Đã xóa sản phẩm!');
    }

    // ================= KHÔI PHỤC ITEM VỪA XÓA =================
   // ================= KHÔI PHỤC ITEM VỪA XÓA =================
public function undoRemove()
{
    $cart = Session::get('cart', []);
    $lastRemoved = Session::get('cart_last_removed');

    if ($lastRemoved) {
        $productId = $lastRemoved['product_id'];
        
        // Đưa sản phẩm trở lại giỏ
        $cart[$productId] = $lastRemoved['item'];

        Session::put('cart', $cart);
        
        // Quan trọng: Xóa dữ liệu tạm để tránh undo nhiều lần một món
        Session::forget('cart_last_removed');

        return redirect()->route('cart.index')
            ->with('success', 'Đã khôi phục sản phẩm thành công!');
    }

    return redirect()->route('cart.index')
        ->with('error', 'Không có dữ liệu để khôi phục.');
}

    // ================= XÓA TOÀN BỘ GIỎ =================
    public function clear()
    {
        Session::forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    // ================= TRANG THANH TOÁN =================
    public function checkout()
    {
        $cart = Session::get('cart', []);

        // Nếu cart rỗng
        if (empty($cart)) {

            return redirect()->route('cart.index')
                ->with('success', 'Giỏ hàng đang trống!');
        }

        // Tổng tiền
        $total = array_sum(array_map(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }, $cart));

        return view('cart.checkout', compact('cart', 'total'));
    }

    // ================= XÁC NHẬN THANH TOÁN =================
    public function confirmCheckout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {

            return redirect()->route('cart.index')
                ->with('success', 'Giỏ hàng đang trống!');
        }

        // Demo thanh toán thành công
        Session::forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Thanh toán thành công!');
    }

    // ================= THANH TOÁN ITEM ĐƯỢC CHỌN =================
    public function confirmSelected(Request $request)
    {
        $selected = $request->input('selected_products', []);

        $cart = Session::get('cart', []);

        if (empty($selected)) {

            return redirect()->route('cart.index')
                ->with('success', 'Vui lòng chọn sản phẩm!');
        }

        foreach ($selected as $productId) {

            if (isset($cart[$productId])) {

                unset($cart[$productId]);
            }
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Thanh toán thành công!');
    }
}