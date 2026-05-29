@extends('layout')

@section('title', 'Danh sách sản phẩm - Siêu thị Mini')

@section('content')
<div class="container py-4">
    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
        <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            <i class="fas fa-box-open me-2 text-primary"></i>
            Tất cả sản phẩm
        </h3>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="fas fa-store me-2"></i>
            Về trang chủ
        </a>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 overflow-hidden rounded-4" style="transition: all 0.4s ease;">
                <a href="{{ route('products.detail', $product) }}" class="position-relative overflow-hidden d-block"
                    style="height: 220px;">
                    @if($product->image)
                    <img src="{{ asset('images/' . $product->image) }}"
                        class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                    @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                        <i class="fas fa-gift fa-3x"></i>
                    </div>
                    @endif
                </a>

                <div class="card-body p-3 d-flex flex-column">
                    <a href="{{ route('products.detail', $product) }}" class="text-decoration-none text-dark">
                        <h6 class="fw-bold mb-2"
                            style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $product->name }}
                        </h6>
                    </a>

                    <div class="mb-2">
                        <span class="h5 fw-bolder text-danger">{{ number_format($product->price) }}đ</span>
                    </div>

                    <div class="mt-auto">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-bold rounded-3"
                                {{ $product->quantity <= 0 ? 'disabled title="Hết hàng"' : '' }}>
                                <i class="fas fa-cart-shopping me-1"></i>
                                Mua ngay
                            </button>
                        </form>

                        <a href="{{ route('products.detail', $product) }}"
                            class="btn btn-outline-secondary w-100 mt-2">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-boxes fa-5x text-muted mb-4 opacity-50"></i>
            <h3 class="text-muted mb-4">Không có sản phẩm nào</h3>
            <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                <i class="fas fa-tags me-2"></i>
                Xem khuyến mãi
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
