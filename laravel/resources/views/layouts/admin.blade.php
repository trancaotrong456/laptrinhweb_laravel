<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Siêu Thị Dashboard')</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    :root {
        --sidebar-w: 255px;
        --sidebar-bg: #0f172a;
        --sidebar-hover: rgba(255,255,255,0.06);
        --sidebar-active: #2563eb;
        --accent: #2563eb;
        --accent-light: #3b82f6;
        --accent-pale: #eff6ff;
        --bg: #f1f5f9;
        --white: #ffffff;
        --text: #1e293b;
        --text-soft: #64748b;
        --text-light: #94a3b8;
        --border: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow: 0 4px 16px rgba(0,0,0,.08);
        --shadow-lg: 0 10px 40px rgba(0,0,0,.12);
        --radius: 12px;
        --transition: all .22s cubic-bezier(.4,0,.2,1);
        --red: #ef4444;
        --green: #10b981;
        --yellow: #f59e0b;
        --purple: #8b5cf6;
        --orange: #f97316;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Be Vietnam Pro', sans-serif; }

    body {
        background: var(--bg);
        color: var(--text);
        display: flex;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ═══════════════════════════════════════════════════════
       SIDEBAR
    ═══════════════════════════════════════════════════════ */
    .admin-sidebar {
        width: var(--sidebar-w);
        background: var(--sidebar-bg);
        color: #fff;
        position: fixed;
        left: 0; top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
    }

    .admin-sidebar::-webkit-scrollbar { width: 4px; }
    .admin-sidebar::-webkit-scrollbar-track { background: transparent; }
    .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

    /* Logo */
    .sb-logo {
        padding: 20px 20px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        flex-shrink: 0;
    }
    .sb-logo-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; color: #fff;
        box-shadow: 0 4px 12px rgba(37,99,235,.4);
        flex-shrink: 0;
    }
    .sb-logo-text { line-height: 1.25; }
    .sb-logo-text strong {
        display: block; font-size: 15px; font-weight: 800;
        color: #fff; letter-spacing: .5px;
    }
    .sb-logo-text span { font-size: 11px; color: var(--text-light); }

    /* Menu group */
    .sb-group { padding: 18px 14px 0; }
    .sb-group-title {
        font-size: 10.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1.2px;
        color: #475569; padding: 0 8px 8px; margin-bottom: 2px;
    }
    .sb-nav { list-style: none; padding: 0; margin: 0 0 4px; }
    .sb-nav li { margin-bottom: 2px; }

    .sb-link {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px;
        color: #94a3b8; text-decoration: none;
        border-radius: 8px; font-size: 13.5px; font-weight: 500;
        transition: var(--transition); position: relative;
    }
    .sb-link .sb-icon {
        width: 32px; height: 32px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
        background: rgba(255,255,255,.05);
        transition: var(--transition);
    }
    .sb-link:hover {
        background: var(--sidebar-hover);
        color: #e2e8f0;
    }
    .sb-link:hover .sb-icon { background: rgba(255,255,255,.1); }
    .sb-link.active {
        background: linear-gradient(90deg, rgba(37,99,235,.25), rgba(37,99,235,.08));
        color: #fff;
    }
    .sb-link.active .sb-icon {
        background: var(--sidebar-active);
        box-shadow: 0 3px 10px rgba(37,99,235,.5);
        color: #fff;
    }
    .sb-link.active::before {
        content: '';
        position: absolute; left: 0; top: 6px; bottom: 6px;
        width: 3px; background: var(--sidebar-active);
        border-radius: 0 3px 3px 0;
    }
    .sb-badge {
        margin-left: auto;
        background: var(--red); color: #fff;
        font-size: 10px; font-weight: 700;
        padding: 2px 7px; border-radius: 50px;
        min-width: 20px; text-align: center;
    }

    /* Bottom links */
    .sb-bottom {
        margin-top: auto;
        padding: 14px 14px 18px;
        border-top: 1px solid rgba(255,255,255,.06);
        flex-shrink: 0;
    }
    .sb-bottom-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px;
        color: #64748b; text-decoration: none;
        border-radius: 8px; font-size: 13px; font-weight: 500;
        transition: var(--transition); margin-bottom: 2px;
    }
    .sb-bottom-link:hover { background: var(--sidebar-hover); color: #94a3b8; }
    .sb-bottom-link.danger { color: #f87171; }
    .sb-bottom-link.danger:hover { background: rgba(239,68,68,.12); color: #ef4444; }

    /* ═══════════════════════════════════════════════════════
       MAIN WRAPPER
    ═══════════════════════════════════════════════════════ */
    .admin-main {
        flex: 1;
        margin-left: var(--sidebar-w);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ═══════════════════════════════════════════════════════
       TOPBAR
    ═══════════════════════════════════════════════════════ */
    .admin-topbar {
        height: 66px;
        background: var(--white);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        position: sticky; top: 0; z-index: 900;
        box-shadow: var(--shadow-sm);
    }
    .topbar-brand {
        display: flex; align-items: center; gap: 10px;
    }
    .topbar-brand .brand-icon {
        color: var(--accent); font-size: 18px;
    }
    .topbar-brand h5 {
        margin: 0; font-size: 17px; font-weight: 800; color: var(--accent);
    }
    .topbar-actions {
        display: flex; align-items: center; gap: 14px;
    }
    .topbar-btn-site {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--accent-pale); color: var(--accent);
        border: 1.5px solid #bfdbfe;
        padding: 7px 16px; border-radius: 50px;
        font-size: 13px; font-weight: 600;
        text-decoration: none; transition: var(--transition);
    }
    .topbar-btn-site:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

    .topbar-avatar {
        display: flex; align-items: center; gap: 10px;
        cursor: pointer; padding: 6px 12px;
        border-radius: 10px; transition: var(--transition);
    }
    .topbar-avatar:hover { background: var(--bg); }
    .topbar-avatar img {
        width: 36px; height: 36px;
        border-radius: 10px; object-fit: cover;
        border: 2px solid var(--border);
    }
    .topbar-avatar .av-info { line-height: 1.3; }
    .topbar-avatar .av-hello { font-size: 11px; color: var(--text-soft); }
    .topbar-avatar .av-name { font-size: 13.5px; font-weight: 700; color: var(--text); }

    /* ═══════════════════════════════════════════════════════
       CONTENT
    ═══════════════════════════════════════════════════════ */
    .admin-content { padding: 28px; flex: 1; }

    /* ═══════════════════════════════════════════════════════
       SHARED COMPONENTS
    ═══════════════════════════════════════════════════════ */
    .admin-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }
    .admin-card-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .admin-card-title {
        font-size: 15px; font-weight: 700; color: var(--text);
        display: flex; align-items: center; gap: 8px; margin: 0;
    }
    .admin-card-body { padding: 22px; }

    /* Stat cards */
    .stat-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 20px 22px;
        box-shadow: var(--shadow-sm);
        display: flex; align-items: center; gap: 16px;
        transition: var(--transition);
        text-decoration: none; color: inherit;
    }
    .stat-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }
    .stat-icon {
        width: 52px; height: 52px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .stat-info { flex: 1; min-width: 0; }
    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-soft); text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 26px; font-weight: 800; color: var(--text); line-height: 1.2; margin-top: 2px; }
    .stat-sub { font-size: 12px; color: var(--text-light); margin-top: 2px; }

    /* Admin tables */
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table thead th {
        font-size: 11.5px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--text-soft);
        padding: 12px 16px; background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .admin-table tbody td {
        padding: 13px 16px; font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: var(--text);
    }
    .admin-table tbody tr:last-child td { border-bottom: none; }
    .admin-table tbody tr:hover td { background: #fafbff; }

    /* Badges */
    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 50px;
        font-size: 11.5px; font-weight: 600; white-space: nowrap;
    }
    .badge-status.pending   { background: #fef3c7; color: #92400e; }
    .badge-status.packing   { background: #dbeafe; color: #1e40af; }
    .badge-status.shipping  { background: #ede9fe; color: #5b21b6; }
    .badge-status.delivered { background: #d1fae5; color: #065f46; }
    .badge-status.cancelled { background: #fee2e2; color: #991b1b; }
    .badge-status.user      { background: #dbeafe; color: #1e40af; }
    .badge-status.admin     { background: #fde8d8; color: #9a3412; }
    .badge-status.active    { background: #d1fae5; color: #065f46; }
    .badge-status.inactive  { background: #fee2e2; color: #991b1b; }

    /* Icon buttons */
    .icon-btn {
        width: 32px; height: 32px; border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--border); background: var(--white);
        color: var(--text-soft); font-size: 13px;
        cursor: pointer; text-decoration: none;
        transition: var(--transition);
    }
    .icon-btn:hover         { background: var(--accent-pale); border-color: #bfdbfe; color: var(--accent); }
    .icon-btn.edit:hover    { background: #fff7ed; border-color: #fed7aa; color: var(--orange); }
    .icon-btn.delete:hover  { background: #fee2e2; border-color: #fecaca; color: var(--red); }
    .icon-btn.promote:hover { background: #fef3c7; border-color: #fde68a; color: var(--yellow); }

    /* Form controls */
    .form-ctrl {
        height: 40px; padding: 0 14px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13.5px; font-family: inherit; color: var(--text);
        background: var(--white); outline: none;
        transition: var(--transition); width: 100%;
    }
    .form-ctrl:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }

    /* Buttons */
    .btn-primary-admin {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--accent); color: #fff;
        border: none; padding: 9px 20px;
        border-radius: 8px; font-size: 13.5px; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: var(--transition);
    }
    .btn-primary-admin:hover { background: #1d4ed8; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }

    .btn-outline-admin {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--white); color: var(--text-soft);
        border: 1.5px solid var(--border); padding: 8px 18px;
        border-radius: 8px; font-size: 13.5px; font-weight: 500;
        cursor: pointer; text-decoration: none;
        transition: var(--transition);
    }
    .btn-outline-admin:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-pale); }

    /* Alert */
    .admin-alert {
        padding: 12px 16px; border-radius: 8px; font-size: 13.5px;
        display: flex; align-items: center; gap: 9px; margin-bottom: 18px;
    }
    .admin-alert.success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .admin-alert.error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    /* Avatar circle */
    .user-avatar {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }

    /* Page header */
    .page-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
    }
    .page-title {
        font-size: 22px; font-weight: 800; color: var(--text);
        display: flex; align-items: center; gap: 10px; margin: 0;
    }
    .page-sub { font-size: 13px; color: var(--text-soft); margin-top: 4px; }

    /* ── GLOBAL FORMS ────────────────────────────────────── */
    .form-ctrl {
        width: 100%; height: 44px; padding: 0 16px;
        border: 1.5px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; color: #1e293b; background: #fff;
        transition: all .2s; font-family: inherit;
    }
    .form-ctrl:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); outline: none; }
    .form-select-ctrl {
        width: 100%; height: 44px; padding: 0 16px;
        border: 1.5px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; color: #1e293b; background: #fff;
        transition: all .2s; font-family: inherit;
    }
    .form-select-ctrl:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); outline: none; }
    textarea.form-ctrl { height: auto; padding: 12px 16px; }
    .form-label-ctrl {
        display: block; font-size: 13px; font-weight: 600;
        color: #475569; margin-bottom: 6px;
    }
    </style>
    @stack('styles')
</head>

<body>
    <!-- ═══════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════ -->
    <aside class="admin-sidebar">
        <!-- Logo -->
        <div class="sb-logo">
            <div class="sb-logo-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="sb-logo-text">
                <strong>Siêu Thị<span style="color:green;"> ADMIN</span></strong>
                <span>Quản trị hệ thống</span>
            </div>
        </div>

        <!-- Menu chính -->
        <div class="sb-group">
            <div class="sb-group-title">Menu chính</div>
            <ul class="sb-nav">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-home"></i></span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}"
                        class="sb-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-box-open"></i></span>
                        Sản phẩm
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.flash-sales.index') }}"
                        class="sb-link {{ request()->is('admin/flash-sales*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-bolt"></i></span>
                        Flash Sale
                    </a>
                </li>
                <li>
                    <a href="{{ route('coupons.index') }}"
                        class="sb-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-ticket-alt"></i></span>
                        Coupons
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index', ['manage' => 1]) }}"
                        class="sb-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-tags"></i></span>
                        Danh mục
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}"
                        class="sb-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-shopping-bag"></i></span>
                        Đơn hàng
                        @php
                            $pendingCount = 0;
                            try {
                                $pendingCount = \App\Models\Order::where('status','pending')->count();
                            } catch(\Exception $e) {}
                        @endphp
                        @if($pendingCount > 0)
                        <span class="sb-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.listUser') }}"
                        class="sb-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                        <span class="sb-icon"><i class="fas fa-users"></i></span>
                        Người dùng
                    </a>
                </li>
            </ul>
        </div>

        <!-- Bottom -->
        <div class="sb-bottom">
            <a href="{{ route('home') }}" class="sb-bottom-link" target="_blank">
                <i class="fas fa-globe" style="width:16px;text-align:center;"></i>
                Xem website
                <i class="fas fa-external-link-alt" style="font-size:10px;margin-left:auto;opacity:.5;"></i>
            </a>
            <a href="{{ route('signout') }}" class="sb-bottom-link danger">
                <i class="fas fa-sign-out-alt" style="width:16px;text-align:center;"></i>
                Đăng xuất
            </a>
        </div>
    </aside>

    <!-- ═══════════════════════════════════
         MAIN
    ═══════════════════════════════════ -->
    <div class="admin-main">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <!-- LOGO SIÊU THỊ -->
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
                <div style="width:42px;height:42px;background:#2e7d32;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;">
                    <i class="fas fa-leaf"></i>
                </div>
                <div style="line-height:1.2;">
                    <div style="font-size:18px;font-weight:800;color:#2e7d32;">Siêu thị</div>
                    <div style="font-size:12px;color:#757575;">Trực tuyến</div>
                </div>
            </a>

            <!-- GÓC PHẢI (AVATAR) -->
            <div class="topbar-actions">
                <div class="dropdown">
                    <div class="topbar-avatar" data-bs-toggle="dropdown" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=dbeafe&color=1e40af&bold=true&size=72"
                            alt="Avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                        <div class="av-info" style="line-height:1.2; text-align:left;">
                            <div class="av-name" style="font-size:14px;font-weight:700;color:#1e293b;">Admin <i class="fas fa-chevron-down ms-1" style="font-size:9px;"></i></div>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                        style="border-radius:12px;margin-top:8px;min-width:180px;">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('signout') }}">
                                <i class="fas fa-sign-out-alt me-2 text-danger"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="admin-content">
            @yield('content')
        </div>

        <!-- FOOTER -->
        <footer class="admin-footer" style="background:#1a1b26;color:#a0aec0;padding:40px 28px;margin-top:auto;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:30px;margin-bottom:30px;padding-bottom:30px;border-bottom:1px solid rgba(255,255,255,0.1);">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;color:#fff;font-weight:700;font-size:16px;margin-bottom:16px;">
                        <i class="fas fa-leaf" style="color:#4ade80;"></i> Siêu thị <span style="color:#4ade80;">Trực tuyến</span>
                    </div>
                    <div style="font-size:13px;line-height:1.6;margin-bottom:16px;">
                        © 2026 Siêu thị trực tuyến. Tươi ngon mỗi ngày, giao hàng tận nơi nhanh chóng và tiết kiệm.
                    </div>
                    <div style="display:flex;gap:10px;">
                        <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;border-radius:8px;color:#fff;text-decoration:none;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;border-radius:8px;color:#fff;text-decoration:none;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;border-radius:8px;color:#fff;text-decoration:none;"><i class="fab fa-youtube"></i></a>
                        <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;border-radius:8px;color:#fff;text-decoration:none;"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div>
                    <h6 style="color:#fff;font-size:13px;font-weight:700;margin-bottom:16px;text-transform:uppercase;">Thông tin</h6>
                    <ul style="list-style:none;padding:0;margin:0;font-size:13px;display:flex;flex-direction:column;gap:12px;">
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Về chúng tôi</a></li>
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Hệ thống cửa hàng</a></li>
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Tin tức & khuyến mãi</a></li>
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h6 style="color:#fff;font-size:13px;font-weight:700;margin-bottom:16px;text-transform:uppercase;">Hỗ trợ khách hàng</h6>
                    <ul style="list-style:none;padding:0;margin:0;font-size:13px;display:flex;flex-direction:column;gap:12px;">
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Chính sách giao hàng</a></li>
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Trang thông tin hỗ trợ</a></li>
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Bảo mật thông tin</a></li>
                        <li><a href="#" style="color:#a0aec0;text-decoration:none;">Đổi trả & Hoàn tiền</a></li>
                    </ul>
                </div>
                <div>
                    <h6 style="color:#fff;font-size:13px;font-weight:700;margin-bottom:16px;text-transform:uppercase;">Tải ứng dụng</h6>
                    <div style="font-size:12px;margin-bottom:12px;">Đăng ký nhận tin khuyến mãi mới nhất</div>
                    <div style="margin-top:16px;">
                        <div style="background:rgba(255,255,255,0.05);padding:10px 14px;border-radius:8px;display:flex;align-items:center;gap:10px;margin-bottom:10px;cursor:pointer;">
                            <i class="fab fa-google-play" style="font-size:24px;color:#fff;"></i>
                            <div style="line-height:1.2;">
                                <div style="font-size:10px;color:#a0aec0;">Tải trên</div>
                                <div style="font-size:13px;color:#fff;font-weight:700;">Google Play</div>
                            </div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05);padding:10px 14px;border-radius:8px;display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <i class="fab fa-apple" style="font-size:24px;color:#fff;"></i>
                            <div style="line-height:1.2;">
                                <div style="font-size:10px;color:#a0aec0;">Tải trên</div>
                                <div style="font-size:13px;color:#fff;font-weight:700;">App Store</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;flex-wrap:wrap;gap:10px;">
                <div>© 2026 Siêu thị trực tuyến — Design by Trần Cao Trọng</div>
                <div><a href="#" style="color:#a0aec0;text-decoration:none;">Chính sách bảo mật</a> · <a href="#" style="color:#a0aec0;text-decoration:none;">Điều khoản sử dụng</a></div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
    </script>
    @endif
    @if(session('error'))
    <script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: '{{ session("error") }}',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
    </script>
    @endif
    @stack('scripts')
</body>

</html>