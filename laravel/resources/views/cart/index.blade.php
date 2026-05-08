@extends('layout')

@section('title', '🛒 Giỏ hàng - Siêu thị Mini')

@section('content')

<!-- CART HERO -->
<section class="py-5 bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <h2>
            <i class="fas fa-shopping-cart text-primary me-2"></i>
            Giỏ hàng
        </h2>

        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            Tiếp tục mua
        </a>
    </div>
</section>

@if(empty($cart) || count($cart) == 0)

<!-- EMPTY CART -->
<div class="container text-center py-5">

    <h3 class="text-muted">
        Giỏ hàng trống
    </h3>

    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
        Mua ngay
    </a>

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


                <!-- PRODUCT -->
                <td>
                    <div class="d-flex align-items-center gap-3">

                        @if(!empty($item['image']))
                        <img src="{{ asset('images/' . $item['image']) }}" width="70" height="70"
                            style="object-fit:cover; border-radius:10px;">
                        @endif

                        <div>
                            <strong>{{ $item['name'] }}</strong>
                        </div>

                    </div>
                </td>

                <!-- PRICE -->
                <td class="text-center">
                    ₫{{ number_format($item['price'] ?? 0) }}
                </td>

                <!-- QUANTITY -->
                <td class="text-center">

                    <form method="POST" action="{{ route('cart.update') }}" class="d-flex justify-content-center gap-2">

                        @csrf

                        <input type="hidden" name="product_id" value="{{ $id }}">

                        <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1"
                            class="form-control" style="width:80px;">

                        <button class="btn btn-sm btn-primary">
                            OK
                        </button>

                    </form>

                </td>

                <!-- SUBTOTAL -->
                <td class="text-end">
                    ₫{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}
                </td>

                <!-- REMOVE -->
                <td class="text-end">

                    <form method="POST" action="{{ route('cart.remove', $id) }}">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Xóa
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <!-- TOTAL -->
    <div class="text-end mt-4">

        <h4>
            Tổng:
            <span class="text-success">
                ₫{{ number_format($total) }}
            </span>
        </h4>

        <div class="mt-3 d-flex justify-content-end gap-2">

            <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger">
                Xóa giỏ hàng
            </a>

            <a href="{{ route('checkout.index') }}" class="btn btn-success">
                Thanh toán
            </a>

        </div>

    </div>

</div>

@endif

@endsection