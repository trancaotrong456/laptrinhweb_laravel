<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'newest');

        $query = Category::withCount('products');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($sort === 'newest') {
            $query->latest('id');
        } elseif ($sort === 'oldest') {
            $query->oldest('id');
        } elseif ($sort === 'quantity') {
            $query->orderBy('products_count', 'desc');
        }

        $categories = $query->paginate(10)->appends($request->query());

        return view('categories.index', compact('categories', 'search', 'sort'));
    }

    public function searchSuggestions(Request $request)
    {
        $q = $request->get('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }
        $categories = Category::where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(10)
            ->get(['name', 'description']);
        return response()->json($categories);
    }

    public function create()
    {
        // Không cần truyền $parents nữa
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            // Bỏ parent_id
        ]);

        Category::create($request->only(['name', 'description']));

        return redirect()->route('categories.index')
                         ->with('success', 'Thêm danh mục "' . $request->name . '" thành công!');
    }

    public function show(Category $category)
    {
        $products = $category->products()->paginate(10);
        return view('categories.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        // Không cần truyền $parents
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($request->only(['name', 'description']));

        return redirect()->route('categories.index')
                         ->with('success', 'Cập nhật danh mục "' . $request->name . '" thành công!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->withErrors([
                'error' => 'Không thể xóa danh mục "' . $category->name . '" vì còn ' .
                          $category->products()->count() . ' sản phẩm.'
            ]);
        }

        $category->delete();
        return redirect()->route('categories.index')
                         ->with('success', 'Xóa danh mục "' . $category->name . '" thành công!');
    }
}