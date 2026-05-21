<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sieu thi Mini')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(
                135deg,
                #667eea 0%,
                #764ba2 100%
            );
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 800;
            background: var(--primary-gradient);

            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cart-badge {
            background: #ee4d2d;
            color: #fff;

            border-radius: 999px;

            min-width: 18px;
            height: 18px;

            font-size: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            position: absolute;
            top: -5px;
            right: -5px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;

            border-radius: 50%;

            background: var(--primary-gradient);

            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: bold;
        }

        footer {
            background: #212529;
            color: white;

            padding: 3rem 0;
            margin-top: 4rem;
        }
    </style>

    @stack('styles')
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light mb-4">
    <div class="container">

        <a class="navbar-brand fs-3" href="{{ route('home') }}">
            <i class="fas fa-store-alt me-2"></i>
            Sieu thi Mini
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('home') }}">
                        Trang chu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('posts.index') }}">
                        Khuyen mai
                    </a>
                </li>

                @auth

                    @if((int) Auth::user()->role === 1)

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('products.index') }}">
                                Products
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('categories.index') }}">
                                Categories
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('coupons.index') }}">
                                Coupons
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('user.listUser') }}">
                                Users
                            </a>
                        </li>

                    @endif

                @endauth

            </ul>

            <div class="d-flex align-items-center gap-3">

                @guest

                    <a href="{{ route('login') }}"
                       class="btn btn-outline-primary rounded-pill">

                        Dang nhap
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

                    <a href="{{ route('cart.index') }}"
                       class="position-relative p-2 text-dark">

                        <i class="fas fa-shopping-cart fs-4"></i>

                        <span class="cart-badge">
                            {{ $cartQuantity }}
                        </span>
                    </a>

                    <div class="dropdown">

                        <a class="d-flex align-items-center gap-2
                                  text-decoration-none text-dark
                                  dropdown-toggle"
                           href="#"
                           data-bs-toggle="dropdown">

                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <span>
                                {{ Auth::user()->name }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                            @if((int) Auth::user()->role === 1)

                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('dashboard') }}">

                                        Dashboard
                                    </a>
                                </li>

                            @endif

                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('signout') }}">

                                    Dang xuat
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
    @yield('content')
</main>

<footer>
    <div class="container text-center">
        <p class="mb-0">
            &copy; 2024 Sieu thi Mini
            - Design by Tran Cao Trong
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>