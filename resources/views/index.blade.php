@extends('layout')

@section('title', 'Trang chủ - Siêu thị Mini')

@section('content')

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SESSION ALERT -->
@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: '{{ session('
    success ') }}',
    showConfirmButton: false,
    timer: 2000,
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
    title: '{{ session('
    error ') }}',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});
</script>
@endif

<div class="hero-section text-center py-5 bg-gradient-primary">

    <div class="container">

        <h1 class="display-4 fw-bold mb-4 text-white animate-fade-in">
            Chào mừng đến Siêu thị Mini
        </h1>

        <p class="lead text-white-50 mb-5">
            Mua sắm thông minh - Giao hàng siêu tốc
        </p>

        @guest

        <div class="d-flex justify-content-center gap-3 flex-wrap">

            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-lg">

                <i class="fas fa-lock me-2"></i>
                Đăng nhập

            </a>

            <a href="{{ route('user.createUser') }}" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow-lg">

                <i class="fas fa-user-plus me-2"></i>
                Đăng ký

            </a>

        </div>

        @else

        @if(Auth::user()->role == 1)

        <a href="{{ route('dashboard') }}" class="btn btn-info btn-lg px-5 py-3 rounded-pill shadow-lg text-white">

            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard Admin

        </a>

        @endif

        @endguest

    </div>

</div>

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="display-5 fw-bold mb-3 gradient-text">
                🛍️ Sản phẩm HOT
            </h2>

            <p class="lead text-muted">
                Chọn lựa những sản phẩm chất lượng cao với giá tốt nhất
            </p>

        </div>

        <div class="row g-4">

            @forelse($products->take(12) as $product)

            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

                <div class="card border-0 h-100 shadow-lg overflow-hidden rounded-4" style="transition: all 0.4s ease;">

                    <a href="{{ route('products.detail', $product) }}" class="position-relative overflow-hidden d-block"
                        style="height: 220px;">

                        @if($product->image)

                        <img src="{{ asset('images/' . $product->image) }}"
                            class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $product->name }}">

                        @else

                        <div
                            class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">

                            <i class="fas fa-gift fa-3x"></i>

                        </div>

                        @endif

                    </a>

                    <div class="card-body p-3 d-flex flex-column">

                        <a href="{{ route('products.detail', $product) }}" class="text-decoration-none text-dark">
                            <h6 class="fw-bold mb-2" style="
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;
                                ">

                                {{ $product->name }}

                            </h6>
                        </a>

                        <div class="mb-2">

                            <span class="h5 fw-bolder text-danger">
                                {{ number_format($product->price) }}đ
                            </span>

                        </div>

                        <div class="mt-auto">

                            <!-- AJAX ADD TO CART -->
                            <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">

                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold rounded-3"
                                    style="font-size: 0.9rem;" {{ $product->quantity <= 0 ? 'disabled' : '' }}>

                                    <i class="fas fa-cart-shopping me-1"></i>
                                    Mua ngay

                                </button>

                            </form>

                            <a href="{{ route('products.detail', $product) }}"
                                class="btn btn-outline-secondary w-100 mt-2 rounded-3">
                                Chi tiết
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center py-5">

                <i class="fas fa-fire fa-5x text-muted mb-4 opacity-50"></i>

                <h3 class="text-muted mb-4">
                    Chưa có sản phẩm nào
                </h3>

            </div>

            @endforelse

        </div>

    </div>

</section>

