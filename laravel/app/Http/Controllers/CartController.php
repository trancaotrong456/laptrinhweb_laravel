<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
<<<<<<< HEAD
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
{
    $id = $request->product_id;

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            'name' => 'Sản phẩm',
            'quantity' => 1
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'message' => 'Đã thêm vào giỏ!',
        'count' => array_sum(array_column($cart, 'quantity'))
    ]);
}

    public function update(Request $request)
    {
        $productId = $request->product_id;
        $quantity = (int) $request->quantity;
        $cart = Session::get('cart', []);
        
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }
        
        Session::put('cart', $cart);
        return redirect()->route('cart.index');
    }

    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm!');
    }
//    public function clear()
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã trống!');
=======
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
>>>>>>> feature/posts
    }
}