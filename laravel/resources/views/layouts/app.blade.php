<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siêu thị Mini')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/products.css') }}" rel="stylesheet">
    <style>
    * {
        font-family: 'Inter', sans-serif;
    }

    .navbar {
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
        padding: 1rem 0;
        position: sticky;
        top: 0;
        z-index: 1030;
        transition: all .3s cubic-bezier(.4, 0, .2, 1);
    }

    .navbar.scrolled {
        padding: .75rem 0;
        box-shadow: 0 2px 20px rgba(0, 0, 0, .1);
    }

    .navbar-brand {
        font-size: 1.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .nav-link {
        color: #555 !important;
        font-weight: 500;
        padding: .75rem 1.5rem !important;
        border-radius: 50px;
        margin: 0 .25rem;
        position: relative;
        overflow: hidden;
        transition: all .3s ease;
    }

    .nav-link:hover {
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, .3);
    }

    .cart-container {
        position: relative;
        padding: 6px 10px !important;
        border-radius: 20px;
        transition: 0.2s ease;
    }

    .cart-container:hover {
        background: #f5f5f5;
    }

    .cart-btn {
        color: #333 !important;
        font-size: 1.25rem;
        padding: 0;
        transition: 0.2s ease;
        display: inline-block;
    }

    .cart-container:hover .cart-btn {
        color: #2500f5 !important;
    }

    .cart-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #ee4d2d;
        color: #fff;
        border-radius: 999px;
        min-width: 18px;
        height: 18px;
        font-size: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        line-height: 1;
    }

    main {
        min-height: calc(100vh - 80px);
        padding-top: 80px;
    }

    footer {
        margin-top: 0;
        
    }

    .user-dropdown .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: 1rem 1.5rem;
        border-radius: 50px;
        transition: all .3s ease;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, .3);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: 1rem;
        min-width: 280px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, .95);
    }
    </style>
</head>

<body class="position-relative">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light py-0 px-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-3 lh-1" href="{{ route('home') }}">
                <i class="fas fa-store-alt me-2 text-primary fs-2"></i>
                <span class="d-none d-lg-inline">Siêu thị Mini</span>
            </a>

            <button class="navbar-toggler border-0 p-2 rounded-3 shadow-sm" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent">
                <i class="fas fa-bars text-primary fs-5"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-boxes me-1"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">
                            <i class="fas fa-tags me-1"></i>Khuyến mãi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-list me-1"></i>Danh mục
                        </a>
                    </li>
                </ul>

                <!-- ĐÃ XÓA FORM TÌM KIẾM Ở ĐÂY -->

                <div class="d-flex align-items-center gap-3">
                    @guest
                    <div class="guest-buttons d-none d-lg-flex">
                        <a class="btn btn-outline-primary rounded-pill px-4 py-2" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                        </a>
                        <a class="btn btn-primary rounded-pill px-4 py-2" href="{{ route('user.createUser') }}">
                            <i class="fas fa-user-plus me-1"></i>Đăng ký
                        </a>
                    </div>
                    @else
                    <a href="{{ route('cart.index') }}"
                        class="cart-container text-gray-700 hover:text-blue-600 transition d-flex align-items-center">
                        <i class="fas fa-shopping-cart text-xl md:text-2xl cart-btn"></i>
                        <span class="cart-badge">
                            {{ Session::has('cart') ? array_sum(array_column(Session::get('cart', []), 'quantity')) : 0 }}
                        </span>
                    </a>

                    <div class="dropdown user-dropdown">
                        <a class="dropdown-toggle nav-link p-0 rounded-pill d-flex align-items-center gap-3 text-decoration-none"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                            @if(Auth::user()->role == 1)
                            <span class="badge bg-danger rounded-pill px-2 py-1 small fw-semibold ms-1">ADMIN</span>
                            @endif
                            <i class="fas fa-chevron-down ms-1 opacity-50"></i>
                        </a>

                        <ul class="dropdown-menu shadow-xl border-0 rounded-4">
                            <li>
                                <div class="px-3 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar-lg">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                                            <small class="text-muted">{{ Auth::user()->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <hr class="my-0 opacity-25">
                            </li>

                            <li>
                                <a class="dropdown-item rounded-3 d-flex align-items-center gap-3"
                                    href="{{ route('cart.index') }}">
                                    <i class="fas fa-shopping-cart text-success fs-5"></i>
                                    <span>Giỏ hàng</span>
                                </a>
                            </li>

                            @if(Auth::user()->role == 1)
                            <li>
                                <a class="dropdown-item rounded-3 d-flex align-items-center gap-3"
                                    href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt text-primary fs-5"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 d-flex align-items-center gap-3"
                                    href="{{ route('user.listUser') }}">
                                    <i class="fas fa-users text-info fs-5"></i>
                                    <span>Quản lý User</span>
                                </a>
                            </li>
                            @endif

                            <li>
                                <hr class="my-0 opacity-25">
                            </li>

                            <li>
                                <a href="{{ route('signout') }}"
                                    class="dropdown-item rounded-3 d-flex align-items-center gap-3">
                                    <i class="fas fa-sign-out-alt text-danger fs-5"></i>
                                    <span class="fw-semibold text-danger">Đăng xuất</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible position-fixed end-0 top-0 m-4 shadow-lg z-1050 rounded-4"
            style="max-width: 400px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-5 mt-auto">
        <div class="container">
            <p class="mb-0">© 2024 <strong>Siêu thị Mini</strong> - Trần Cao Trọng - 24211TT1101</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    window.addEventListener('scroll', () => {
        const el = document.querySelector('.navbar');
        if (!el) return;
        el.classList.toggle('scrolled', window.scrollY > 50);
    });

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
                        if (badge) badge.innerText = data.count;
                        alert(data.message);
                    }
                });
        });
    });
    </script>
</body>

</html>