<!-- COUPONS SECTION (Homepage) -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 gradient-text">
                <i class="fas fa-ticket-alt me-2"></i>Mã giảm giá cho bạn
            </h2>
            <p class="lead text-muted mb-0">Chọn mã và lưu ngay để dùng cho đơn hàng của bạn</p>
        </div>

        <div class="row g-4">
            @php
            $savedCouponIdsHome = [];
            if (auth()->check()) {
            $savedCouponIdsHome = \App\Models\UserSavedCoupon::where('user_id', auth()->id())
            ->pluck('coupon_id')
            ->map(fn ($id) => (int) $id)
            ->all();
            }
            @endphp

            @forelse($coupons as $coupon)
            @php
            $isSavedHome = in_array($coupon->id, $savedCouponIdsHome);
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1 fw-bold"><strong>{{ $coupon->code }}</strong></h5>
                                <div class="text-muted small">
                                    @if($coupon->type === 'percent')
                                    Giảm <span
                                        class="text-danger">{{ rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') }}%</span>
                                    @else
                                    Giảm <span class="text-danger">{{ number_format($coupon->value) }} đ</span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="mt-3 text-muted small">
                            Đơn tối thiểu:
                            <strong>
                                {{ $coupon->min_order_value ? number_format($coupon->min_order_value) . ' đ' : 'Không yêu cầu' }}
                            </strong>
                        </div>

                        @if($coupon->ends_at)
                        <div class="mt-2 text-muted small">Hết hạn: {{ $coupon->ends_at->format('d/m/Y H:i') }}</div>
                        @else
                        <div class="mt-2 text-muted small">Không giới hạn thời gian</div>
                        @endif

                        <div class="mt-4">
                            @auth
                            @if($isSavedHome)
                            <form method="POST" action="{{ route('coupons.unsave', $coupon) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Đã lưu
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('coupons.save', $coupon) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-ticket-alt me-2"></i>Lưu mã
                                </button>
                            </form>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="fas fa-lock me-2"></i>Đăng nhập để lưu
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-inbox fa-5x text-muted opacity-50 mb-3"></i>
                <h4 class="text-muted">Hiện chưa có mã giảm giá</h4>
            </div>
            @endforelse
        </div>
    </div>
</section>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}


.animate-fade-in {
    animation: fadeIn 1s ease-in;
}

@keyframes fadeIn {

    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.gradient-text {

    background: linear-gradient(45deg,
            #ff6b6b,
            #4ecdc4,
            #45b7d1);

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    background-clip: text;
}

.card:hover {

    transform: translateY(-10px);

}
</style>

<!-- AJAX ADD TO CART -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const forms = document.querySelectorAll('.add-to-cart-form');

    forms.forEach(form => {

        form.addEventListener('submit', function(e) {

            e.preventDefault();

            const formData = new FormData(this);

            const submitBtn = this.querySelector('button[type="submit"]');

            submitBtn.disabled = true;

            fetch(this.action, {

                    method: 'POST',

                    headers: {

                        'X-Requested-With': 'XMLHttpRequest',

                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),

                        'Accept': 'application/json'
                    },

                    body: formData

                })

                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {

                        throw data;
                    }

                    return data;
                })

                .then(data => {

                    if (data.success) {

                        // FIRE ALERT SUCCESS
                        Swal.fire({

                            toast: true,

                            position: 'top-end',

                            icon: 'success',

                            title: data.message,

                            showConfirmButton: false,

                            timer: 2000,

                            timerProgressBar: true

                        });

                        // UPDATE CART BADGE
                        const badge = document.querySelector('.cart-badge');

                        if (badge) {

                            badge.innerText = data.cartCount;
                        }

                    } else {

                        // FIRE ALERT ERROR
                        Swal.fire({

                            toast: true,

                            position: 'top-end',

                            icon: 'error',

                            title: data.message || 'Có lỗi xảy ra',

                            showConfirmButton: false,

                            timer: 2000,

                            timerProgressBar: true

                        });

                        // REDIRECT LOGIN
                        if (data.redirect) {

                            setTimeout(() => {

                                window.location.href = data.redirect;

                            }, 1000);
                        }
                    }

                })

                .catch(error => {

                    console.log(error);

                    Swal.fire({

                        toast: true,

                        position: 'top-end',

                        icon: 'error',

                        title: error.message || 'Không thể thêm sản phẩm',

                        showConfirmButton: false,

                        timer: 2000,

                        timerProgressBar: true

                    });

                })

                .finally(() => {

                    submitBtn.disabled = false;

                });

        });

    });

});
</script>
<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.add-to-cart-form').forEach(form => {

        form.addEventListener('submit', function(e) {

            e.preventDefault();

            let formData = new FormData(this);

            fetch(this.action, {

                    method: 'POST',

                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).getAttribute('content')
                    },

                    body: formData

                })

                .then(response => response.json())

                .then(data => {

                    if (data.success) {

                        Swal.fire({

                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,

                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true

                        });

                        // UPDATE CART BADGE
                        let badge = document.querySelector('.cart-badge');

                        if (badge) {

                            badge.innerText = data.cartCount;
                        }

                    } else {

                        Swal.fire({

                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message

                        });

                        // REDIRECT LOGIN
                        if (data.redirect) {

                            setTimeout(() => {

                                window.location.href = data.redirect;

                            }, 1000);
                        }
                    }

                })

                .catch(error => {

                    console.log(error);

                    Swal.fire({

                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể thêm sản phẩm'

                    });

                });

        });

    });

});
</script>
@endsection