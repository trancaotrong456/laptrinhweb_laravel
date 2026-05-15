<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| TRANG CHỦ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $products = \App\Models\Product::take(8)->get();

    $banners = \App\Models\Post::where('type', 1)
        ->orderBy('priority', 'desc')
        ->take(4)
        ->get();

    return view('index', compact('products', 'banners'));

})->name('home');

/*
|--------------------------------------------------------------------------
| ĐĂNG NHẬP / ĐĂNG KÝ
|--------------------------------------------------------------------------
*/

Route::get('/login', [CrudUserController::class, 'login'])
    ->name('login');

Route::post('/login', [CrudUserController::class, 'authUser'])
    ->name('user.authUser');

Route::get('/register', [CrudUserController::class, 'createUser'])
    ->name('user.createUser');

Route::post('/register', [CrudUserController::class, 'postUser'])
    ->name('user.postUser');

/*
|--------------------------------------------------------------------------
| ROUTE CẦN ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ĐĂNG XUẤT
    |--------------------------------------------------------------------------
    */

    Route::get('/signout', [CrudUserController::class, 'signOut'])
        ->name('signout');

    /*
    |--------------------------------------------------------------------------
    | GIỎ HÀNG
    |--------------------------------------------------------------------------
    */

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');

    Route::post('/cart/update', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{productId}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::get('/cart/clear', [CartController::class, 'clear'])
        ->name('cart.clear');

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'process'])
        ->name('checkout.process');

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class);

    /*
    |--------------------------------------------------------------------------
    | POSTS / KHUYẾN MÃI
    |--------------------------------------------------------------------------
    */

    Route::get('/khuyen-mai', [PostController::class, 'index'])
        ->name('posts.index');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        // Bảng điều khiển thống kê
        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])->name('dashboard');

        // Quản lý User
        Route::get('/list', [CrudUserController::class, 'listUser'])->name('user.listUser');
        Route::get('/read/{id}', [CrudUserController::class, 'readUser'])->name('user.readUser');
        Route::get('/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.updateUser');
        Route::post('/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
        Route::get('/delete/{id}', [CrudUserController::class, 'deleteUser'])->name('user.deleteUser');

        // =========================
        // Quản lý Products
        // =========================
        Route::resource('products', ProductController::class);
    });
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    /* --- KHU VỰC DÀNH CHO CẢ USER THƯỜNG VÀ ADMIN --- */
    // Khách hàng (role=0) có thể vào xem sản phẩm, bài viết thoải mái
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
});
