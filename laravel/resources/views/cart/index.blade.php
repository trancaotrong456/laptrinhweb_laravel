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
                    ₫{{ number_format($item['price']) }}
                </td>

                <td class="text-center">
                    <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:70px">
                        <button class="btn btn-sm btn-primary">OK</button>
                    </form>
                </td>

                <td class="text-end">
                    ₫{{ number_format($item['price'] * $item['quantity']) }}
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

        <a href="#" class="btn btn-success mt-2">Thanh toán</a>
    </div>

</div>

@endif {{-- QUAN TRỌNG: FIX LỖI Ở ĐÂY --}}

@endsection