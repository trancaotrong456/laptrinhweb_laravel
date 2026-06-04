<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Siêu thị trực tuyến')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/store.css') }}" rel="stylesheet">
    <link href="{{ asset('css/account-dropdown.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="topbar">
        🌿 Miễn phí vận chuyển đơn hàng từ 300.000đ — <a href="{{ route('products.all') }}">Mua sắm ngay</a>
    </div>
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="site-logo">
                    <div class="logo-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <span>Siêu thị<br><small style="font-size:11px;font-weight:500;color:#9e9e9e;line-height:1;">Trực
                            tuyến</small></span>
                </a>
                <div class="header-search d-none d-lg-block">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
                    <button class="search-btn" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                    <div class="search-suggest" id="searchSuggest"></div>
                </div>
                <div class="header-actions">
                    @guest
                    <a href="{{ route('login') }}" class="haction-btn">
                        <i class="far fa-user"></i>
                        <span>Đăng nhập</span>
                    </a>
                    @else
                    <a href="{{ route('cart.index') }}" class="haction-btn" style="position:relative;">
                        @php
                        $cartQuantity = Session::has('cart')
                        ? array_sum(array_column(Session::get('cart', []), 'quantity'))
                        : \App\Models\UserCartItem::where('user_id', Auth::id())->sum('quantity');
                        @endphp
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                        <span class="hbadge cart-badge">{{ $cartQuantity }}</span>
                    </a>
                    <div class="user-drop" data-user-drop>
                        <button type="button" class="haction-btn user-drop-trigger" data-user-drop-trigger
                            aria-haspopup="true" aria-expanded="false"
                            style="min-height:44px; padding:10px 16px; display:flex; align-items:center; gap:8px; border-radius:12px; width:auto;">

                            <i class="far fa-user-circle"></i>
                            <span>{{ Str::limit(Auth::user()->name, 10) }}</span>
                        </button>

                        <div class="user-drop-menu" data-user-drop-menu>


                            <div style="padding:8px 12px 4px;">
                                <div style="font-weight:700;font-size:13.5px;">{{ Auth::user()->name }}</div>
                                <div style="font-size:12px;color:#9e9e9e;">
                                    @if((int) Auth::user()->role === 1) Administrator @else Khách hàng @endif
                                </div>
                            </div>
                            <div class="udrop-divider"></div>
                            <a href="{{ route('cart.index') }}" class="udrop-item">
                                <i class="fas fa-shopping-cart"></i> Giỏ hàng
                            </a>
                            <a href="{{ route('orders.user') }}" class="udrop-item">
                                <i class="fas fa-box"></i> Đơn hàng của tôi
                            </a>


                            <a href="{{ route('coupons.saved') }}" class="udrop-item">
                                <i class="fas fa-ticket-alt"></i> Mã giảm giá
                            </a>
                            @if((int) Auth::user()->role === 1)
                            <div class="udrop-divider"></div>
                            <div class="udrop-header">Quản trị</div>
                            <a href="{{ route('dashboard') }}" class="udrop-item">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" class="udrop-item">
                                <i class="fas fa-box-open"></i> Sản phẩm
                            </a>
                            <a href="{{ route('categories.index', ['manage' => 1]) }}" class="udrop-item">
                                <i class="fas fa-tags"></i> Danh mục
                            </a>
                            <a href="{{ route('coupons.index') }}" class="udrop-item">
                                <i class="fas fa-percent"></i> Mã giảm giá
                            </a>
                            <a href="{{ route('user.listUser') }}" class="udrop-item">
                                <i class="fas fa-users"></i> Người dùng
                            </a>
                            @endif
                            <div class="udrop-divider"></div>
                            <a href="{{ route('signout') }}" class="udrop-item danger">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
            <div class="d-lg-none pb-2" style="position:relative;">
                <input type="text" id="searchInputMobile" placeholder="Tìm kiếm..." autocomplete="off"
                    style="width:100%;height:38px;border:1.5px solid #e0e0e0;border-radius:8px;padding:0 44px 0 14px;font-size:13px;outline:none;font-family:inherit;">
                <button
                    style="position:absolute;right:6px;top:50%;transform:translateY(-50%);width:28px;height:28px;background:#2e7d32;border:none;border-radius:6px;color:white;cursor:pointer;">
                    <i class="fas fa-search" style="font-size:12px;"></i>
                </button>
            </div>
        </div>
        <div class="cat-nav">
            <div class="container">
                <div class="cat-nav-inner">
                    <a href="{{ route('home') }}" class="cat-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-house"></i>
                        Trang chủ
                    </a>
                    <a href="{{ route('products.all') }}"
                        class="cat-nav-link {{ request()->routeIs('products.all') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        Tất cả sản phẩm
                    </a>
                    @auth
                    <a href="{{ route('posts.index') }}"
                        class="cat-nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Tin Tức & Khuyến Mãi
                    </a>
                    <a href="{{ route('coupons.saved') }}"
                        class="cat-nav-link {{ request()->routeIs('coupons.saved') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt"></i>
                        Mã giảm giá
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="cat-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        Danh mục
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    <main class="main-wrap">
        @yield('content')
    </main>
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-logo">
                        <i class="fas fa-leaf"></i>
                        Siêu thị <span>Trực tuyến</span>
                    </div>
                    <p class="footer-desc">
                        © {{ date('Y') }} Siêu thị trực tuyến. Tươi ngon mỗi ngày, giao hàng tận nơi nhanh chóng và tiết
                        kiệm.
                    </p>
                    <div class="footer-social mt-3">
                        <a href="#" class="fsocial-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="fsocial-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="fsocial-btn"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="fsocial-btn"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 footer-col">
                    <h6>Thông tin</h6>
                    <ul>
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Hệ thống cửa hàng</a></li>
                        <li><a href="{{ route('posts.index') }}">Tin tức & khuyến mãi</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 footer-col">
                    <h6>Hỗ trợ khách hàng</h6>
                    <ul>
                        <li><a href="#">Chính sách giao hàng</a></li>
                        <li><a href="#">Trang thông tin hỗ trợ</a></li>
                        <li><a href="#">Bảo mật thông tin</a></li>
                        <li><a href="#">Đổi trả & Hoàn tiền</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 footer-col">
                    <h6>Liên hệ</h6>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>1800 1234</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>hotro@sieuthi.vn</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>TP. Hồ Chí Minh, Việt Nam</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 footer-col">
                    <h6>Tải ứng dụng</h6>
                    <p style="font-size:12.5px;color:rgba(255,255,255,.45);margin-bottom:10px;">
                        Đăng ký nhận tin khuyến mãi mới nhất
                    </p>
                    <div class="footer-apps">
                        <a href="#" class="footer-app-btn">
                            <i class="fab fa-google-play"></i>
                            <div>
                                <div style="font-size:10px;opacity:.7;">Tải trên</div>
                                <div style="font-weight:700;font-size:12px;">Google Play</div>
                            </div>
                        </a>
                        <a href="#" class="footer-app-btn">
                            <i class="fab fa-apple"></i>
                            <div>
                                <div style="font-size:10px;opacity:.7;">Tải trên</div>
                                <div style="font-weight:700;font-size:12px;">App Store</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} Siêu thị trực tuyến — Design by Trần Cao Trọng</span>
                <span>Chính sách bảo mật · Điều khoản sử dụng</span>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // ── LIVE SEARCH ──────────────────────────────────────────
    function setupSearch(inputId, suggestId) {
        const input = document.getElementById(inputId);
        const suggest = document.getElementById(suggestId);
        if (!input) return;
        let timer;
        input.addEventListener('keyup', function() {
            clearTimeout(timer);
            const kw = this.value.trim();
            if (!kw) {
                if (suggest) suggest.classList.remove('open');
                return;
            }
            timer = setTimeout(() => {
                fetch(`/search-products?q=${encodeURIComponent(kw)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (!suggest) return;
                        if (!data.length) {
                            suggest.innerHTML =
                                `<div style="padding:16px;color:#9e9e9e;text-align:center;">Không tìm thấy sản phẩm</div>`;
                        } else {
                            suggest.innerHTML = data.map(p => `
                                <a href="/san-pham/${p.id}" class="suggest-item">
                                    <img src="{{ asset('images') }}/${p.image}" alt="${p.name}" onerror="this.src='data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;48&quot; height=&quot;48&quot;><rect fill=&quot;%23f5f5f5&quot; width=&quot;48&quot; height=&quot;48&quot;/></svg>'">
                                    <div>
                                        <div class="sname">${p.name}</div>
                                        <div class="sprice">${Number(p.price).toLocaleString('vi-VN')}đ</div>
                                    </div>
                                </a>`).join('');
                        }
                        suggest.classList.add('open');
                    })
                    .catch(() => {});
            }, 300);
        });
        document.addEventListener('click', e => {
            if (suggest && !input.contains(e.target) && !suggest.contains(e.target)) {
                suggest.classList.remove('open');
            }
        });
    }
    setupSearch('searchInput', 'searchSuggest');
    </script>
    @stack('scripts')

    <script>
    (function() {
        const root = document.querySelector('[data-user-drop]');
        if (!root) return;

        const trigger = root.querySelector('[data-user-drop-trigger]');
        const menu = root.querySelector('[data-user-drop-menu]');
        if (!trigger || !menu) return;

        function close() {
            root.classList.remove('openUserDrop');
        }

        function open() {
            root.classList.add('openUserDrop');
        }

        function toggle(e) {
            // Toggle only when clicking the trigger
            e.preventDefault();
            e.stopPropagation();
            if (root.classList.contains('openUserDrop')) close();
            else open();
        }

        trigger.addEventListener('click', toggle);

        document.addEventListener('click', function(e) {
            if (!root.contains(e.target)) close();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') close();
        });
    })();
    </script>
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
    {{-- ── SCRIPT AJAX CHO GIỎ HÀNG VÀ MÃ GIẢM GIÁ ── --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý Thêm vào giỏ hàng (AJAX)
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    if(data.success) {
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: data.message, showConfirmButton: false, timer: 2500, timerProgressBar: true
                        });
                        const badge = document.querySelector('.cart-badge');
                        if(badge && data.cartCount !== undefined) {
                            badge.textContent = data.cartCount;
                            badge.classList.add('pop-animation');
                            setTimeout(() => badge.classList.remove('pop-animation'), 300);
                        }
                    } else {
                        if (data.redirect) window.location.href = data.redirect;
                        else Swal.fire({
                            toast: true, position: 'top-end', icon: 'error',
                            title: data.message || 'Có lỗi xảy ra!', showConfirmButton: false, timer: 2500, timerProgressBar: true
                        });
                    }
                })
                .catch(err => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    console.error(err);
                });
            });
        });

        // Xử lý Lưu mã giảm giá (AJAX)
        document.querySelectorAll('.save-coupon-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    if(data.success) {
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: data.message, showConfirmButton: false, timer: 2500, timerProgressBar: true
                        });
                        if (btn.classList.contains('btn-collect')) {
                            btn.classList.remove('btn-collect');
                            btn.classList.add('btn-saved');
                            btn.textContent = 'Đã lưu';
                        } else if (btn.classList.contains('btn-saved')) {
                            btn.classList.remove('btn-saved');
                            btn.classList.add('btn-collect');
                            btn.textContent = 'Lưu mã';
                        }
                    } else {
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'error',
                            title: data.message || 'Có lỗi xảy ra!', showConfirmButton: false, timer: 2500, timerProgressBar: true
                        });
                    }
                })
                .catch(err => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    console.error(err);
                });
            });
        });
    });
    </script>
    <style>
        .pop-animation {
            animation: pop 0.3s ease;
        }
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }
    </style>

    {{-- ── CHATBOX AI ── --}}
    @include('layouts.chatbox')

</body>


</html>