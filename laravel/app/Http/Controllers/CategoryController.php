<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function doUong()
    {
        $doUongs = Category::where('type', 'do_uong')->latest()->get();

        return view('categories.douong', compact('doUongs'));
    }

    public function thucPham()
    {
        $thucPhams = Category::where('type', 'thuc_pham')->latest()->get();

        return view('categories.thucpham', compact('thucPhams'));
    }

    public function giaDung()
    {
        $giaDungs = Category::where('type', 'gia_dung')->latest()->get();

        return view('categories.giadung', compact('giaDungs'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
            'type' => 'required|in:do_uong,thuc_pham,gia_dung',
            'description' => 'nullable'
        ]);

        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return view('categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'description' => 'nullable'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }
}