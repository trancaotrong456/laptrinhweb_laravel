@extends('layout')

@section('title', 'Xác nhận đơn hàng - Siêu thị Mini')

@section('content')

<section class="py-5 bg-light">
    <div class="container text-center">
        <h2>
            <i class="fas fa-check-circle text-success me-2"></i>
            Xác nhận đơn hàng
        </h2>

        <p class="text-muted mt-2">
            Cảm ơn bạn đã mua hàng!
        </p>
    </div>
</section>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <!-- ALERT -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i class="fas fa-check-circle me-2"></i>

                <strong>Thành công!</strong>

                Đơn hàng của bạn đã được tạo thành công.

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>

            </div>

            <!-- CARD -->
            <div class="card mb-4 shadow border-0">

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Chi tiết đơn hàng
                    </h5>

                </div>

                <div class="card-body">

                    <!-- INFO -->
                    <div class="row mb-3">

                        <!-- CUSTOMER -->
                        <div class="col-md-6">

                            <p class="fw-bold mb-1">
                                Khách hàng:
                            </p>

                            <p class="mb-1">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="mb-1">
                                {{ auth()->user()->email }}
                            </p>

                            <p class="mb-1">
                                {{ auth()->user()->phone ?? 'Chưa cập nhật' }}
                            </p>

                        </div>

                        <!-- ORDER -->
                        <div class="col-md-6">

                            <p class="fw-bold mb-1">
                                Ngày đặt:
                            </p>

                            <p>
                                {{ \Illuminate\Support\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}
                            </p>

                            <p class="fw-bold mb-1">
                                Phương thức thanh toán:
                            </p>

                            <p>

                                @switch($order['payment_method'])

                                    @case('cod')
                                        <span class="badge bg-info">
                                            Thanh toán khi nhận hàng
                                        </span>
                                        @break

                                    @case('bank')
                                        <span class="badge bg-warning text-dark">
                                            Chuyển khoản ngân hàng
                                        </span>
                                        @break

                                    @case('wallet')
                                        <span class="badge bg-success">
                                            Ví điện tử
                                        </span>
                                        @break

                                    @default
                                        <span class="badge bg-secondary">
                                            Không xác định
                                        </span>

                                @endswitch

                            </p>

                        </div>

                    </div>

                    <hr>

                    <!-- PRODUCTS -->
                    <h6 class="mb-3">

                        <i class="fas fa-shopping-bag me-2"></i>

                        Sản phẩm đã đặt

                    </h6>

                    <div class="table-responsive">

                        <table class="table table-sm align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($order['cart'] as $item)

                                <tr>

                                    <td>
                                        {{ $item['name'] }}
                                    </td>

                                    <td class="text-center">
                                        {{ number_format($item['price'] ?? 0) }} ₫
                                    </td>

                                    <td class="text-center">
                                        {{ $item['quantity'] }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }} ₫
                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <hr>

                    <!-- TOTAL -->
                    <div class="row">

                        <div class="col-md-6"></div>

                        <div class="col-md-6">

                            <div class="d-flex justify-content-between mb-2">

                                <strong>Tạm tính:</strong>

                                <span>
                                    {{ number_format($order['subtotal'] ?? $order['total']) }} ₫
                                </span>

                            </div>

                            <div class="d-flex justify-content-between mb-2">

                                <strong>Giảm giá:</strong>

                                <span class="text-success">
                                    -{{ number_format($order['discount'] ?? 0) }} ₫
                                </span>

                            </div>

                            <div class="d-flex justify-content-between mb-2">

                                <strong>Phí vận chuyển:</strong>

                                <span>
                                    {{ number_format($order['shipping_fee'] ?? 0) }} ₫
                                </span>

                            </div>

                            @if(!empty($order['coupon_code']))

                            <div class="d-flex justify-content-between mb-2">

                                <strong>Mã giảm giá:</strong>

                                <span class="badge bg-success">
                                    {{ $order['coupon_code'] }}
                                </span>

                            </div>

                            @endif

                            <div class="border-top pt-2 mt-2">

                                <div class="d-flex justify-content-between align-items-center">

                                    <h5 class="mb-0">
                                        Tổng cộng:
                                    </h5>

                                    <h5 class="text-success mb-0">
                                        {{ number_format($order['total']) }} ₫
                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- NOTES -->
                    @if(!empty($order['notes']))

                    <hr>

                    <div>

                        <h6>
                            Ghi chú:
                        </h6>

                        <p class="text-muted mb-0">
                            {{ $order['notes'] }}
                        </p>

                    </div>

                    @endif

                </div>

            </div>

            <!-- BUTTONS -->
            <div class="d-grid gap-2">

                <a href="{{ route('home') }}"
                    class="btn btn-primary btn-lg">

                    <i class="fas fa-arrow-left me-2"></i>

                    Tiếp tục mua sắm

                </a>

                <a href="{{ route('cart.index') }}"
                    class="btn btn-outline-secondary btn-lg">

                    <i class="fas fa-shopping-cart me-2"></i>

                    Về giỏ hàng

                </a>

            </div>

        </div>

    </div>

</div>

@endsection