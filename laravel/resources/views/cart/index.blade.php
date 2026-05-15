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
                                        <input type="checkbox" class="form-check-input cart-select"
                                            name="selected_products[]" value="{{ $id }}" form="cartCheckoutForm">

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
                                    {{ number_format($item['price']) }}₫
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('cart.update') }}"
                                        class="d-flex justify-content-center gap-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            class="form-control text-center" style="width:70px;">
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item['price'] * $item['quantity']) }}₫
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

                    <form id="cartCheckoutForm" method="POST" action="{{ route('cart.confirmSelected') }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100 mb-2 py-3 shadow">
                            <i class="fas fa-check-circle me-2"></i> Thanh toán ngay
                        </button>
                    </form>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session()->has('cart_last_removed'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true,
        icon: 'success',
        title: 'Đã xóa sản phẩm',
        html: '<button id="undo-btn" class="btn btn-sm btn-dark mt-2 w-100">Khôi phục (Undo)</button>',
        didOpen: () => {
            const undoBtn = document.getElementById('undo-btn');
            if (undoBtn) {
                undoBtn.addEventListener('click', () => {
                    window.location.href = "{{ route('cart.undoRemove') }}";
                });
            }
        }
    });
});
</script>
@endif

@endsection