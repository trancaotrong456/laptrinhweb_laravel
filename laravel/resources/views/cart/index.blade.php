@extends('layout')
@section('title', '🛒 Giỏ hàng - Siêu thị Mini')

@section('content')
<!-- Page title -->
<!-- Cart Hero -->
<section class="py-5 bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-shopping-cart text-primary me-2"></i>Giỏ hàng</h2>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">Tiếp tục mua</a>
    </div>
</section>

@if(empty($cart) || count($cart) == 0)

<!-- EMPTY -->
<div class="container text-center py-5">
    <h3 class="text-muted">Giỏ hàng trống</h3>
    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Mua ngay</a>
</div>

@else

<!-- CART -->
<div class="container py-4">

    <table class="table align-middle">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th class="text-center">Giá</th>
                <th class="text-center">Số lượng</th>
                <th class="text-end">Thành tiền</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($cart as $id => $item)
            <tr>
                <td>{{ $item['name'] }}</td>

                <td class="text-center">
                    ₫{{ number_format($item['price'] ?? 0) }}
                </td>

                <td class="text-center">
                    <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1"
                            style="width:70px">
                        <button class="btn btn-sm btn-primary">OK</button>
                    </form>
                </td>

                <td class="text-end">
                    ₫{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}
                </td>

                <td>
                    <form method="POST" action="{{ route('cart.remove', $id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="text-end mt-4">
        <h4>
            Tổng: <span class="text-success">
                ₫{{ number_format($total) }}
            </span>
        </h4>

        <!-- Thanh toán Button -->
        <button type="button" class="btn btn-success btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#checkoutModal">
            <i class="fas fa-credit-card me-2"></i>Thanh toán
        </button>
        
        <a href="{{ route('home') }}" class="btn btn-secondary btn-lg mt-3">
            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua
        </a>
    </div>

</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="fas fa-credit-card me-2"></i>Chọn hình thức thanh toán
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="checkoutForm" method="POST" action="{{ route('checkout') }}">
                    @csrf
                    
                    <div class="payment-methods">
                        <div class="form-check payment-option mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                <i class="fas fa-money-bill-wave me-2"></i><strong>Thanh toán khi nhận hàng (COD)</strong>
                                <p class="text-muted ms-4">Tự do, không phí thêm</p>
                            </label>
                        </div>

                        <div class="form-check payment-option mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                            <label class="form-check-label" for="bank">
                                <i class="fas fa-university me-2"></i><strong>Chuyển khoản ngân hàng</strong>
                                <p class="text-muted ms-4">Nhanh chóng, an toàn</p>
                            </label>
                        </div>

                        <div class="form-check payment-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="wallet" value="wallet">
                            <label class="form-check-label" for="wallet">
                                <i class="fas fa-wallet me-2"></i><strong>Ví điện tử</strong>
                                <p class="text-muted ms-4">Momo, ZaloPay</p>
                            </label>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="mt-4">
                        <label for="notes" class="form-label">Ghi chú đơn hàng (không bắt buộc):</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ghi chú cho người bán..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="checkoutForm" class="btn btn-success btn-lg">
                    <i class="fas fa-check-circle me-2"></i>Xác nhận
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option {
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-option:hover {
    border-color: #28a745;
    background-color: #f8f9fa;
}

.payment-option input[type="radio"]:checked + label {
    color: #28a745;
}

.payment-option input[type="radio"]:checked ~ .payment-option {
    border-color: #28a745;
    background-color: #f0f8f5;
}

.payment-option label {
    margin-bottom: 0;
    cursor: pointer;
}

.payment-option p {
    margin-bottom: 0;
    font-size: 0.9rem;
}
</style>

@endif {{-- QUAN TRỌNG: FIX LỖI Ở ĐÂY --}}

@endsection