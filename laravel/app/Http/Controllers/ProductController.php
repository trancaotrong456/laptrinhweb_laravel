<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();
        
        // tìm kiếm theo tên
        $products = $this->searchByName($products, $request);

        // lọc theo category
        $products = $this->filterByCategory($products, $request);

        //sap xep
        $products = $this->sortProducts($products, $request);

        // phân trang
        $products = $this->paginateProducts($products);

        $categories = Category::all();

        //thong bao search
        $message = null;

        if ($request->keyword){
            $message = 'Tìm thấy '. $products->total() . ' Kết quả tìm kiếm cho: ' . $request->keyword;
        }
        return view('products.index', compact(
            'products',
            'categories',
            'message'
        ));
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

            $products->whereHas('category', function($query) use ($request){
                $query->where('name', $request->category);
            });
        }

        return $products;
    }

    // Phân trang sản phẩm
    private function paginateProducts($products)
    {
        return $products->paginate(5)->appends(request()->query());
    }
    //show form them san pham
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
    //luu san pham moi vao db
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
            $imageName = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'category_id' => $request->category_id,
            'status' => $request->quantity > 0 ? 'Còn hàng' : 'Hết hàng'
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }
    //show form edit san pham
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }
    //cap nhat san pham
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $imageName = $product->image;

        if ($request->hasFile('image')) {
            //xoa anh cu
            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
            $imageName = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'status' => $request->quantity > 0 ? 'Còn hàng' : 'Hết hàng'
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Cập nhật sản phẩm thành công');
    }
    // xoa san pham
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        //xoa anh
        if($product->image){
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }

    //chi tiet san pham
    public function show($id){
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }
    //sap xep san pham
    public function sortProducts($products, $request){
        if($request->sort == 'price_asc'){
            $products->orderBy('price', 'asc');
        }
        elseif ($request->sort == 'price_desc'){
            $products->orderBy('price', 'desc');
        }
        elseif ($request->sort == 'latest'){
            $products->latest();
        }
        return $products;
    }
}
