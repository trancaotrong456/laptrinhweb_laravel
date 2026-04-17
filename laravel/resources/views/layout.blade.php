<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Management - @yield('title')</title>

    {{-- THÊM DÒNG NÀY: Nhúng CSS của Bootstrap để giao diện đẹp --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: #f4f4f4;
    }

    nav {
        background: #333;
        padding: 15px;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    nav ul li {
        margin-right: 20px;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .main-container {
        padding: 20px;
        min-height: 80vh;
        padding-bottom: 60px;
        /* Tránh footer đè lên nội dung */
    }

    footer {
        background-color: #0000FF;
        color: white;
        padding: 10px;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        z-index: 999;
    }
    </style>
</head>

<body>
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Trang chủ</a></li>

            {{-- KHÁCH VÃNG LAI (CHƯA ĐĂNG NHẬP) --}}
            @guest
            <li><a href="{{ route('login') }}">Đăng nhập</a></li>
            <li><a href="{{ route('user.createUser') }}">Đăng kí</a></li>

            {{-- Ai cũng xem được Khuyến Mãi --}}
            <li><a href="{{ route('posts.index') }}">🔥 Khuyến Mãi</a></li>

            {{-- ĐÃ ĐĂNG NHẬP --}}
            @else
            <li><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li><a href="{{ route('categories.index') }}">Danh mục</a></li>

            {{-- Chỗ này đang bị trùng Route với Khuyến mãi, sếp check lại nhé --}}
            <li><a href="{{ route('posts.index') }}">Tin tức</a></li>

            <li><a href="{{ route('posts.index') }}">🔥 Khuyến Mãi</a></li>

            {{-- CHỈ ADMIN (Role = 1) mới thấy menu Quản lý và Đăng bài --}}
            @if(Auth::user()->role == 1)
            <li><a href="{{ route('user.listUser') }}">Quản lý User</a></li>
            <li><a href="{{ route('posts.create') }}">✍️ Đăng Bài KHUYẾN MÃI</a></li>
            @endif

            <li><a href="{{ route('signout') }}">Đăng xuất</a></li>
            @endguest
        </ul>
    </nav>

    {{-- Đổi class thành main-container để không bị đụng chạm với class container của Bootstrap --}}
    <div class="main-container">

        {{-- Phần thông báo thành công sếp để đây là chuẩn rồi --}}
        @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
        @endif

        @yield('content')
    </div>

    <footer>
        <p>&copy; Trần Cao Trọng - 24211TT1101</p>
    </footer>

    {{-- File Script Bootstrap ở đáy body là đúng chuẩn --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>