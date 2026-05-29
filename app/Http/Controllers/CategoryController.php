<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CATEGORY LIST
    |--------------------------------------------------------------------------
    */

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

        $hasParentColumn = $this->hasParentColumn();
        $hasTypeColumn = Schema::hasColumn('categories', 'type');

        $query = Category::query();

        /*
        |--------------------------------------------------------------------------
        | WITH PRODUCTS COUNT
        |--------------------------------------------------------------------------
        */

        if (method_exists(Category::class, 'products')) {
            $query->withCount('products');
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TYPE
        |--------------------------------------------------------------------------
        */

        if ($type && $hasTypeColumn) {
            $query->where('type', $type);
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD CHILDREN
        |--------------------------------------------------------------------------
        */

        if ($hasParentColumn) {
            $query
                ->whereNull('parent_id')
                ->with([
                    'children' => function ($query) {
                        if (method_exists(Category::class, 'products')) {
                            $query->withCount('products');
                        }

                        $query->orderBy('name');
                    }
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search, $hasParentColumn) {

                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');

                if ($hasParentColumn) {
                    $query->orWhereHas('children', function ($query) use ($search) {
                        $query
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */

        match ($sort) {
            'oldest' => $query->oldest(),

            'quantity' => $query->orderBy(
                'products_count',
                'desc'
            ),

            default => $query->latest(),
        };

        $categories = $query
            ->paginate(10)
            ->appends($request->query());

        return view('categories.index', [
            'categories' => $categories,
            'search' => $search,
            'sort' => $sort,
            'hasParentColumn' => $hasParentColumn,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $parents = collect();

        if ($this->hasParentColumn()) {
            $parents = Category::whereNull('parent_id')
                ->orderBy('name')
                ->get();
        }

        return view('categories.create', compact('parents'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:categories,name',
            'description' => 'nullable',
        ];

        if (Schema::hasColumn('categories', 'type')) {
            $rules['type'] =
                'required|in:do_uong,thuc_pham,gia_dung';
        }

        if ($this->hasParentColumn()) {
            $rules['parent_id'] =
                'nullable|exists:categories,id';
        }

        $request->validate($rules);

        Category::create(
            $this->categoryData($request)
        );

        $redirectRoute = $this->getRedirectRoute(
            $this->resolveType($request)
        );

        return redirect()
            ->route($redirectRoute)
            ->with(
                'success',
                'Thêm danh mục thành công!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $query = Category::query();

        if (method_exists(Category::class, 'products')) {
            $query->withCount('products');
        }

        $category = $query->findOrFail($id);

        $products = collect();

        if (method_exists($category, 'products')) {
            $products = $category->products()->paginate(10);
        }

        return view(
            'categories.show',
            compact('category', 'products')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $query = Category::query();

        if (method_exists(Category::class, 'products')) {
            $query->withCount('products');
        }

        $category = $query->findOrFail($id);

        $parents = collect();

        if ($this->hasParentColumn()) {
            $parents = Category::whereNull('parent_id')
                ->where('id', '!=', $category->id)
                ->orderBy('name')
                ->get();
        }

        return view(
            'categories.edit',
            compact('category', 'parents')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $rules = [
            'name' =>
                'required|max:255|unique:categories,name,' . $id,

            'description' => 'nullable',
        ];

        if (Schema::hasColumn('categories', 'type')) {
            $rules['type'] =
                'required|in:do_uong,thuc_pham,gia_dung';
        }

        if ($this->hasParentColumn()) {
            $rules['parent_id'] =
                'nullable|exists:categories,id';
        }

        $request->validate($rules);

        /*
        |--------------------------------------------------------------------------
        | CHECK SELF PARENT
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasParentColumn() &&
            (int) $request->parent_id === (int) $category->id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'parent_id' =>
                        'Danh mục cha không thể là chính nó.'
                ]);
        }

        $category->update(
            $this->categoryData($request)
        );

        $redirectRoute = $this->getRedirectRoute(
            $category->fresh()->type ?? null
        );

        return redirect()
            ->route($redirectRoute)
            ->with(
                'success',
                'Cập nhật danh mục thành công!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $type = $category->type ?? null;

        /*
        |--------------------------------------------------------------------------
        | CHECK PRODUCTS
        |--------------------------------------------------------------------------
        */

        if (
            method_exists($category, 'products') &&
            $category->products()->count() > 0
        ) {
            return back()->withErrors([
                'error' =>
                    'Không thể xóa danh mục đang có sản phẩm.'
            ]);
        }

        $category->delete();

        $redirectRoute = $this->getRedirectRoute($type);

        return redirect()
            ->route($redirectRoute)
            ->with(
                'success',
                'Xóa danh mục thành công!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH SUGGESTIONS
    |--------------------------------------------------------------------------
    */

    public function searchSuggestions(Request $request)
    {
        $search = $request->input('q', '');

        if (strlen($search) < 1) {
            return response()->json([]);
        }

        $query = Category::query()
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');

        if ($this->hasParentColumn()) {

            $query
                ->with('parent')

                ->orWhereHas('parent', function ($query) use ($search) {

                    $query
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere(
                            'description',
                            'like',
                            '%' . $search . '%'
                        );
                });
        }

        $suggestions = $query
            ->limit(10)
            ->get()
            ->map(function ($category) {

                $label = $category->name;

                if (
                    $this->hasParentColumn() &&
                    $category->parent
                ) {
                    $label =
                        $category->parent->name .
                        ' → ' .
                        $category->name;
                }

                return [
                    'name' => $label,

                    'description' => $category->description
                        ? Str::limit(
                            $category->description,
                            50
                        )
                        : 'Không có mô tả'
                ];
            });

        return response()->json($suggestions);
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORY DATA
    |--------------------------------------------------------------------------
    */

    private function categoryData(Request $request): array
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('categories', 'slug')) {
            $data['slug'] =
                Str::slug($request->name);
        }

        /*
        |--------------------------------------------------------------------------
        | TYPE
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('categories', 'type')) {
            $data['type'] =
                $this->resolveType($request);
        }

        /*
        |--------------------------------------------------------------------------
        | PARENT
        |--------------------------------------------------------------------------
        */

        if ($this->hasParentColumn()) {
            $data['parent_id'] =
                $request->parent_id;
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE TYPE
    |--------------------------------------------------------------------------
    */

    private function resolveType(Request $request): string
    {
        if (
            Schema::hasColumn('categories', 'type') &&
            $request->filled('type')
        ) {
            return $request->type;
        }

        if (
            $this->hasParentColumn() &&
            $request->filled('parent_id')
        ) {

            $parent = Category::find(
                $request->parent_id
            );

            if ($parent && $parent->type) {
                return $parent->type;
            }
        }

        return 'do_uong';
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT ROUTE
    |--------------------------------------------------------------------------
    */

    private function getRedirectRoute($type)
    {
        return match ($type) {

            'do_uong' => 'categories.do_uong',

            'thuc_pham' => 'categories.thuc_pham',

            'gia_dung' => 'categories.gia_dung',

            default => 'categories.index',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK PARENT COLUMN
    |--------------------------------------------------------------------------
    */

    private function hasParentColumn(): bool
    {
        return Schema::hasColumn(
            'categories',
            'parent_id'
        );
    }
}