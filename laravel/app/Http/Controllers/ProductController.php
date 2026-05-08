<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();
        
        // tìm kiếm theo tên
        $products = $this->searchByName($products, $request);

        // lọc theo category
        $products = $this->filterByCategory($products, $request);

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

            $products->where(
                'category_id',
                $request->category
            );
        }

        return $products;
    }

    // Phân trang sản phẩm
    private function paginateProducts($products)
    {
        return $products->paginate(5);
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
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'category_id' => $request->category_id
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
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Cập nhật sản phẩm thành công');
    }
    // xoa san pham
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()
            ->route('products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }
}
