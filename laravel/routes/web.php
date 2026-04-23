<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CartController;



// Trang chủ (Trang này ai cũng xem được, giúp tránh lỗi vòng lặp Redirect)
Route::get('/', function () {
    $products = \App\Models\Product::take(8)->get();
    $banners = \App\Models\Post::where('type', 1)->orderBy('priority', 'desc')->take(4)->get();
    return view('index', compact('products', 'banners'));
})->name('home');

// Nhóm Route Đăng nhập & Đăng ký
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');

Route::get('/create', [CrudUserController::class, 'createUser'])->name('user.createUser');
Route::post('/create', [CrudUserController::class, 'postUser'])->name('user.postUser');


/*
|--------------------------------------------------------------------------
| 2. CÁC ROUTE YÊU CẦU ĐĂNG NHẬP (Auth Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Đăng xuất
    Route::get('/signout', [CrudUserController::class, 'signOut'])->name('signout');

    /* --- KHU VỰC DÀNH RIÊNG CHO ADMIN (role = 1) --- */
    // Bảo vệ bằng middleware 'admin' sếp đã tạo
    Route::middleware(['admin'])->group(function () {

        // Bảng điều khiển thống kê
        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])->name('dashboard');

        // Quản lý User
        Route::get('/list', [CrudUserController::class, 'listUser'])->name('user.listUser');
        Route::get('/read/{id}', [CrudUserController::class, 'readUser'])->name('user.readUser');
        Route::get('/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.updateUser');
        Route::post('/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
        Route::get('/delete/{id}', [CrudUserController::class, 'deleteUser'])->name('user.deleteUser');
    });

    /* --- KHU VỰC DÀNH CHO CẢ USER THƯỜNG VÀ ADMIN --- */
    // Khách hàng (role=0) có thể vào xem sản phẩm, bài viết thoải mái
    Route::resource('products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
    
    Route::resource('posts', PostController::class);
// Thêm 2 dòng này cho phần Bài viết / Khuyến mãi
Route::get('/khuyen-mai', [PostController::class, 'index'])->name('posts.index');
Route::get('/khuyen-mai/them-moi', [PostController::class, 'create'])->name('posts.create');
Route::post('/khuyen-mai/luu', [PostController::class, 'store'])->name('posts.store');
Route::get('/khuyen-mai/{id}/sua', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/khuyen-mai/{id}', [PostController::class, 'update'])->name('posts.update');
});