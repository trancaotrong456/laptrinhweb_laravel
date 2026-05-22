<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('category');

        // tìm kiếm
        $products = $this->searchByName($products, $request);

        // lọc category
        $products = $this->filterByCategory($products, $request);

        // sắp xếp
        $products = $this->sortProducts($products, $request);

        // phân trang
        $products = $this->paginateProducts($products);

        $categories = Category::all();

        return view('products.index', [
            'products' => $products,
            'keyword' => $request->keyword,
            'categories' => $categories,
            'category_id' => $request->category_id
        ]);
    }

    // Tìm kiếm theo tên
    private function searchByName($products, $request)
    {
        if ($request->keyword) {
            $products->where(
                'name',
                'like',
                '%' . $request->keyword . '%'
            );
        }

        return $products;
    }

    // Lọc theo category
    private function filterByCategory($products, $request)
    {
        if ($request->category) {

            $products->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }

        return $products;
    }

    // Phân trang
    private function paginateProducts($products)
    {
        return $products->paginate(5)->appends(request()->query());
    }

    /**
     * Show form create
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = $request
                ->file('image')
                ->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'category_id' => $request->category_id,
            'status' => $request->quantity > 0
                ? 'Còn hàng'
                : 'Hết hàng'
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Show detail
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $categories = Category::all();

        return view(
            'products.edit',
            compact('product', 'categories')
        );
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $imageName = $product->image;

        if ($request->hasFile('image')) {

            // xóa ảnh cũ
            if ($product->image) {
                Storage::disk('public')
                    ->delete($product->image);
            }

            $imageName = $request
                ->file('image')
                ->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'category_id' => $request->category_id,
            'status' => $request->quantity > 0
                ? 'Còn hàng'
                : 'Hết hàng'
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // xóa ảnh
        if ($product->image) {
            Storage::disk('public')
                ->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }

    // Sắp xếp
    public function sortProducts($products, $request)
    {
        if ($request->sort == 'price_asc') {
            $products->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $products->orderBy('price', 'desc');
        } elseif ($request->sort == 'latest') {
            $products->latest();
        }

        return $products;
    }
}