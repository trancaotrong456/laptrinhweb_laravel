<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siêu thị Mini')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    /* NAVBAR */
    .navbar-custom {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(15px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1.6rem;
        color: #667eea !important;
    }

    .nav-link {
        font-weight: 500;
        padding: 8px 16px !important;
        border-radius: 20px;
        transition: 0.3s;
    }

    .nav-link:hover {
        background: #f1f3ff;
        color: #667eea !important;
    }

    /* SEARCH */
    .search-box {
        position: relative;
    }

    .search-box input {
        border-radius: 25px;
        padding-right: 40px;
    }

    .btn-search {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: #667eea;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
    }

    /* CART */
    .cart-btn {
        position: relative;
        font-size: 1.3rem;
        color: #667eea;
    }

    .cart-btn:hover {
        transform: scale(1.1);
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background: red;
        color: white;
        border-radius: 50%;
        font-size: 0.7rem;
        padding: 3px 6px;
    }

    /* USER */
    .user-box {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .dropdown-menu {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    main {
        padding-top: 90px;
    }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-store"></i> MiniMart
            </a>

            <!-- Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- MENU -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Khuyến mãi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Danh mục</a></li>
                </ul>

                <!-- SEARCH -->
                <form class="d-flex me-3 search-box">
                    <input class="form-control" type="search" placeholder="Tìm sản phẩm...">
                    <button class="btn btn-search"><i class="fas fa-search"></i></button>
                </form>

                <!-- RIGHT -->
                @guest
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="btn btn-primary rounded-pill px-4" href="{{ route('user.createUser') }}">Đăng ký</a>
                </div>
                @else
                <div class="d-flex align-items-center gap-3">

                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge">
                            {{ Session::has('cart') ? count(Session::get('cart')) : 0 }}
                        </span>
                    </a>

                    <!-- USER -->
                    <div class="dropdown">
                        <div class="user-box" data-bs-toggle="dropdown">
                            <div class="avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down small"></i>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">{{ Auth::user()->name }}</li>

                            @if(Auth::user()->role == 1)
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            @endif

                            <li><a class="dropdown-item" href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="GET" action="{{ route('signout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
                @endguest

            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">© 2024 Siêu thị Mini - Trần Cao Trọng</p>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>