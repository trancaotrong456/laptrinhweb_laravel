<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Management - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
    /* CSS bổ sung để căn chỉnh Navbar và nút Giỏ hàng sang phải */
    nav ul {
        display: flex;
        align-items: center;
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    nav ul li {
        margin-right: 15px;
        /* Khoảng cách giữa các menu */
    }

    /* Đẩy class này sang tận cùng bên phải */
    .cart-item {
        margin-left: auto;
        margin-right: 20px;
        /* Cách lề phải một chút cho đẹp */
    }

    .cart-item a {
        background-color: #ffc107;
        /* Màu vàng nổi bật */
        color: #000;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .cart-item a:hover {
        background-color: #e0a800;
    }
    </style>
</head>

<body>
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Trang chủ</a></li>

            @guest
            <li><a href="{{ route('login') }}">Đăng nhập</a></li>
            <li><a href="{{ route('user.createUser') }}">Đăng kí</a></li>
            @else
            <li><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li><a href="{{ route('categories.index') }}">Danh mục</a></li>
            <li><a href="{{ route('posts.index') }}">Tin tức</a></li>
            @if(Auth::user()->role == 1)
            <li><a href="{{ route('user.listUser') }}">Quản lý User</a></li>
            @endif
            <li><a href="{{ route('signout') }}">Đăng xuất</a></li>
            @endguest

            <li class="cart-item">
                <a href="{{ route('cart.index') }}">
                    🛒 Giỏ hàng ({{ session()->has('cart') ? count(session('cart')) : 0 }})
                </a>
            </li>
        </ul>
    </nav>

    <div class="container" style="padding-bottom: 60px;">
        @if(session('success'))
        <div
            style="background: #d4edda; color: #155724; padding: 10px; margin: 20px auto; max-width: 400px; border-radius: 5px;">
            {{ session('success') }}
        </div>
        @endif

        @yield('content')
    </div>

    <footer
        style="background-color: #0000FF; color: white; padding: 10px; position: fixed; bottom: 0; width: 100%; z-index: 1000;">
        <p style="text-align: center; margin: 0;">&copy; Trần Cao Trọng - 24211TT1101</p>
    </footer>
</body>

</html>