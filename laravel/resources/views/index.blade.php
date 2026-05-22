@extends('layout')

@section('title', 'Trang chủ - Siêu thị Mini')

@section('content')

<div class="container mt-3">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

</div>

<div class="hero-section home-welcome-banner text-center py-5 bg-gradient-primary">

    <div class="container">

        <h1 class="display-4 fw-bold mb-4 text-white animate-fade-in">
            Chào mừng đến Siêu thị Mini
        </h1>

        <p class="lead text-white-50 mb-5">
            Mua sắm thông minh - Giao hàng siêu tốc
        </p>

        @guest

        <div class="d-flex justify-content-center gap-3 flex-wrap">

            <a href="{{ route('login') }}"
               class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-lg">

                <i class="fas fa-lock me-2"></i>
                Đăng nhập
            </a>

            <a href="{{ route('user.createUser') }}"
               class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow-lg">

                <i class="fas fa-user-plus me-2"></i>
                Đăng ký
            </a>

        </div>

        @else

            @if(Auth::user()->role == 1)

            <a href="{{ route('dashboard') }}"
               class="btn btn-info btn-lg px-5 py-3 rounded-pill shadow-lg text-white">

                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard Admin
            </a>

            @endif

        @endguest

    </div>

</div>

@if(isset($coupons) && $coupons->count() > 0)

<section class="py-5 bg-white border-bottom">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Ma giam gia dang co
                </h3>

                <p class="text-muted mb-0">
                    Ban co the luu ma de dung khi checkout.
                </p>

            </div>

            <a href="{{ route('cart.index') }}"
               class="btn btn-outline-primary">

                Mo gio hang
            </a>

        </div>

        <div class="row g-3">

            @foreach($coupons as $coupon)

            <div class="col-lg-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-2">

                            <h5 class="mb-0">
                                {{ $coupon->code }}
                            </h5>

                            @if($coupon->type === 'percent')

                                <span class="badge bg-danger">
                                    {{ rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') }}%
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    -{{ number_format($coupon->value) }} đ
                                </span>

                            @endif

                        </div>

                        <p class="text-muted small mb-2">

                            Don toi thieu:

                            <strong>
                                {{ $coupon->min_order_value ? number_format($coupon->min_order_value) . ' đ' : 'Khong yeu cau' }}
                            </strong>

                        </p>

                        @if($coupon->ends_at)

                            <p class="text-muted small mb-3">
                                Het han: {{ $coupon->ends_at->format('d/m/Y H:i') }}
                            </p>

                        @else

                            <p class="text-muted small mb-3">
                                Khong gioi han thoi gian
                            </p>

                        @endif

                        @auth

                            @if(in_array((int) $coupon->id, $savedCouponIds ?? [], true))

                                <div class="d-flex gap-2">

                                    <span class="btn btn-sm btn-success disabled">
                                        Da luu
                                    </span>

                                    <form method="POST"
                                          action="{{ route('coupons.unsave', $coupon) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
                                            Bo luu
                                        </button>

                                    </form>

                                </div>

                            @else

                                <form method="POST"
                                      action="{{ route('coupons.save', $coupon) }}">

                                    @csrf

                                    <button class="btn btn-sm btn-primary">
                                        Luu ma nay
                                    </button>

                                </form>

                            @endif

                        @else

                            <a href="{{ route('login') }}"
                               class="btn btn-sm btn-outline-primary">

                                Dang nhap de luu ma
                            </a>

                        @endauth

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endif

@if(isset($banners) && $banners->count() > 0)

<section class="banner-section">

    <div id="mainCarousel"
         class="carousel slide carousel-fade"
         data-bs-ride="carousel"
         data-bs-interval="4000">

        <div class="carousel-inner rounded-3 shadow-lg">

            @foreach($banners as $index => $banner)

            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

                <img src="{{ $banner->image ? asset('images/' . $banner->image) : asset('images/banner-placeholder.jpg') }}"
                     class="d-block w-100"
                     style="height: 500px; object-fit: cover;"
                     alt="{{ $banner->title }}">

                <div class="carousel-caption d-none d-md-block">

                    @if(Auth::check() && Auth::user()->role == 1)

                    <a href="{{ route('posts.edit', $banner->id) }}"
                       class="btn btn-sm btn-warning mb-2">

                        ⚙️ Edit Banner
                    </a>

                    @endif

                    <h2 class="display-5 fw-bold text-shadow">
                        {{ $banner->title }}
                    </h2>

                    <p class="lead text-shadow">
                        {{ Str::limit($banner->content, 120) }}
                    </p>

                    <div class="d-flex gap-2 justify-content-center">

                        <a href="#"
                           class="btn btn-primary btn-lg rounded-pill px-4">

                            Xem ngay
                        </a>

                        <a href="{{ route('posts.index') }}"
                           class="btn btn-outline-light btn-lg rounded-pill px-4">

                            Tất cả khuyến mãi
                        </a>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

        <button class="carousel-control-prev"
                type="button"
                data-bs-target="#mainCarousel"
                data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <button class="carousel-control-next"
                type="button"
                data-bs-target="#mainCarousel"
                data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

</section>

@endif

@endsection

@push('styles')
<style>
    .home-welcome-banner {
        background: linear-gradient(135deg, #00c9ff 0%, #00e5ff 100%);
    }
</style>
@endpush
