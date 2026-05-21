<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController; // Import CouponController

// ==================== TRANG CHỦ & PUBLIC ====================
Route::get('/', function () {
    $products = \App\Models\Product::take(8)->get();
    $banners = \App\Models\Post::where('type', 1)->orderBy('priority', 'desc')->take(4)->get();
    return view('index', compact('products', 'banners'));
})->name('home');

// Đăng nhập / Đăng ký
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');
Route::get('/register', [CrudUserController::class, 'createUser'])->name('user.createUser');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');

// ==================== YÊU CẦU ĐĂNG NHẬP ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/signout', [CrudUserController::class, 'signOut'])->name('signout');

    // Giỏ hàng
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/{productId}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/undo-remove', [CartController::class, 'undoRemove'])->name('cart.undoRemove');
        Route::post('/confirm-selected', [CartController::class, 'confirmSelected'])->name('cart.confirmSelected');
        Route::get('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    // Thanh toán
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Bài viết khuyến mãi
    Route::get('/khuyen-mai', [PostController::class, 'index'])->name('posts.index');

    // ==================== QUẢN LÝ DANH MỤC ====================
    // 1. Route gợi ý tìm kiếm (đặt trước để không bị trùng)
    Route::get('/categories/search-suggestions', [CategoryController::class, 'searchSuggestions'])
        ->name('categories.searchSuggestions');

    // 2. Các route thêm/sửa/xóa chỉ dành cho ADMIN (phải đặt TRƯỚC route show)
    Route::middleware(['admin'])->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // 3. Route danh sách và chi tiết (dành cho mọi user đã đăng nhập)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // ==================== ADMIN ONLY (các chức năng khác) ====================
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])->name('dashboard');

        // Quản lý user
        Route::prefix('user')->group(function () {
            Route::get('/list', [CrudUserController::class, 'listUser'])->name('user.listUser');
            Route::get('/read/{id}', [CrudUserController::class, 'readUser'])->name('user.readUser');
            Route::get('/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.updateUser');
            Route::post('/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
            Route::get('/delete/{id}', [CrudUserController::class, 'deleteUser'])->name('user.deleteUser');
        });

        // Quản lý sản phẩm
        Route::resource('products', ProductController::class);

        // Quản lý bài viết
        Route::resource('posts', PostController::class)->except(['index']);

        // ==================== QUẢN LÝ MÃ GIẢM GIÁ (COUPONS) ====================
        Route::resource('coupons', CouponController::class)->except(['show']);
        // Route lưu / hủy lưu coupon cho user (có thể dùng chung, nhưng đặt trong admin để an toàn)
        Route::post('/coupons/{coupon}/save', [CouponController::class, 'saveForUser'])->name('coupons.saveForUser');
        Route::delete('/coupons/{coupon}/unsave', [CouponController::class, 'unsaveForUser'])->name('coupons.unsaveForUser');
    });
});