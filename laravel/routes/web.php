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
    $banners = \App\Models\Post::where('type', 1)->orderBy('priority', 'desc')->take(4)->get();
    return view('index', compact('products', 'banners'));
})->name('home');

/*
|--------------------------------------------------------------------------
| ĐĂNG NHẬP / ĐĂNG KÝ
|--------------------------------------------------------------------------
*/
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');
Route::get('/register', [CrudUserController::class, 'createUser'])->name('user.createUser');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');

/*
|--------------------------------------------------------------------------
| SẢN PHẨM & KHUYẾN MÃI CÔNG KHAI
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/khuyen-mai', [PostController::class, 'index'])->name('posts.index');

/*
|--------------------------------------------------------------------------
| ROUTE CẦN ĐĂNG NHẬP (giỏ hàng, thanh toán)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/signout', [CrudUserController::class, 'signOut'])->name('signout');

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/{productId}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY (phải đặt trước route show để tránh xung đột)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        // Dashboard & user
        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])->name('dashboard');
        Route::get('/list', [CrudUserController::class, 'listUser'])->name('user.listUser');
        Route::get('/read/{id}', [CrudUserController::class, 'readUser'])->name('user.readUser');
        Route::get('/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.updateUser');
        Route::post('/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
        Route::get('/delete/{id}', [CrudUserController::class, 'deleteUser'])->name('user.deleteUser');

        // Quản lý sản phẩm (create, store, edit, update, destroy)
        Route::resource('products', ProductController::class)->except(['index', 'show']);

        // Quản lý danh mục (create, store, edit, update, destroy) - đặt trước route show
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Quản lý bài viết (create, store, edit, update, destroy)
        Route::resource('posts', PostController::class)->except(['index']);
    });

    /*
    |--------------------------------------------------------------------------
    | DANH MỤC CÔNG KHAI (phải đặt SAU các route admin để không bị 'create' chặn)
    |--------------------------------------------------------------------------
    */
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
});