<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Models\Coupon;
use App\Models\UserSavedCoupon;

/*
|--------------------------------------------------------------------------
| 1. TRANG CHỦ & PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $products = \App\Models\Product::take(8)->get();

    $banners = \App\Models\Post::where('type', 1)
        ->orderBy('priority', 'desc')
        ->take(4)
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
            'coupons',
            'savedCouponIds'
        )
    );
})->name('home');

/*
|--------------------------------------------------------------------------
| POSTS / KHUYẾN MÃI
|--------------------------------------------------------------------------
*/

Route::get(
    '/khuyen-mai',
    [PostController::class, 'index']
)->name('posts.index');

/*
|--------------------------------------------------------------------------
| AUTH
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
| 2. ROUTE CẦN LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ĐĂNG XUẤT
    |--------------------------------------------------------------------------
    */

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

        Route::get(
            '/',
            [CartController::class, 'index']
        )->name('cart.index');

        Route::post(
            '/add',
            [CartController::class, 'add']
        )->name('cart.add');

        Route::post(
            '/update',
            [CartController::class, 'update']
        )->name('cart.update');

        Route::delete(
            '/{productId}',
            [CartController::class, 'remove']
        )->name('cart.remove');

        Route::get(
            '/undo-remove',
            [CartController::class, 'undoRemove']
        )->name('cart.undoRemove');

        Route::get(
            '/clear',
            [CartController::class, 'clear']
        )->name('cart.clear');

        Route::post(
            '/coupon/apply',
            [CartController::class, 'applyCoupon']
        )->name('cart.coupon.apply');

        Route::post(
            '/coupon/remove',
            [CartController::class, 'removeCoupon']
        )->name('cart.coupon.remove');
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

    /*
    |--------------------------------------------------------------------------
    | SAVE COUPON
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/coupons/{coupon}/save',
        [CouponController::class, 'saveForUser']
    )->name('coupons.save');

    Route::delete(
        '/coupons/{coupon}/save',
        [CouponController::class, 'unsaveForUser']
    )->name('coupons.unsave');

    /*
    |--------------------------------------------------------------------------
    | 3. ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [CrudUserController::class, 'dashboard']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | USER CRUD
        |--------------------------------------------------------------------------
        */

        Route::prefix('user')->group(function () {

            Route::get(
                '/list',
                [CrudUserController::class, 'listUser']
            )->name('user.listUser');

            Route::get(
                '/read/{id}',
                [CrudUserController::class, 'readUser']
            )->name('user.readUser');

            Route::get(
                '/update/{id}',
                [CrudUserController::class, 'updateUser']
            )->name('user.updateUser');

            Route::post(
                '/update/{id}',
                [CrudUserController::class, 'postUpdateUser']
            )->name('user.postUpdateUser');

            Route::get(
                '/delete/{id}',
                [CrudUserController::class, 'deleteUser']
            )->name('user.deleteUser');
        });

        /*
        |--------------------------------------------------------------------------
        | RESOURCE CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            ProductController::class
        );

        Route::resource(
            'categories',
            CategoryController::class
        );

        Route::resource(
            'coupons',
            CouponController::class
        )->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | POSTS CRUD
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