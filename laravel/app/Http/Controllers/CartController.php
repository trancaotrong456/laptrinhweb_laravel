<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
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

    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã trống!');
    }
}