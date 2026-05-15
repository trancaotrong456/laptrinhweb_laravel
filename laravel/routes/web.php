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
| TRANG CHỦ & PUBLIC ROUTES
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

// Đăng nhập / Đăng ký
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');
Route::get('/register', [CrudUserController::class, 'createUser'])->name('user.createUser');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');

/*
|--------------------------------------------------------------------------
| ROUTE CẦN ĐĂNG NHẬP (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/signout', [CrudUserController::class, 'signOut'])->name('signout');

    // GIỎ HÀNG
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/{productId}', [CartController::class, 'remove'])->name('cart.remove');
        // Đổi sang GET để dễ gọi từ JavaScript Undo
        Route::get('/undo-remove', [CartController::class, 'undoRemove'])->name('cart.undoRemove');
        Route::post('/confirm-selected', [CartController::class, 'confirmSelected'])->name('cart.confirmSelected');
        Route::get('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // POSTS / KHUYẾN MÃI (Sửa lỗi trùng posts.index)
    Route::get('/khuyen-mai', [PostController::class, 'index'])->name('posts.index');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])->name('dashboard');
        
        // QUẢN LÝ USER
        Route::prefix('user')->group(function () {
            Route::get('/list', [CrudUserController::class, 'listUser'])->name('user.listUser');
            Route::get('/read/{id}', [CrudUserController::class, 'readUser'])->name('user.readUser');
            Route::get('/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.updateUser');
            Route::post('/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
            Route::get('/delete/{id}', [CrudUserController::class, 'deleteUser'])->name('user.deleteUser');
        });

        // RESOURCE QUẢN LÝ (Chỉ khai báo 1 lần ở đây)
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        // Loại bỏ index để không trùng với route 'khuyen-mai' ở trên
        Route::resource('posts', PostController::class)->except(['index']);
    });
});
