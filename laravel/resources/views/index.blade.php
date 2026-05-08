@extends('dashboard') @section('title', 'Chào mừng đến với Siêu thị Mini')

@section('content')
<div style="text-align: center; padding: 50px 20px; background: #f8f9fa;">
    <h1 style="font-size: 3rem; color: #007bff;">Chào mừng đến với Siêu Thị </h1>
    <p style="font-size: 1.2rem; color: #6c757d;">Hệ thống quản lý siêu thị hiện đại, nhanh chóng và chính xác.</p>

    @guest
    <div style="margin-top: 30px;">
        <a href="{{ route('login') }}"
            style="padding: 15px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">Đăng
            nhập quản trị</a>
        <a href="{{ route('user.createUser') }}"
            style="padding: 15px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">Tham
            gia ngay</a>
    </div>
    @else
    <div style="margin-top: 30px;">
        <a href="{{ route('dashboard') }}"
            style="padding: 15px 30px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px;">Vào
            trang quản lý (Dashboard)</a>
    </div>
    @endguest
</div>

<div style="display: flex; justify-content: space-around; padding: 50px; text-align: center;">
    <div>
        <h3>🛍️ Sản phẩm</h3>
        <p>Hàng ngàn mặt hàng đa dạng.</p>
    </div>
    <div>
        <h3>🚚 Giao hàng</h3>
        <p>Nhanh chóng trong nội thành.</p>
    </div>
    <div>
        <h3>💎 Uy tín</h3>
        <p>Chất lượng hàng đầu Việt Nam.</p>
    </div>
</div>
@endsection