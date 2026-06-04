<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Be Vietnam Pro', sans-serif;
    }

    body {
        background-color: #f4f7fe;
        color: #2b3674;
        display: flex;
        min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
        width: 260px;
        background: #111c43;
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        transition: all 0.3s;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sb-logo-icon {
        width: 40px;
        height: 40px;
        background: #2563eb;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .sb-logo-text {
        line-height: 1.2;
    }

    .sb-logo-text h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 0.5px;
    }

    .sb-logo-text span {
        font-size: 11px;
        color: #94a3b8;
    }

    .menu-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        padding: 24px 24px 8px;
        letter-spacing: 1px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0 12px;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 4px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: #94a3b8;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: 0.3s;
    }

    .sidebar-link i {
        width: 24px;
        font-size: 16px;
        margin-right: 8px;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    .sidebar-link.active {
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* MAIN CONTENT */
    .main-wrapper {
        flex: 1;
        margin-left: 260px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* TOPBAR */
    .topbar {
        height: 70px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .btn-view-site {
        background: #eff6ff;
        color: #2563eb;
        border: none;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }

    .user-profile img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* CONTENT */
    .content {
        padding: 24px;
        flex: 1;
    }
    </style>
    @stack('styles')
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sb-logo-icon">
                <i class="fas fa-chart-pie text-white"></i>
            </div>
            <div class="sb-logo-text">
                <h4>TTP ADMIN</h4>
                <span>Quản trị hệ thống</span>
            </div>
        </div>
        <div class="menu-title">MENU CHÍNH</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}"
                    class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Sản phẩm
                </a>
            </li>
            <li>
                <a href="{{ route('posts.index') }}"
                    class="sidebar-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <i class="fas fa-bolt"></i> Khuyến mãi
                </a>
            </li>
            <li>
                <a href="{{ route('coupons.index') }}"
                    class="sidebar-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Coupons
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}"
                    class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Danh mục
                </a>
            </li>
            <li>
                <a href="{{ route('user.listUser') }}"
                    class="sidebar-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Người dùng
                </a>
            </li>
        </ul>
    </aside>
    <!-- MAIN -->
    <div class="main-wrapper">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <h4 style="margin:0;font-size:20px;font-weight:800;color:#2563eb;">
                    <i class="fas fa-user-shield me-2"></i>TTP Admin
                </h4>
            </div>
            <div class="topbar-right">
                <a href="{{ route('home') }}" class="btn-view-site">
                    <i class="fas fa-globe"></i> Xem website
                </a>

                <div class="dropdown">
                    <div class="user-profile" data-bs-toggle="dropdown">
                        <span style="font-size:13px;color:#64748b;">Xin chào,</span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e2e8f0&color=1e293b"
                            alt="Avatar">
                        <span style="font-size:14px;font-weight:700;">{{ Auth::user()->name }} <i
                                class="fas fa-chevron-down ms-1" style="font-size:10px;"></i></span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                        style="border-radius:12px;margin-top:10px;">
                        <li><a class="dropdown-item" href="{{ route('signout') }}"><i
                                    class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>
    </div>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>