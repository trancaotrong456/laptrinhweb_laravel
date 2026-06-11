@extends('layout')

@section('title', 'Sản phẩm yêu thích')

@section('content')

<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0">
             Sản phẩm yêu thích
        </h3>

        <span class="badge bg-danger">
            {{ $wishlists->count() }} sản phẩm
        </span>
    </div>

    <div class="row g-4">

        @forelse($wishlists as $item)

        @php
            $product = $item->product;
        @endphp

        <div class="col-6 col-md-4 col-lg-3">

            <div class="product-card h-100">

                <a href="{{ route('products.detail', $product) }}"
                   class="pc-img-wrap d-block">

                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}"
                             alt="{{ $product->name }}">
                    @else
                        <div class="no-img">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif

                </a>

                <div class="pc-body">

                    <div class="pc-name">
                        {{ $product->name }}
                    </div>

                    <div class="pc-price-row">
                        <span class="pc-price">
                            {{ number_format($product->price) }}đ
                        </span>
                    </div>

                    <div class="mt-3 d-grid gap-2">

                        <a href="{{ route('products.detail',$product) }}"
                           class="btn btn-success">

                            <i class="fas fa-eye"></i>
                            Xem chi tiết

                        </a>

                        <form action="{{ route('wishlist.toggle',$product) }}"
                              method="POST">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-danger w-100">

                                <i class="fas fa-heart-broken"></i>
                                Bỏ yêu thích

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="col-12">

            <div class="empty-state text-center py-5">

                <i class="fas fa-heart-broken"
                   style="font-size:60px;color:#ccc;"></i>

                <h4 class="mt-3">
                    Chưa có sản phẩm yêu thích
                </h4>

                <p class="text-muted">
                    Hãy thêm sản phẩm bạn yêu thích để xem lại sau.
                </p>

                <a href="{{ route('products.all') }}"
                   class="btn btn-success">

                    <i class="fas fa-store"></i>
                    Khám phá sản phẩm

                </a>

            </div>

        </div>

        @endforelse

    </div>

</div>

@endsection