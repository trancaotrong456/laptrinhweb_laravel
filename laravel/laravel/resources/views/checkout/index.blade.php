@extends('layout')

@section('title', '💳 Thanh toán - Siêu thị Mini')

@push('styles')
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --success: #10b981;
    --danger: #ef4444;
    --bg: #f5f7fb;
    --card-bg: rgba(255, 255, 255, 0.85);
    --border: rgba(255, 255, 255, 0.4);
    --shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

body {
    background:
        radial-gradient(circle at top left, rgba(99, 102, 241, .12), transparent 30%),
        radial-gradient(circle at bottom right, rgba(16, 185, 129, .10), transparent 30%),
        var(--bg);
}

/* HERO */

.checkout-hero {
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}

.checkout-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(135deg,
            rgba(99, 102, 241, .12),
            rgba(139, 92, 246, .10));
    z-index: 0;
}

.checkout-hero .container {
    position: relative;
    z-index: 2;
}

.checkout-title {
    font-size: 3rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 12px;
}

.checkout-subtitle {
    color: #6b7280;
    font-size: 1.05rem;
}

/* CARD */

.glass-card {
    background: var(--card-bg);
    backdrop-filter: blur(18px);
    border: 1px solid var(--border);
    border-radius: 28px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-modern {
    padding: 24px 28px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    background:
        linear-gradient(135deg,
            rgba(99, 102, 241, .08),
            rgba(139, 92, 246, .05));
}

.card-header-modern h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.15rem;
    color: #111827;
}

.card-body-modern {
    padding: 28px;
}

/* PRODUCT ITEM */

.product-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px;
    border-radius: 20px;
    transition: .25s ease;
    border: 1px solid transparent;
}

.product-item:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, .7);
    border-color: rgba(99, 102, 241, .12);
}

.product-left {
    display: flex;
    align-items: center;
    gap: 18px;
}

.product-image {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
}

.product-name {
    font-weight: 700;
    color: #111827;
    margin-bottom: 5px;
}

.product-qty {
    color: #6b7280;
    font-size: .92rem;
}

.product-price {
    font-weight: 800;
    color: var(--success);
    font-size: 1.05rem;
}

/* TOTAL */

.total-box {
    margin-top: 10px;
    padding: 22px 26px;
    border-radius: 22px;
    background:
        linear-gradient(135deg,
            #6366f1,
            #8b5cf6);
    color: white;
}

.total-box h4 {
    margin: 0;
    font-weight: 800;
}

/* FORM */

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 14px 16px;
    font-size: .95rem;
    transition: .25s ease;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 .25rem rgba(99, 102, 241, .15);
}

/* BUTTONS */

.btn-modern {
    border: none;
    border-radius: 18px;
    padding: 14px 20px;
    font-weight: 700;
    transition: .25s ease;
}

