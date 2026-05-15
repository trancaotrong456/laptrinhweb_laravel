@extends('layout')

@section('title', '🛒 Giỏ hàng - Siêu thị Mini')

@section('content')

<section class="py-5 bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">
            <i class="fas fa-shopping-cart text-primary me-2"></i>
            Giỏ hàng của bạn
        </h2>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm
        </a>
    </div>
</section>

<div class="container py-5">
    @if(empty($cart) || count($cart) == 0)
    <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="150" class="mb-4" alt="Empty Cart">
        <h3 class="text-muted">Giỏ hàng của bạn đang trống</h3>
        <p class="text-secondary">Hãy thêm một vài sản phẩm để bắt đầu mua sắm nhé!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-3">
            Mua sắm ngay
        </a>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="ps-4">Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end pe-4">Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if(!empty($item['image']))
                                        <img src="{{ asset('images/' . $item['image']) }}" class="rounded shadow-sm"
                                            width="70" height="70" style="object-fit:cover;">
                                        @else
                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center"
                                            style="width:70px; height:70px;">N/A</div>
                                        @endif
                                        <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                    </div>
                                </td>
                                <td class="text-center text-primary fw-bold">
                                    {{ number_format($item['price'] ?? 0) }}₫
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('cart.update') }}" class="d-flex justify-content-center gap-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1"
                                            class="form-control text-center" style="width:70px;">
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}₫
                                </td>
                                <td class="text-center pe-4">
                                    <form method="POST" action="{{ route('cart.remove', $id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link text-danger p-0" title="Xóa">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Tổng quan đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($total) }}₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 fw-bold">Tổng cộng:</span>
                        <span class="h5 fw-bold text-danger">{{ number_format($total) }}₫</span>
                    </div>

                    <button type="button" class="btn btn-success btn-lg w-100 mb-2 py-3 shadow" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                        <i class="fas fa-credit-card me-2"></i> Thanh toán ngay
                    </button>

                    <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger w-100 mt-2"
                        onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                        Xóa toàn bộ giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

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
                                <i class="fas fa-money-bill-wave me-2"></i><strong>COD (Tiền mặt)</strong>
                                <p class="text-muted ms-4">Thanh toán khi nhận hàng</p>
                            </label>
                        </div>
                        <div class="form-check payment-option mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                            <label class="form-check-label" for="bank">
                                <i class="fas fa-university me-2"></i><strong>Chuyển khoản</strong>
                                <p class="text-muted ms-4">Nhanh chóng, an toàn</p>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="notes" class="form-label">Ghi chú đơn hàng:</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="checkoutForm" class="btn btn-success btn-lg">Xác nhận đơn hàng</button>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option { padding: 15px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; }
.payment-option:hover { background-color: #f8f9fa; border-color: #28a745; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session()->has('cart_last_removed'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        icon: 'success',
        title: 'Đã xóa sản phẩm.',
        html: '<button id="btn-undo" class="btn btn-sm btn-dark w-100 mt-2">Hoàn tác ngay</button>',
        didOpen: () => {
            document.getElementById('btn-undo').addEventListener('click', () => {
                window.location.href = "{{ route('cart.undoRemove') }}";
            });
        }
    });
});
</script>
@endif

@endsection