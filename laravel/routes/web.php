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

        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [CrudUserController::class, 'listUser'])
            ->name('user.listUser');

        Route::get('/users/{id}', [CrudUserController::class, 'readUser'])
            ->name('user.readUser');

        Route::get('/users/{id}/edit', [CrudUserController::class, 'updateUser'])
            ->name('user.updateUser');

        Route::put('/users/{id}', [CrudUserController::class, 'postUpdateUser'])
            ->name('user.postUpdateUser');

        Route::delete('/users/{id}', [CrudUserController::class, 'deleteUser'])
            ->name('user.deleteUser');

        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategoryController::class);

        Route::get('/do-uong', [CategoryController::class, 'doUong'])
            ->name('categories.do_uong');

        Route::get('/thuc-pham', [CategoryController::class, 'thucPham'])
            ->name('categories.thuc_pham');

        Route::get('/gia-dung', [CategoryController::class, 'giaDung'])
            ->name('categories.gia_dung');

        /*
        |--------------------------------------------------------------------------
        | QUẢN LÝ KHUYẾN MÃI
        |--------------------------------------------------------------------------
        */

        Route::get('/khuyen-mai/them-moi', [PostController::class, 'create'])
            ->name('posts.create');

        Route::post('/khuyen-mai/luu', [PostController::class, 'store'])
            ->name('posts.store');

        Route::get('/khuyen-mai/{id}/sua', [PostController::class, 'edit'])
            ->name('posts.edit');

        Route::put('/khuyen-mai/{id}', [PostController::class, 'update'])
            ->name('posts.update');

        Route::delete('/khuyen-mai/{id}', [PostController::class, 'destroy'])
            ->name('posts.destroy');
    });
});