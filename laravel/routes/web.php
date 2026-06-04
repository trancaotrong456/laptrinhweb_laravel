<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductShopController;
use App\Http\Controllers\SavedCouponController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\FlashSaleController;

use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use App\Models\FlashSale;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $products = Product::take(8)->get();

    $banners = \App\Models\Post::where('type', 1)
        ->orderBy('priority', 'desc')
        ->take(4)
        ->get();

    // Flash Sales đang hoạt động
    $activeFlashSales = FlashSale::active()->with('product')->get();
    $flashSaleEndsAt  = $activeFlashSales->first()?->ends_at;

    // Fetch categories from database with product count
    $categories = Category::withCount('products')
        ->orderBy('name')
        ->get();

    $coupons = Coupon::where('is_active', true)
        ->where(function ($query) {
            $query->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
        })
        ->where(function ($query) {
            $query->whereNull('usage_limit')
                ->orWhereColumn('used_count', '<', 'usage_limit');
        })
        ->orderByDesc('id')
        ->take(6)
        ->get();

    $savedCouponIds = [];

    if (auth()->check()) {
        $savedCouponIds = UserSavedCoupon::where('user_id', auth()->id())
            ->pluck('coupon_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    return view(
        'index',
        compact(
            'products',
            'banners',
            'categories',
            'coupons',
            'savedCouponIds',
            'activeFlashSales',
            'flashSaleEndsAt'
        )
    );

})->name('home');


/*
|--------------------------------------------------------------------------
| UTILITY ROUTES (Admin only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/seed-categories', [UtilityController::class, 'seedCategories'])->name('utility.seedCategories');
});


/*
|--------------------------------------------------------------------------
| SEARCH AJAX
|--------------------------------------------------------------------------
*/

Route::get('/search-products', function (Request $request) {

    $keyword = trim(
        $request->get('q', $request->get('keyword', ''))
    );

    if ($keyword === '') {
        return response()->json([]);
    }

    $products = Product::where(
            'name',
            'LIKE',
            "%{$keyword}%"
        )
        ->take(8)
        ->get([
            'id',
            'name',
            'price',
            'image'
        ]);

    return response()->json($products);

})->name('products.search');


/*
|--------------------------------------------------------------------------
| CATEGORY ROUTES (Gộp chung và sắp xếp chuẩn tránh lỗi 404)
|--------------------------------------------------------------------------
*/

Route::get('/do-uong', [CategoryController::class, 'doUong'])->name('categories.do_uong');
Route::get('/thuc-pham', [CategoryController::class, 'thucPham'])->name('categories.thuc_pham');
Route::get('/gia-dung', [CategoryController::class, 'giaDung'])->name('categories.gia_dung');
Route::get('/categories/search-suggestions', [CategoryController::class, 'searchSuggestions'])->name('categories.searchSuggestions');

// Hệ thống CRUD Danh mục (Đã chuyển ra ngoài Middleware Admin)
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create'); // 👈 Luôn đứng trước {id} để tránh 404
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');


/*
|--------------------------------------------------------------------------
| PRODUCT DETAIL
|--------------------------------------------------------------------------
*/

Route::get(
    '/san-pham/{product}',
    [ProductShopController::class, 'show']
)->name('products.detail');