.btn-checkout {
    background:
        linear-gradient(135deg,
            var(--success),
            #34d399);
    color: white;
    box-shadow: 0 10px 25px rgba(16, 185, 129, .25);
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 35px rgba(16, 185, 129, .35);
}

.btn-back {
    border: 2px solid rgba(0, 0, 0, .08);
    background: white;
    color: #374151;
}

.btn-back:hover {
    background: #f9fafb;
}

/* EMPTY */

.empty-cart {
    padding: 100px 0;
}

.empty-icon {
    width: 140px;
    height: 140px;
    margin: auto;
    border-radius: 50%;
    background:
        linear-gradient(135deg,
            rgba(99, 102, 241, .12),
            rgba(139, 92, 246, .15));

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 4rem;
    color: var(--primary);
    margin-bottom: 30px;
}

/* MOBILE */

@media(max-width: 768px) {

    .checkout-title {
        font-size: 2.2rem;
    }

    .product-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .product-price {
        width: 100%;
        text-align: right;
    }
}
</style>
@endpush

@section('content')

<!-- HERO -->
<section class="checkout-hero">
    <div class="container">
        <h1 class="checkout-title">
            <i class="fas fa-credit-card me-3 text-primary"></i>
            Thanh toán đơn hàng
        </h1>

        <p class="checkout-subtitle">
            Kiểm tra sản phẩm và hoàn tất thông tin giao hàng của bạn.
        </p>
    </div>
</section>

@if(empty($cart) || count($cart) == 0)

<!-- EMPTY CART -->
<section class="empty-cart">
    <div class="container text-center">

        <div class="empty-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>

        <h2 class="fw-bold mb-3">
            Giỏ hàng của bạn đang trống
        </h2>

        <p class="text-muted mb-4">
            Hãy khám phá thêm nhiều sản phẩm hấp dẫn tại Siêu thị Mini.
        </p>

        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow">
            <i class="fas fa-store me-2"></i>
            Mua sắm ngay
        </a>

    </div>
</section>

@else

<!-- CHECKOUT -->
<section class="pb-5">
    <div class="container">

        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-8">

                <div class="glass-card">

                    <div class="card-header-modern">
                        <h5>
                            <i class="fas fa-bag-shopping text-primary me-2"></i>
                            Đơn hàng của bạn
                        </h5>
                    </div>

                    <div class="card-body-modern">

                        @foreach($cart as $id => $item)
                        @php
                            $itemImage = $item['image'] ?? null;
                            $itemImageUrl = null;
                            if ($itemImage) {
                                $itemImageUrl = \Illuminate\Support\Str::startsWith($itemImage, ['http://', 'https://'])
                                    ? $itemImage
                                    : (str_contains($itemImage, '/') ? asset('storage/' . $itemImage) : asset('images/' . $itemImage));
                            }
                        @endphp

                        <div class="product-item mb-3">

                            <div class="product-left">

                                @if($itemImageUrl)
                                <img src="{{ $itemImageUrl }}" class="product-image">
                                @else
                                <img src="https://via.placeholder.com/100" class="product-image">
                                @endif

                                <div>

                                    <div class="product-name">
                                        {{ $item['name'] }}
                                    </div>

                                    <div class="product-qty">
                                        <i class="fas fa-layer-group me-1"></i>
                                        Số lượng:
                                        <strong>{{ $item['quantity'] }}</strong>
                                    </div>

                                </div>

                            </div>

                            <div class="product-price">
                                ₫{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}
                            </div>

                        </div>

                        @endforeach

                        <div class="total-box d-flex justify-content-between align-items-center">

                            <span class="fs-5 fw-semibold">
                                Tổng thanh toán
                            </span>

                            <h4>
                                ₫{{ number_format($total) }}
                            </h4>

                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">

                <div class="glass-card">

                    <div class="card-header-modern">
                        <h5>
                            <i class="fas fa-truck text-success me-2"></i>
                            Thông tin giao hàng
                        </h5>
                    </div>

                    <div class="card-body-modern">

                        <form method="POST" action="{{ route('checkout.process') }}">

                            @csrf

                            <div class="mb-3">
                                <label class="form-label">
                                    Họ và tên *
                                </label>

                                <input type="text" class="form-control" name="name" placeholder="Nhập họ tên..."
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Email *
                                </label>

                                <input type="email" class="form-control" name="email" placeholder="example@gmail.com"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Số điện thoại
                                </label>

                                <input type="tel" class="form-control" name="phone" placeholder="090xxxxxxx">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    Địa chỉ giao hàng *
                                </label>

                                <textarea class="form-control" rows="4" name="address"
                                    placeholder="Nhập địa chỉ nhận hàng..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-modern btn-checkout w-100">

                                <i class="fas fa-circle-check me-2"></i>
                                Xác nhận đặt hàng

                            </button>

                        </form>

                        <a href="{{ route('cart.index') }}" class="btn btn-modern btn-back w-100 mt-3">

                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại giỏ hàng

                        </a>

                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

@endif

@endsection
