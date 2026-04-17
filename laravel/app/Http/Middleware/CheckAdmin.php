<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
{
    // Nếu chưa đăng nhập, đá về login (Cái này chuẩn rồi)
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // LẤY RA USER ĐANG ĐĂNG NHẬP
    $user = Auth::user();

    // CHỈ KHI truy cập vào các route cần quyền admin (như Dashboard, List User)
    // Mà user đó KHÔNG PHẢI admin (role != 1) thì mới chặn
    if ($request->is('dashboard*') || $request->is('list*')) {
        if ($user->role != 1) {
            // Thay vì redirect gây vòng lặp, hãy trả về một thông báo lỗi
            // Hoặc đá về trang chủ NHƯNG trang chủ đó không được dính middleware admin
            return redirect('/')->with('error', 'Bạn không có quyền vào khu vực Admin.');
        }
    }

    return $next($request);
}
}