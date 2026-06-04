<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderCategoryIndex($request);
    }

    public function doUong(Request $request)
    {
        return $this->renderCategoryIndex($request, 'do_uong');
    }

    public function thucPham(Request $request)
    {
        return $this->renderCategoryIndex($request, 'thuc_pham');
    }

    public function giaDung(Request $request)
    {
        return $this->renderCategoryIndex($request, 'gia_dung');
    }

    private function renderCategoryIndex(Request $request, ?string $type = null)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'newest');
        $hasTypeColumn = Schema::hasColumn('categories', 'type');
        $hasParentColumn = Schema::hasColumn('categories', 'parent_id');

        $query = Category::query()->withCount('products');

        // Lọc theo loại danh mục (nếu có)
        if ($type && $hasTypeColumn) {
            $query->where('type', $type);
        }

        // Tìm kiếm theo tên hoặc mô tả
        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Sắp xếp dữ liệu bằng match expression
        match ($sort) {
            'oldest' => $query->oldest(),
            'quantity' => $query->orderBy('products_count', 'desc'),
            default => $query->latest(),
        };

        $categories = $query->paginate(10)->appends($request->query());

        // Kiểm tra xem là admin và đang ở giao diện quản lý hay không
        if (auth()->check() && auth()->user()->role === 1 && $request->has('manage')) {
            return view(
                'categories.index',
                compact('categories', 'search', 'sort', 'hasParentColumn')
            );
        }

        return view(
            'categories.front',
            compact('categories', 'search', 'sort', 'hasParentColumn')
        );
    }

    public function create()
    {
        // GIẢI PHÁP TRIỆT ĐỂ: Tạo một Collection rỗng để "chữa cháy" 
        // phòng trường hợp View cũ hoặc View Cache vẫn cố tìm biến $parents
        $parents = collect();

        return view('categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:categories,name',
            'type' => 'nullable|in:do_uong,thuc_pham,gia_dung',
            'description' => 'nullable'
        ];

        $request->validate($rules);

        try {
            Category::create($this->categoryData($request));

            $redirectRoute = $this->getRedirectRoute($this->resolveType($request));

            return redirect()
                ->route($redirectRoute)
                ->with('success', 'Thêm danh mục "' . $request->name . '" thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi thêm danh mục. Vui lòng thử lại.']);
        }
    }

    public function show($id)
    {
        // TỐI ƯU: Bỏ đếm thừa withCount() vì bên dưới hàm paginate() đã tự đếm tổng số
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate(10);

        return view('categories.show', compact('category', 'products'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        // GIẢI PHÁP TRIỆT ĐỂ: Truyền tương tự cho trang sửa đề phòng view cũ chưa cập nhật
        $parents = collect();
        
        return view('categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $rules = [
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'type' => 'nullable|in:do_uong,thuc_pham,gia_dung',
            'description' => 'nullable'
        ];

        $request->validate($rules);

        try {
            $category->update($this->categoryData($request));

            $redirectRoute = $this->getRedirectRoute($category->fresh()->type ?? null);

            return redirect()
                ->route($redirectRoute)
                ->with('success', 'Cập nhật danh mục "' . $request->name . '" thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật danh mục. Vui lòng thử lại.']);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $type = $category->type;
            $categoryName = $category->name;

            // TỐI ƯU: Ép đếm trực tiếp từ Database thay vì tải Collection lên RAM để đếm
            $productCount = $category->products()->count();

            if ($productCount > 0) {
                return back()->withErrors([
                    'error' => 'Không thể xóa danh mục "' . $categoryName . '" vì còn ' . 
                                $productCount . ' sản phẩm. Vui lòng chuyển sản phẩm sang danh mục khác trước.'
                ]);
            }

            $category->delete();

            $redirectRoute = $this->getRedirectRoute($type);

            return redirect()
                ->route($redirectRoute)
                ->with('success', 'Xóa danh mục "' . $categoryName . '" thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa danh mục. Vui lòng thử lại.']);
        }
    }

    public function searchSuggestions(Request $request)
    {
        $search = $request->input('q', '');

        if (strlen($search) < 1) {
            return response()->json([]);
        }

        $suggestions = Category::query()
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->limit(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'description' => $category->description
                        ? Str::limit($category->description, 50)
                        : 'Không có mô tả'
                ];
            });

        return response()->json($suggestions);
    }

    private function categoryData(Request $request): array
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description
        ];

        if (Schema::hasColumn('categories', 'slug')) {
            $data['slug'] = Str::slug($request->name);
        }

        if (Schema::hasColumn('categories', 'type')) {
            $data['type'] = $this->resolveType($request);
        }

        return $data;
    }

    private function resolveType(Request $request): ?string
    {
        // If admin specified a type use it; otherwise return null so redirect goes to the full list
        if ($request->filled('type')) {
            return $request->type;
        }

        return null;
    }

    private function getRedirectRoute($type)
    {
        return match ($type) {
            'do_uong' => 'categories.do_uong',
            'thuc_pham' => 'categories.thuc_pham',
            'gia_dung' => 'categories.gia_dung',
            default => 'categories.index',
        };
    }
}