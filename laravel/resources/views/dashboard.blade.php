<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Management - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            <li><a href="{{ route('signout') }}">Đăng xuất</a></li>
            @if(Auth::user()->role == 1)
            <li><a href="{{ route('user.listUser') }}">Quản lý User</a></li>
            <li><a href="{{ route('categories.index') }}">Danh mục</a></li>
            <li><a href="{{ route('posts.index') }}">Tin tức</a></li>
            @endif
            @endguest
        </ul>
    </nav>

    <div class="container">
        @if(session('success'))
        <div
            style="background: #d4edda; color: #155724; padding: 10px; margin: 20px auto; max-width: 400px; border-radius: 5px;">
            {{ session('success') }}
        </div>
        @endif

        @yield('content')
    </div>

    <footer style="background-color: #0000FF; color: white; padding: 10px; position: fixed; bottom: 0; width: 100%;">
        <p style="text-align: center; margin: 0;">&copy; Trần Cao Trọng - 24211TT1101</p>
    </footer>
</body>

</html>