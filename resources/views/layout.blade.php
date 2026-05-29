<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Siêu thị Mini')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/products.css') }}" rel="stylesheet">

    @stack('styles')

    <style>
    :root {
        --primary: #4f46e5;
        --secondary: #7c3aed;

        --gradient:
            linear-gradient(135deg,
                #4f46e5 0%,
                #7c3aed 100%);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;

        background:
            linear-gradient(to bottom right,
                #f8fafc,
                #eef2ff);

        overflow-x: hidden;
    }

    /* ================= TOP NAVBAR ================= */

    .top-navbar {
        position: sticky;
        top: 0;
        z-index: 999;

        background:
            rgba(255, 255, 255, .88);

        backdrop-filter: blur(18px);

        border-bottom:
            1px solid rgba(255, 255, 255, .25);

        padding: 16px 0;

        transition: .3s;
    }

    .top-navbar.scrolled {
        box-shadow:
            0 10px 30px rgba(0, 0, 0, .08);
    }

    .navbar-brand {
        font-size: 1.8rem;
        font-weight: 800;

        background: var(--gradient);

        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .navbar-brand i {
        background: var(--gradient);

        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* ================= SEARCH ================= */

    .search-box {
        width: 100%;
        max-width: 520px;

        position: relative;
    }

    .search-input {
        width: 100%;
        height: 52px;

        border: none;
        outline: none;

        border-radius: 999px;

        padding: 0 60px 0 24px;

        background: #f1f5f9;

        font-size: 15px;

        transition: .3s;
    }

    .search-input:focus {
        background: white;

        box-shadow:
            0 0 0 4px rgba(79, 70, 229, .12);
    }

    .search-btn {
        position: absolute;

        top: 5px;
        right: 5px;

        width: 42px;
        height: 42px;

        border: none;
        border-radius: 50%;

        background: var(--gradient);

        color: white;
    }

    /* ================= CART ================= */

    .cart-wrapper {
        position: relative;

        width: 50px;
        height: 50px;

        border-radius: 16px;

        background: white;

        display: flex;
        align-items: center;
        justify-content: center;

        text-decoration: none;

        box-shadow:
            0 8px 18px rgba(0, 0, 0, .06);

        transition: .3s;
    }

    .cart-wrapper:hover {
        transform: translateY(-3px);
    }

    .cart-icon {
        color: var(--primary);
        font-size: 1.2rem;
    }

    .cart-badge {
        position: absolute;

        top: -6px;
        right: -6px;

        min-width: 22px;
        height: 22px;

        border-radius: 999px;

        background: #ef4444;

        color: white;

        font-size: 11px;
        font-weight: 700;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 2px solid white;
    }

    /* ================= USER ================= */

    .user-avatar {
        width: 42px;
        height: 42px;

        border-radius: 50%;

        background: var(--gradient);

        color: white;

        display: flex;
        align-items: center;
        justify-content: center;

        font-weight: 700;
    }

    /* ================= MENU NAVBAR ================= */

    .menu-navbar {
        position: sticky;
        top: 84px;
        z-index: 998;

        background:
            rgba(255, 255, 255, .92);

        backdrop-filter: blur(18px);

        border-bottom:
            1px solid #e5e7eb;
    }

    .menu-wrap {
        position: relative;

        display: flex;
        justify-content: center;
        align-items: center;

        gap: 8px;

        padding: 14px 0;

        overflow-x: auto;
    }

    .menu-wrap::-webkit-scrollbar {
        display: none;
    }

    /* ================= SLIDING BG ================= */

    .menu-indicator {
        position: absolute;

        height: 50px;

        border-radius: 999px;

        background: var(--gradient);

        transition:
            all .35s cubic-bezier(.4, 0, .2, 1);

        z-index: 1;

        box-shadow:
            0 10px 25px rgba(79, 70, 229, .22);
    }

    .menu-link {
        position: relative;
        z-index: 2;

        height: 50px;

        padding: 0 22px;

        border-radius: 999px;

        display: flex;
        align-items: center;
        gap: 8px;

        text-decoration: none;

        color: #334155;

        font-weight: 600;

        white-space: nowrap;

        transition: .25s;
    }

    .menu-link.active {
        color: white;
        font-weight: 700;
    }

    .menu-link.hovering-active {
        color: #334155 !important;
    }

    .menu-link:hover {
        color: white;
    }

    /* ================= DROPDOWN ================= */

    .dropdown-menu {
        border: none;

        border-radius: 20px;

        padding: 12px;

        box-shadow:
            0 20px 40px rgba(0, 0, 0, .12);
    }

    .dropdown-item {
        border-radius: 12px;

        padding: 12px 14px;

        transition: .25s;
    }

    .dropdown-item:hover {
        background: #eef2ff;

        transform: translateX(4px);
    }

    /* ================= MAIN ================= */

    main {
        min-height: calc(100vh - 180px);

        padding-top: 30px;
    }

    /* ================= FOOTER ================= */

    footer {
        margin-top: 80px;

        background:
            linear-gradient(135deg,
                #111827,
                #1e293b);

        color:
            rgba(255, 255, 255, .9);

        padding: 60px 0 40px;
    }

    /* ================= MOBILE ================= */

    @media(max-width: 991px) {

        .search-box {
            display: none;
        }

        .menu-navbar {
            top: 82px;
        }

        .menu-wrap {
            justify-content: flex-start;

            padding-left: 14px;
            padding-right: 14px;
        }

        .menu-link {
            font-size: 14px;

            padding: 0 18px;
        }
    }

    /* ================= SEARCH SUGGEST ================= */

    .search-suggest {

        position: absolute;

        top: 60px;
        left: 0;

        width: 100%;

        background: white;

        border-radius: 20px;

        box-shadow:
            0 15px 40px rgba(0, 0, 0, .12);

        overflow: hidden;

        z-index: 9999;

        max-height: 420px;

        overflow-y: auto;
    }

    .search-item {

        display: flex;
        align-items: center;
        gap: 14px;

        padding: 14px;

        text-decoration: none;

        color: #111827;

        transition: .25s;
    }

    .search-item:hover {

        background: #eef2ff;
    }

    .search-item img {

        width: 60px;
        height: 60px;

        object-fit: cover;

        border-radius: 12px;
    }

    .search-item-name {

        font-weight: 600;
    }

    .search-item-price {

        color: #ef4444;

        font-weight: 700;

        margin-top: 4px;
    }
    </style>
</head>

<body>

    <!-- ================= TOP NAVBAR ================= -->

    <nav class="top-navbar">

        <div class="container">

            <div class="d-flex align-items-center justify-content-between gap-4">

                <!-- LOGO -->
                <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">

                    <i class="fas fa-store-alt"></i>

                    <span>Siêu thị Mini</span>

                </a>

                <!-- SEARCH -->
                <div class="search-box d-none d-lg-block position-relative">

                    <input type="text" class="search-input" id="search-input" placeholder="Tìm kiếm sản phẩm...">

                    <button class="search-btn" type="button">

                        <i class="fas fa-search"></i>

                    </button>

                    <!-- SEARCH SUGGEST -->
                    <div id="search-suggest" class="search-suggest d-none"></div>

                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">

                    @guest

                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">

                        Đăng nhập

                    </a>

                    @else

                    @php
                    $cartQuantity = Session::has('cart')
                    ? array_sum(
                    array_column(
                    Session::get('cart', []),
                    'quantity'
                    )
                    )
                    : \App\Models\UserCartItem
                    ::where('user_id', Auth::id())
                    ->sum('quantity');
                    @endphp

                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="cart-wrapper">

                        <i class="fas fa-shopping-cart cart-icon"></i>

                        <span class="cart-badge">
                            {{ $cartQuantity }}
                        </span>

                    </a>

                    <!-- USER -->
                    <div class="dropdown">

                        <a class="d-flex align-items-center gap-3
                                  text-decoration-none text-dark
                                  dropdown-toggle" href="#" data-bs-toggle="dropdown">

                            <div class="user-avatar">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                            </div>

                            <div class="d-none d-md-block">

                                <div class="fw-bold">

                                    {{ Auth::user()->name }}

                                </div>

                                <small class="text-muted">

                                    @if((int) Auth::user()->role === 1)
                                    Administrator
                                    @else
                                    Customer
                                    @endif

                                </small>

                            </div>

                        </a>

                        <!-- DROPDOWN -->
                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="{{ route('cart.index') }}">

                                    <i class="fas fa-shopping-cart me-2 text-success"></i>

                                    Giỏ hàng

                                </a>
                            </li>

                            @if((int) Auth::user()->role === 1)

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <h6 class="dropdown-header fw-bold text-primary">
                                    Quản lý Admin
                                </h6>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">

                                    <i class="fas fa-chart-line me-2 text-primary"></i>

                                    Dashboard

                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('products.index') }}">

                                    <i class="fas fa-box me-2 text-primary"></i>

                                    Products

                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('coupons.index') }}">

                                    <i class="fas fa-percent me-2 text-primary"></i>

                                    Coupons

                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('user.listUser') }}">

                                    <i class="fas fa-users me-2 text-primary"></i>

                                    Users

                                </a>
                            </li>

                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('signout') }}">

                                    <i class="fas fa-sign-out-alt me-2"></i>

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

    <!-- ================= MENU NAVBAR ================= -->

    <div class="menu-navbar">

        <div class="container">

            <div class="menu-wrap" id="menuWrap">

                <!-- SLIDING BG -->
                <div class="menu-indicator" id="menuIndicator"></div>

                <!-- HOME -->
                <a href="{{ route('home') }}" class="menu-link {{ request()->routeIs('home') ? 'active' : '' }}">

                    <i class="fas fa-house"></i>

                    Trang chủ

                </a>

                <!-- POSTS -->
                <a href="{{ route('posts.index') }}"
                    class="menu-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">

                    <i class="fas fa-tags"></i>

                    Khuyến mãi

                </a>

                @auth

                <!-- SAVED COUPONS -->
                <a href="{{ route('coupons.saved') }}"
                    class="menu-link {{ request()->routeIs('coupons.saved') ? 'active' : '' }}">

                    <i class="fas fa-ticket-alt"></i>

                    Mã giảm giá

                </a>

                <!-- ALL PRODUCTS -->
                <a href="{{ route('products.all') }}"
                    class="menu-link {{ request()->routeIs('products.all') ? 'active' : '' }}">

                    <i class="fas fa-box-open"></i>

                    Tất cả sản phẩm

                </a>

                <!-- CATEGORIES -->
                <a href="{{ route('categories.index') }}"
                    class="menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">

                    <i class="fas fa-layer-group"></i>

                    Danh mục

                </a>

                @endauth

            </div>

        </div>

    </div>

    <!-- ================= MAIN ================= -->

    <main>

        @yield('content')

    </main>

    @include('partials.ads_block')

    <!-- ================= FOOTER ================= -->

    <footer>

        <div class="container text-center">

            <h3 class="fw-bold mb-3">

                <i class="fas fa-store-alt me-2"></i>

                Siêu thị Mini

            </h3>

            <p class="opacity-75">

                Website bán hàng Laravel hiện đại

            </p>

            <p class="mt-4 opacity-75 mb-0">

                © 2026 Siêu thị Mini
                - Design by Trần Cao Trọng

            </p>

        </div>

    </footer>

    <!-- ================= JS ================= -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // ================= NAVBAR SHADOW =================

    window.addEventListener('scroll', () => {

        const navbar =
            document.querySelector('.top-navbar');

        navbar.classList.toggle(
            'scrolled',
            window.scrollY > 30
        );

    });

    // ================= SLIDING MENU =================

    const indicator =
        document.getElementById('menuIndicator');

    const menuLinks =
        document.querySelectorAll('.menu-link');

    const activeLink =
        document.querySelector('.menu-link.active');

    function moveIndicator(el) {

        indicator.style.width =
            `${el.offsetWidth}px`;

        indicator.style.height =
            `${el.offsetHeight}px`;

        indicator.style.left =
            `${el.offsetLeft}px`;

        indicator.style.top =
            `${el.offsetTop}px`;

    }

    // FIRST LOAD
    if (activeLink) {

        moveIndicator(activeLink);

    }

    // HOVER EFFECT
    menuLinks.forEach(link => {

        link.addEventListener('mouseenter', () => {

            moveIndicator(link);

            if (
                activeLink &&
                link !== activeLink
            ) {

                activeLink.classList.add(
                    'hovering-active'
                );

            }

        });

        link.addEventListener('mouseleave', () => {

            if (activeLink) {

                moveIndicator(activeLink);

                activeLink.classList.remove(
                    'hovering-active'
                );

            }

        });

    });

    // RESIZE
    window.addEventListener('resize', () => {

        if (activeLink) {

            moveIndicator(activeLink);

        }

    });

    const searchInput =
        document.getElementById('search-input');

    const searchSuggest =
        document.getElementById('search-suggest');

    searchInput.addEventListener('keyup', function() {

        let keyword = this.value.trim();

        if (keyword.length <= 0) {

            searchSuggest.classList.add('d-none');

            return;
        }

        fetch(`/search-products?q=${encodeURIComponent(keyword)}`)

            .then(res => res.json())

            .then(data => {

                if (data.length <= 0) {

                    searchSuggest.innerHTML = `
                <div class="p-3 text-muted">
                    Không tìm thấy sản phẩm
                </div>
            `;

                    searchSuggest.classList.remove('d-none');

                    return;
                }

                let html = '';

                data.forEach(product => {

                    html += `

            <a href="/san-pham/${product.id}"
               class="search-item">

<img src="{{ asset('images') }}/${product.image}"
                     alt="${product.name}">

                <div>

                    <div class="search-item-name">
                        ${product.name}
                    </div>

                    <div class="search-item-price">
                        ${Number(product.price).toLocaleString()}đ
                    </div>

                </div>

            </a>

            `;
                });

                searchSuggest.innerHTML = html;

                searchSuggest.classList.remove('d-none');

            });

    });

    // CLICK OUTSIDE
    document.addEventListener('click', function(e) {

        if (
            !searchInput.contains(e.target) &&
            !searchSuggest.contains(e.target)
        ) {

            searchSuggest.classList.add('d-none');
        }

    });
    </script>
    @stack('scripts')

</body>

</html>