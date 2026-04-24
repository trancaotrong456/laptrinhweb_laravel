<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

// Trang chủ
Route::get('/', function () {
    return view('index'); 
})->name('home');

// Đăng nhập & Đăng ký
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');
Route::get('/register', [CrudUserController::class, 'createUser'])->name('user.createUser');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');

// Các route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/signout', [CrudUserController::class, 'signOut'])->name('signout');

    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [CrudUserController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [CrudUserController::class, 'listUser'])->name('user.listUser');
        Route::get('/users/{id}', [CrudUserController::class, 'readUser'])->name('user.readUser');
        Route::get('/users/{id}/edit', [CrudUserController::class, 'updateUser'])->name('user.updateUser');
        Route::put('/users/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
        Route::delete('/users/{id}', [CrudUserController::class, 'deleteUser'])->name('user.deleteUser');

        // Quản lý danh mục theo loại
        Route::get('/do-uong', [CategoryController::class, 'doUong'])->name('categories.do_uong');
        Route::get('/thuc-pham', [CategoryController::class, 'thucPham'])->name('categories.thuc_pham');
        Route::get('/gia-dung', [CategoryController::class, 'giaDung'])->name('categories.gia_dung');
    });

    // Resources
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
});