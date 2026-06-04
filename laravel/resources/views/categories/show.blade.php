@extends('layout')
@section('title', $category->name . ' - Danh mục')
@section('content')
<style>
    .bg-gradient-green {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
    }
    .text-green {
        color: #28a745 !important;
    }
    .btn-green {
        background-color: #28a745;
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-green:hover {
        background-color: #1e7e34;
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="container py-4">
    <div class="card border-0 rounded-4 bg-gradient-green text-white p-4 p-md-5 mb-4 position-relative shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8 position-relative" style="z-index: 2;">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm rounded-circle me-3 text-green shadow-sm" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold shadow-sm">Danh mục</span>
                </div>
                <h1 class="display-5 fw-bold mb-2">{{ $category->name }}</h1>
                <p class="lead opacity-90 mb-0">
                    {{ $category->description ?: 'Thỏa thích lựa chọn hàng nghìn mặt hàng tươi sạch mỗi ngày.' }}
                </p>
                <div class="mt-3 fs-6 opacity-75">
                    <i class="fas fa-fingerprint me-1"></i> ID Danh mục: {{ $category->id }} 
                    <span class="mx-2">|</span> 
                    <i class="fas fa-calendar-alt me-1"></i> Ngày khởi tạo: {{ $category->created_at->format('d/m/Y') }}
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->role === 1)
                <div class="col-md-4 text-md-end mt-4 mt-md-0 position-relative" style="z-index: 2;">
                    <div class="btn-group bg-white p-2 rounded-3 shadow-sm">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-link text-warning text-decoration-none fw-bold px-3">
                            <i class="fas fa-edit me-1"></i>Sửa
                        </a>
                        @if($category->products_count == 0)
                            <span class="text-muted py-1">|</span>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger text-decoration-none fw-bold px-3" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                    <i class="fas fa-trash me-1"></i>Xóa
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="position-absolute end-0 bottom-0 opacity-10 text-white display-1 fw-bold pe-5 pb-3 d-none d-md-block" style="font-size: 8rem; pointer-events: none;">
            <i class="fas fa-leaf"></i>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-6">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="fas fa-boxes text-green me-2"></i>Sản phẩm đang bán
            </h4>
        </div>
        <div class="col-6 text-end">
            <span class="fs-5 fw-bold text-green bg-light px-3 py-2 rounded-3">
                Tổng số: {{ $products->total() }} mặt hàng
            </span>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="row g-3 mb-4">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card" style="height: 100%;">
                    <a href="{{ route('products.detail', $product) }}" class="pc-img-wrap d-block">
                        @if($product->image)
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                            onerror="this.parentElement.innerHTML='<div class=\'no-img\'><i class=\'fas fa-image\'></i></div>'">
                        @else
                        <div class="no-img"><i class="fas fa-image"></i></div>
                        @endif
                        
                        @if($product->quantity <= 0)
                            <span class="pc-badge" style="background:#dc3545;">Hết hàng</span>
                        @elseif($product->quantity <= 10)
                            <span class="pc-badge" style="background:#ffc107;color:#000;">Sắp hết</span>
                        @endif

                        {{-- Add to cart overlay --}}
                        @auth
                        <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="pc-add-overlay" title="Thêm vào giỏ"
                                {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                        @endauth
                    </a>
                    <div class="pc-body d-flex flex-column" style="height: calc(100% - 200px);">
                        <div class="pc-origin">
                            {{ $category->name }}
                        </div>
                        <a href="{{ route('products.detail', $product) }}" class="pc-name" title="{{ $product->name }}">{{ $product->name }}</a>
                        <div class="pc-price-row d-flex justify-content-between align-items-end mt-auto">
                            <span class="pc-price">{{ number_format($product->price) }}đ</span>
                            @if(auth()->check() && auth()->user()->role === 1)
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning" style="padding: 2px 8px; font-size: 11px; border-radius: 4px;" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            <div class="shadow-sm rounded-pill p-1 bg-light">
                {{ $products->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-5 my-4 bg-light rounded-4 border border-dashed p-5">
            <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-apple-alt fa-2x text-muted"></i>
            </div>
            <h4 class="fw-bold text-secondary">Chưa có mặt hàng nào!</h4>
            <p class="text-muted max-w-md mx-auto mb-4">Danh mục "{{ $category->name }}" hiện tại chưa được cập nhật sản phẩm nào vào hệ thống bán lẻ.</p>
            @if(auth()->check() && auth()->user()->role === 1)
                <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="btn btn-green px-4 py-2">
                    <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm Đầu Tiên
                </a>
            @else
                <a href="{{ route('home') }}" class="btn btn-green px-4 py-2">
                    <i class="fas fa-home me-2"></i>Về Trang Chủ
                </a>
            @endif
        </div>
    @endif
</div>
@endsection