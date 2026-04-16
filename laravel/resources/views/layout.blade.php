<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Management - @yield('title')</title>
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

    .container {
        padding: 20px;
        min-height: 80vh;
    }

    footer {
        background-color: #0000FF;
        color: white;
        padding: 10px;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
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
            <li><a href="{{ route('dashboard') }}">Bảng điều khiển</a></li>
            <li><a href="{{ route('user.listUser') }}">Quản lý User</a></li>
            <li><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li><a href="{{ route('signout') }}">Đăng xuất</a></li>
            @endguest
        </ul>
    </nav>

    <div class="container">
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
</body>

</html>