/*
|--------------------------------------------------------------------------
| POSTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/khuyen-mai',
    [PostController::class, 'index']
)->name('posts.index');


/*
|--------------------------------------------------------------------------
| LOGIN / REGISTER
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [CrudUserController::class, 'login']
)->name('login');

Route::post(
    '/login',
    [CrudUserController::class, 'authUser']
)->name('user.authUser');

Route::get(
    '/register',
    [CrudUserController::class, 'createUser']
)->name('user.createUser');

Route::post(
    '/register',
    [CrudUserController::class, 'postUser']
)->name('user.postUser');


/*
|--------------------------------------------------------------------------
| AUTH REQUIRED ROUTES (Yêu cầu đăng nhập)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | SIGN OUT
    |--------------------------------------------------------------------------
    |*/

    Route::get(
        '/signout',
        [CrudUserController::class, 'signOut']
    )->name('signout');


    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    Route::prefix('cart')->group(function () {

        Route::get('/', [CartController::class, 'index'])
            ->name('cart.index');

        Route::post('/add', [CartController::class, 'add'])
            ->name('cart.add');

        Route::post('/update', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/{productId}', [CartController::class, 'remove'])
            ->name('cart.remove');

        Route::get('/undo-remove', [CartController::class, 'undoRemove'])
            ->name('cart.undoRemove');

        Route::get('/clear', [CartController::class, 'clear'])
            ->name('cart.clear');

        Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])
            ->name('cart.coupon.apply');

        Route::post('/coupon/remove', [CartController::class, 'removeCoupon'])
            ->name('cart.coupon.remove');

    });


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/checkout',
        [CheckoutController::class, 'index']
    )->name('checkout.index');

    Route::post(
        '/checkout',
        [CartController::class, 'checkout']
    )->name('checkout.process');

    Route::get(
        '/order-confirmation',
        [CartController::class, 'orderConfirmation']
    )->name('order.confirmation');

    Route::get(
        '/orders',
        [CartController::class, 'userOrders']
    )->name('orders.user');

    Route::get(
        '/orders/{orderNumber}',
        [CartController::class, 'orderDetail']
    )->name('order.detail');


    /*
    |--------------------------------------------------------------------------
    | USER SAVED COUPONS (Ví Voucher người dùng & Lưu mã AJAX)
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/coupons/{id}/save',
        [SavedCouponController::class, 'save']
    )->name('coupons.save');

    Route::delete(
        '/coupons/{id}/unsave',
        [SavedCouponController::class, 'unsave']
    )->name('coupons.unsave');

    Route::get(
        '/coupons/saved',
        [SavedCouponController::class, 'index']
    )->name('coupons.saved');


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS SHOP
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/all',
        [ProductShopController::class, 'all']
    )->name('products.all');

    Route::post(
        '/san-pham/{product}/mua-ngay',
        [ProductShopController::class, 'buyNow']
    )->name('products.buyNow');

    Route::post(
        '/san-pham/{product}/danh-gia',
        [ProductShopController::class, 'storeReview']
    )->name('products.reviews.store');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])->group(function () {

        Route::get(
            '/dashboard',
            [DashboardController::class, 'dashboard']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | USERS MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::prefix('user')->group(function () {

            Route::get('/list', [CrudUserController::class, 'listUser'])
                ->name('user.listUser');

            Route::get('/read/{id}', [CrudUserController::class, 'readUser'])
                ->name('user.readUser');

            Route::get('/update/{id}', [CrudUserController::class, 'updateUser'])
                ->name('user.updateUser');

            Route::post('/update/{id}', [CrudUserController::class, 'postUpdateUser'])
                ->name('user.postUpdateUser');

            Route::get('/delete/{id}', [CrudUserController::class, 'deleteUser'])
                ->name('user.deleteUser');

            // Cấp quyền admin
            Route::patch('/promote/{id}', [CrudUserController::class, 'promoteUser'])
                ->name('user.promoteUser');
        });

        /*
        |--------------------------------------------------------------------------
        | ADMIN ORDERS MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::prefix('admin/orders')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
            Route::get('/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
            Route::patch('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        });


        /*
        |--------------------------------------------------------------------------
        | ADMIN CRUD RESOURCES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            ProductController::class
        );

        // Đoạn quản lý categories cũ ở đây đã được xóa bỏ

        Route::resource(
            'coupons',
            CouponController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | FLASH SALE ADMIN
        |--------------------------------------------------------------------------
        */

        Route::prefix('admin/flash-sales')->name('admin.flash-sales.')->group(function () {
            Route::get('/',                          [FlashSaleController::class, 'index'])  ->name('index');
            Route::post('/',                         [FlashSaleController::class, 'store'])  ->name('store');
            Route::get('/{flashSale}/edit',          [FlashSaleController::class, 'edit'])   ->name('edit');
            Route::put('/{flashSale}',               [FlashSaleController::class, 'update']) ->name('update');
            Route::delete('/{flashSale}',            [FlashSaleController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | POSTS / BANNERS ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/admin/khuyen-mai/them-moi',
            [PostController::class, 'create']
        )->name('posts.create');

        Route::post(
            '/admin/khuyen-mai/luu',
            [PostController::class, 'store']
        )->name('posts.store');

        Route::get(
            '/admin/khuyen-mai/{id}/sua',
            [PostController::class, 'edit']
        )->name('posts.edit');

        Route::put(
            '/admin/khuyen-mai/{id}',
            [PostController::class, 'update']
        )->name('posts.update');

        Route::delete(
            '/admin/khuyen-mai/{id}',
            [PostController::class, 'destroy']
        )->name('posts.destroy');

    });

});
