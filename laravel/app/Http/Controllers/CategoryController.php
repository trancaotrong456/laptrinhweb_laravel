<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Category::withCount('products');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $categories = $query->latest()->paginate(10)->appends($request->query());

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            Category::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return redirect()->route('categories.index')
                           ->with('success', 'Thêm danh mục "' . $request->name . '" thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Có lỗi xảy ra khi thêm danh mục. Vui lòng thử lại.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $products = $category->products()->paginate(10);
        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return redirect()->route('categories.index')
                           ->with('success', 'Cập nhật danh mục "' . $request->name . '" thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật danh mục. Vui lòng thử lại.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Kiểm tra xem danh mục có sản phẩm không
            if ($category->products()->count() > 0) {
                return back()->withErrors([
                    'error' => 'Không thể xóa danh mục "' . $category->name . '" vì còn ' .
                              $category->products()->count() . ' sản phẩm. Vui lòng chuyển sản phẩm sang danh mục khác trước.'
                ]);
            }

            $categoryName = $category->name;
            $category->delete();

            return redirect()->route('categories.index')
                           ->with('success', 'Xóa danh mục "' . $categoryName . '" thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa danh mục. Vui lòng thử lại.']);
        }
    }
}
