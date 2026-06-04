<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\UserCartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductShopController extends Controller
{
    public function all(Request $request)
    {
        $products = Product::query();

         // tìm kiếm theo tên
        $products = $this->searchByName(
            $products,
            $request
        );

         // lọc danh mục
        $products = $this->filterByCategories(
            $products,
            $request
        );
        // lọc giá
        $products = $this->filterByPrice(
            $products,
            $request
        );
        // lọc còn hàng
        $products = $this->filterByStock(
            $products,
            $request
        );
        // sắp xếp
        $products = $this->sortProducts(
            $products,
            $request
        );
        $products = $products
        ->with('category')
        ->paginate(12)
        ->withQueryString();
        $categories = Category::all();
        return view('products.all', compact('products', 'categories'));
    }
    private function searchByName($products, $request)
    {
        if ($request->filled('keyword')) {

            $products->where(
                'name',
                'like',
                '%' . $request->keyword . '%'
            );
        }

        return $products;
    }
    private function sortProducts($products, $request)
    {
        switch ($request->sort) {

            case 'price_asc':
                $products->orderBy(
                    'price',
                    'asc'
                );
                break;

            case 'price_desc':
                $products->orderBy(
                    'price',
                    'desc'
                );
                break;

            case 'latest':
                $products->latest();
                break;

            default:
                $products->latest();
                break;
        }

        return $products;
    }
    private function filterByCategories($products, $request)
    {
        $categories = $request->category_id;

        if (!empty($categories)) {

            $products->whereIn(
                'category_id',
                $categories
            );
        }

        return $products;
    }
    private function filterByPrice($products, $request){
        if($request->filled('min_price')){
            $products->where('price', '>=', $request->min_price);
        }
        if($request->filled('max_price')){
            $products->where('price', '<=', $request->max_price);
        }
        return $products;
    }
    private function filterByStock($products, $request)
    {
        if ($request->filled('in_stock')) {
    
            $products->where(
                'quantity',
                '>',
                0
            );
        }
    
        return $products;
    }

    public function show(Product $product)
    {
        $this->loadProductRelations($product);

        $reviews = $product->reviews;
    
        $reviewData = $this->getReviewStatistics($reviews);
    
        $relatedProducts = $this->getRelatedProducts($product);

        return view('products.detail', [
            'product' => $product,
            'reviews' => $reviews,
            'reviewCount' => $reviewData['reviewCount'],
            'averageRating' => $reviewData['averageRating'],
            'ratingCounts' => $reviewData['ratingCounts'],
            'relatedProducts' => $relatedProducts,
        ]);
    }
    private function loadProductRelations(Product $product)
    {
        $product->load([
            'category',
            'reviews' => function ($query) {
                $query->latest();
            }
        ]);
    }
    private function getReviewStatistics($reviews)
    {
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

        return [
            'reviewCount' => $reviewCount,
            'averageRating' => $averageRating,
            'ratingCounts' => $ratingCounts,
        ];
    }
    private function getRelatedProducts(Product $product)
    {
        return Product::query()
            ->where('id', '!=', $product->id)
            ->when(
                $product->category_id,
                function ($query) use ($product) {

                    $query->where(
                        'category_id',
                        $product->category_id
                    );
                }
            )
            ->latest()
            ->take(4)
            ->get();
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