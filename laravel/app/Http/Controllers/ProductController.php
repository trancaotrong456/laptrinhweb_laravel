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

        // tìm kiếm theo tên sản phẩm
        $products = $this->searchByName($products, $request);

        // lọc category theo id
        $products = $this->filterByCategory($products, $request);

        // sắp xếp
        $products = $this->sortProducts($products, $request);

        // phân trang
        $products = $this->paginateProducts($products);

        $categories = Category::all();

        return view('products.admin.products.index', [
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
        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
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

        return view('products.admin.products.create', compact('categories'));
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'name' => 'required|max:255|min:3',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',

            // validate image
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.min' => 'Tên không hợp lệ.',

            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không hợp lệ.',

            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.numeric' => 'Số lượng phải là số.',
            'quantity.min' => 'Số lượng không hợp lệ.',

            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',

            'image.image' => 'File upload phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'image.max' => 'Kích thước ảnh tối đa 2MB.',
        ]);

        $imageName = null;

        // upload ảnh
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

        return redirect()
            ->route('products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    /**
     * Show detail
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('products.admin.products.show', compact('product'));
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'required|max:255|min:3',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
    
            // validate image
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.min' => 'Tên không hợp lệ.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
    
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.numeric' => 'Số lượng phải là số.',
    
            'category_id.required' => 'Vui lòng chọn danh mục.',
    
            'image.image' => 'File upload phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'image.max' => 'Kích thước ảnh tối đa 2MB.',
        ]);
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

        return redirect()->route('products.index')->with('Cập nhật sản phẩm thành công!');
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
        switch ($request->sort){
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            default :
                $products->latest();
                break;
            
        }

        return $products;
    }
}

