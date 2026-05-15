@extends('layout')
@section('title', '✅ Xác nhận đơn hàng - Siêu thị Mini')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2><i class="fas fa-check-circle text-success me-2"></i>Xác nhận đơn hàng</h2>
        <p class="text-muted mt-2">Cảm ơn bạn đã mua hàng!</p>
    </div>
</section>

<!-- Success Message -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Alert -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Thành công!</strong> Đơn hàng của bạn đã được tạo. Chúng tôi sẽ liên hệ với bạn sớm.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Order Details Card -->
            <div class="card mb-4 shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Chi tiết đơn hàng</h5>
                </div>
                <div class="card-body">
                    <!-- Khách hàng -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Khách hàng:</strong></p>
                            <p>{{ auth()->user()->name }}</p>
                            <p>{{ auth()->user()->email }}</p>
                            <p>{{ auth()->user()->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày đặt:</strong></p>
                            <p>{{ \Illuminate\Support\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</p>
                            <p><strong>Phương thức thanh toán:</strong></p>
                            <p>
                                @switch($order['payment_method'])
                                    @case('cod')
                                        <span class="badge bg-info">Thanh toán khi nhận hàng</span>
                                        @break
                                    @case('bank')
                                        <span class="badge bg-warning">Chuyển khoản ngân hàng</span>
                                        @break
                                    @case('wallet')
                                        <span class="badge bg-success">Ví điện tử</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Sản phẩm -->
                    <h6 class="mb-3"><i class="fas fa-shopping-bag me-2"></i>Sản phẩm đã đặt</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['cart'] as $id => $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="text-center">₫{{ number_format($item['price'] ?? 0) }}</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">₫{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- Tổng tiền -->
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Tổng sản phẩm:</strong>
                                <span>₫{{ number_format($order['total']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Phí vận chuyển:</strong>
                                <span class="text-success">Miễn phí</span>
                            </div>
                            <div class="border-top pt-2">
                                <div class="d-flex justify-content-between">
                                    <h5>Tổng cộng:</h5>
                                    <h5 class="text-success">₫{{ number_format($order['total']) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    @if($order['notes'])
                    <hr>
                    <div>
                        <h6>Ghi chú:</h6>
                        <p class="text-muted">{{ $order['notes'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mb-4 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Bước tiếp theo</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Chúng tôi sẽ kiểm tra đơn hàng của bạn</li>
                        <li>Liên hệ xác nhận thông tin giao hàng</li>
                        <li>Chuẩn bị hàng và gửi cho bạn</li>
                        <li>Bạn sẽ nhận được thông báo khi hàng đã gửi</li>
                    </ol>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-grid gap-2">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-user me-2"></i>Xem đơn hàng của tôi
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0;
    font-weight: 500;
}

ol li {
    margin-bottom: 10px;
}
</style>
@endsection
