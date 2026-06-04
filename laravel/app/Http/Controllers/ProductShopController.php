<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\UserCartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductShopController extends Controller
{
    public function all(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $keyword = $request->string('keyword')->trim();
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->string('category'));
        }

        $products = $query
            ->orderByDesc('id')
            ->paginate(12);

        return view('products.all', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'reviews' => function ($query) {
            $query->latest();
        }]);

        $reviews = $product->reviews;
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        $ratingCounts = [];
        for ($rating = 5; $rating >= 1; $rating--) {
            $ratingCounts[$rating] = $reviews
                ->where('rating', $rating)
                ->count();
        }

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, function ($query) use ($product) {
                $query->where('category_id', $product->category_id);
            })
            ->orderByDesc('id')
            ->take(4)
            ->get();

        return view('products.detail', compact(
            'product',
            'reviews',
            'reviewCount',
            'averageRating',
            'ratingCounts',
            'relatedProducts'
        ));
    }

    public function storeReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
            ],
            [
                'user_name' => auth()->user()->name,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return redirect()
            ->route('products.detail', $product)
            ->with('success', 'Cảm ơn bạn đã bình luận và đánh giá sản phẩm!');
    }

    public function buyNow(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);

        if ($product->quantity <= 0) {
            return redirect()
                ->route('products.detail', $product)
                ->with('error', 'Sản phẩm hiện đã hết hàng.');
        }

        if ($quantity > $product->quantity) {
            return redirect()
                ->route('products.detail', $product)
                ->with('error', 'Số lượng mua vượt quá hàng còn lại.');
        }

        $this->addProductToCart($product, $quantity);
        $this->syncSessionCart();

        return redirect()
            ->route('checkout.index')
            ->with('success', 'Sản phẩm đã sẵn sàng để thanh toán!');
    }

    private function addProductToCart(Product $product, int $quantity): void
    {
        $item = UserCartItem::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $item->product_name = $product->name;
        $item->product_price = $product->price ?? 0;
        $item->product_image = $product->image;
        $item->quantity = ((int) ($item->quantity ?? 0)) + $quantity;
        $item->save();
    }

    private function syncSessionCart(): void
    {
        $items = UserCartItem::where('user_id', auth()->id())
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
    }
}
