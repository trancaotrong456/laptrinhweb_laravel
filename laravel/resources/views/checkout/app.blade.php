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
    <!-- Google Font -->
   
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/store.css') }}" rel="stylesheet">
    <link href="{{ asset('css/products.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
    :root {
        --primary: #4f46e5;
        --secondary: #7c3aed;
        --dark: #0f172a;
        --gray: #64748b;
        --light: #f8fafc;
        --success: #10b981;
        --danger: #ef4444;
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }
    body {
        background: linear-gradient(to bottom right, #f8fafc, #eef2ff);
        color: #111827;
        overflow-x: hidden;
    }
    /* ================= NAVBAR ================= */
    .navbar {
        position: sticky;
        top: 0;
        z-index: 999;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, .3);
        padding: 14px 24px;
        transition: all .3s ease;
    }
    .navbar.scrolled {
        padding: 10px 24px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
    }
    .navbar-brand {
        font-size: 1.9rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .navbar-brand i {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    /* NAV LINKS */
    .nav-link {
        position: relative;
        color: #334155 !important;
        font-weight: 600;
        padding: 12px 18px !important;
        border-radius: 14px;
        transition: .25s ease;
    }
    .nav-link:hover {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(79, 70, 229, .25);
    }
    /* SEARCH */
    .search-box {
        position: relative;
        width: 320px;
    }
    .search-input {
        width: 100%;
        border: none;
        outline: none;
        padding: 12px 45px 12px 18px;
        border-radius: 50px;
        background: rgba(255, 255, 255, .8);
        box-shadow: inset 0 0 0 1px #e2e8f0;
        transition: .3s;
    }
    .search-input:focus {
        box-shadow:
            0 0 0 4px rgba(79, 70, 229, .15),
            inset 0 0 0 1px var(--primary);
    }
    .search-icon {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        color: var(--gray);
    }
    /* BUTTONS */
    .btn-modern {
        border-radius: 50px;
        padding: 12px 22px;
        font-weight: 600;
        transition: .25s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
    }
    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        color: white;
        box-shadow: 0 10px 20px rgba(79, 70, 229, .25);
    }
    .btn-primary-modern:hover {
        color: white;
        box-shadow: 0 14px 30px rgba(79, 70, 229, .35);
    }
    .btn-outline-modern {
        border: 2px solid #dbeafe;
        background: white;
        color: var(--primary);
    }
    .btn-outline-modern:hover {
        background: var(--primary);
        color: white;
    }
    /* CART */
    .cart-container {
        position: relative;
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
        transition: .3s;
        text-decoration: none;
    }
    .cart-container:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 10px 25px rgba(79, 70, 229, .2);
    }
    .cart-btn {
        color: var(--primary);
        font-size: 1.25rem;
    }
    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 22px;
        height: 22px;
        border-radius: 999px;
        background: var(--danger);
        color: white;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        border: 2px solid white;
    }
    /* USER */
    .user-avatar,
    .user-avatar-lg {
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .user-avatar {
        width: 45px;
        height: 45px;
        font-size: 1rem;
    }
    .user-avatar-lg {
        width: 55px;
        height: 55px;
        font-size: 1.2rem;
    }
    /* DROPDOWN */
    .dropdown-menu {
        border: none;
        border-radius: 24px;
        padding: 1rem;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, .12);
        min-width: 290px;
        margin-top: 16px;
    }
    .dropdown-item {
        border-radius: 16px;
        padding: 14px 16px;
        font-weight: 500;
        transition: .25s;
    }
    .dropdown-item:hover {
        background: #eef2ff;
        transform: translateX(4px);
    }
    /* MAIN */
    main {
        min-height: calc(100vh - 150px);
        padding-top: 40px;
    }
    /* ALERT */
    .alert-modern {
        border: none;
        border-radius: 20px;
        padding: 18px 22px;
        backdrop-filter: blur(12px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, .12);
    }
    /* FOOTER */
    footer {
        margin-top: 80px;
        background: linear-gradient(135deg, #111827, #1e293b);
        color: rgba(255, 255, 255, .9);
        padding: 50px 0;
    }
    .footer-title {
        font-weight: 800;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    .footer-social a {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .08);
        color: white;
        margin: 0 5px;
        transition: .3s;
        text-decoration: none;
    }
    .footer-social a:hover {
        transform: translateY(-4px);
        background: var(--primary);
    }
    /* MOBILE */
    @media(max-width: 991px) {
        .search-box {
            width: 100%;
            margin: 15px 0;
        }
        .navbar-collapse {
            padding-top: 20px;
        }
        .guest-buttons {
            flex-direction: column;
            width: 100%;
            gap: 12px;
        }
        .guest-buttons a {
            width: 100%;
        }
    }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="fas fa-store-alt"></i>
                <span>Siêu thị Mini</span>
            </a>
            <!-- TOGGLE -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent">
                <i class="fas fa-bars fs-4 text-primary"></i>
            </button>
            <!-- CONTENT -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- MENU -->
                <ul class="navbar-nav mx-auto gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>
                            Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-box-open me-1"></i>
                            Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">
                            <i class="fas fa-tags me-1"></i>
                            Khuyến mãi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('coupons.index') }}">
                            <i class="fas fa-ticket-alt me-1"></i>
                            Mã giảm giá
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-layer-group me-1"></i>
                            Danh mục
                        </a>
                    </li>
                </ul>
                <!-- SEARCH -->
                <form class="search-box me-lg-4">
                    <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm...">
                    <i class="fas fa-search search-icon"></i>
                </form>
                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">
                    @guest
                    <div class="guest-buttons d-flex gap-3">
                        <a href="{{ route('login') }}"
                            class="btn btn-modern btn-outline-modern d-flex align-items-center gap-2">
                            <i class="fas fa-sign-in-alt"></i>
                            Đăng nhập
                        </a>
                        <a href="{{ route('user.createUser') }}"
                            class="btn btn-modern btn-primary-modern d-flex align-items-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Đăng ký
                        </a>
                    </div>
                    @else
                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="cart-container">
                        <i class="fas fa-shopping-cart cart-btn"></i>
                        <span class="cart-badge">
                            {{ Session::has('cart') ? array_sum(array_column(Session::get('cart', []), 'quantity')) : 0 }}
                        </span>
                    </a>
                    <!-- USER -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle border-0 p-0 d-flex align-items-center gap-3" href="#"
                            role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block text-start">
                                <div class="fw-bold text-dark">
                                    {{ Str::limit(Auth::user()->name, 14) }}
                                </div>
                                <small class="text-muted">
                                    {{ Auth::user()->role == 1 ? 'Administrator' : 'Khách hàng' }}
                                </small>
                            </div>
                        </a>
                        <!-- DROPDOWN -->
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="px-2 pb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-lg">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">
                                            {{ Auth::user()->name }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ Auth::user()->email }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <hr class="opacity-25">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-3"
                                    href="{{ route('cart.index') }}">
                                    <i class="fas fa-shopping-cart text-success"></i>
                                    Giỏ hàng
                                </a>
                            </li>
                            @if(Auth::user()->role == 1)
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-3"
                                    href="{{ route('dashboard') }}">
                                    <i class="fas fa-chart-line text-primary"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-3"
                                    href="{{ route('user.listUser') }}">
                                    <i class="fas fa-users text-info"></i>
                                    Quản lý User
                                </a>
                            </li>
                            @endif
                            <li>
                                <hr class="opacity-25">
                            </li>
                            <li>
                                <a href="{{ route('signout') }}"
                                    class="dropdown-item d-flex align-items-center gap-3 text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    <!-- MAIN -->
    <main>
        <!-- ALERT -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-modern position-fixed top-0 end-0 m-4 z-3"
            style="max-width: 400px;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </main>
    <!-- FOOTER -->
    <footer>
        <div class="container text-center">
            <div class="footer-title">
                <i class="fas fa-store-alt me-2"></i>
                Siêu thị Mini
            </div>
            <p class="mb-4 text-light opacity-75">
                Website bán hàng Laravel hiện đại
            </p>
            <div class="footer-social mb-4">
                <a href="#">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
            <p class="mb-0 opacity-75">
                © 2026 <strong>Trần Cao Trọng - 24211TT1101</strong>
            </p>
        </div>
    </footer>
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CUSTOM -->
    <script>
    // NAVBAR SCROLL
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    });
    // AJAX ADD TO CART
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            let productId = this.dataset.id;
            fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.querySelector('.cart-badge');
                        if (badge) {
                            badge.innerText = data.count;
                        }
                        // TOAST
                        const toast = document.createElement('div');
                        toast.className =
                            'position-fixed top-0 end-0 m-4 bg-success text-white px-4 py-3 rounded-4 shadow';
                        toast.style.zIndex = '9999';
                        toast.innerHTML =
                            `<i class="fas fa-check-circle me-2"></i>${data.message}`;
                        document.body.appendChild(toast);
                        setTimeout(() => {
                            toast.remove();
                        }, 2500);
                    }
                });
        });
    });
    </script>
</body>
</html>