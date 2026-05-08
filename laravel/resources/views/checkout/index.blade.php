@extends('layout')

@section('title', '💳 Thanh toán - Siêu thị Mini')

@section('content')

<!-- CHECKOUT HERO -->
<section class="py-5 bg-light">
    <div class="container">
        <h2>
            <i class="fas fa-credit-card text-primary me-2"></i>
            Thanh toán
        </h2>
    </div>
</section>

@if(empty($cart) || count($cart) == 0)

<!-- EMPTY CART -->
<div class="container text-center py-5">
    <h3 class="text-muted">Giỏ hàng trống</h3>
    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Mua ngay</a>
</div>

@else

<div class="container py-4">
    <div class="row">
        <!-- CART SUMMARY -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    @foreach($cart as $id => $item)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @if(!empty($item['image']))
                            <img src="{{ asset('images/' . $item['image']) }}" width="50" height="50"
                                style="object-fit:cover; border-radius:5px;">
                            @endif
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <br>
                                <small class="text-muted">Số lượng: {{ $item['quantity'] }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            ₫{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-success">₫{{ number_format($total) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHECKOUT FORM -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>
                            Đặt hàng
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại giỏ hàng
                </a>
            </div>
        </div>
    </div>
</div>

@endif

@endsection