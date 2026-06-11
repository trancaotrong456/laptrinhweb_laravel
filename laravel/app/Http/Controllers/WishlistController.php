<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function toggle(Product $product)
    {
        $wishlist = Wishlist::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'product_id',
            $product->id
        )
        ->first();

        if ($wishlist) {

            $wishlist->delete();

            return back()->with(
                'success',
                'Đã bỏ khỏi danh sách yêu thích'
            );
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with(
            'success',
            'Đã thêm vào danh sách yêu thích'
        );
    }

    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view(
            'wishlist.index',
            compact('wishlists')
        );
    }
}