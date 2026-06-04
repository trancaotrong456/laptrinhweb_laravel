<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category');
        $products = $this->searchByName($products, $request);
        $products = $this->filterByCategory($products, $request);
        $products = $this->sortProducts($products, $request);
        $products = $this->paginateProducts($products);

        $categories = Category::all();

        return view('products.index', [
            'products' => $products,
            'keyword' => $request->keyword,
            'categories' => $categories,
            'category_id' => $request->category_id,
        ]);
    }

    private function searchByName($products, Request $request)
    {
        if ($request->filled('keyword')) {
            $products->where('name', 'like', '%' . $request->keyword . '%');
        }

        return $products;
    }

    private function filterByCategory($products, Request $request)
    {
        if ($request->filled('category')) {
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }

        return $products;
    }

    private function paginateProducts($products)
    {
        return $products->paginate(5)->appends(request()->query());
    }

    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'image|mimes:jpg,png,jpeg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $imageName = $request->hasFile('image')
            ? $this->storeImage($request)
            : null;

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->quantity > 0 ? 'Con hang' : 'Het hang',
        ]);

        return redirect()->route('products.index');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'image|mimes:jpg,png,jpeg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $imageName = $product->image;

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $imageName = $this->storeImage($request);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->quantity > 0 ? 'Con hang' : 'Het hang',
        ]);

        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->deleteImage($product->image);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Xoa san pham thanh cong');
    }

    public function sortProducts($products, Request $request)
    {
        if ($request->sort === 'price_asc') {
            $products->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $products->orderBy('price', 'desc');
        } elseif ($request->sort === 'latest') {
            $products->latest();
        }

        return $products;
    }

    public function searchSuggest(Request $request)
    {
        $keyword = (string) $request->keyword;

        $products = Product::where('name', 'LIKE', "%{$keyword}%")
            ->limit(8)
            ->get();

        return response()->json($products);
    }

    private function storeImage(Request $request): string
    {
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $targetDirectory = public_path('images');

        if (!File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        // Fix for Windows OneDrive Read-only attribute bug where is_writable() returns false
        $targetPath = $targetDirectory . DIRECTORY_SEPARATOR . $imageName;
        copy($image->getRealPath(), $targetPath);
        @unlink($image->getRealPath());

        return $imageName;
    }

    private function deleteImage(?string $imagePath): void
    {
        if (!$imagePath) {
            return;
        }

        if (str_contains($imagePath, '/')) {
            Storage::disk('public')->delete($imagePath);
            return;
        }

        File::delete(public_path('images/' . $imagePath));
    }
}
