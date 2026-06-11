@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@push('styles')
<style>
.product-detail-wrapper{
    max-width:1000px;
    margin:40px auto;
    padding:0 15px;
}

.product-detail-card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,.05);
}

.product-detail-header{
    padding:20px 25px;
    border-bottom:1px solid #f1f1f1;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#fafafa;
}

.product-detail-header h2{
    margin:0;
    font-size:22px;
    font-weight:700;
}

.product-detail-body{
    padding:25px;
}

.product-layout{
    display:grid;
    grid-template-columns:350px 1fr;
    gap:30px;
}

.product-image-box{
    border:1px solid #e5e7eb;
    border-radius:12px;
    overflow:hidden;
    background:#fff;
}

.product-image-box img{
    width:100%;
    height:350px;
    object-fit:cover;
    display:block;
}

.product-image-placeholder{
    height:350px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#999;
    font-size:50px;
    background:#f8f9fa;
}

.info-table{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.info-item{
    display:flex;
    border-bottom:1px dashed #e5e7eb;
    padding-bottom:12px;
}

.info-label{
    width:150px;
    font-weight:700;
    color:#374151;
}

.info-value{
    flex:1;
    color:#111827;
}

.price-text{
    font-size:22px;
    font-weight:700;
    color:#dc2626;
}

.status-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
}

.status-in{
    background:#e8f5e9;
    color:#2e7d32;
}

.status-out{
    background:#ffebee;
    color:#c62828;
}

.description-box{
    margin-top:10px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:10px;
    padding:15px;
    line-height:1.8;
}

.product-footer{
    padding:20px 25px;
    border-top:1px solid #f1f1f1;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

.btn-action{
    height:42px;
    padding:0 20px;
    border-radius:8px;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-weight:600;
}

.btn-back{
    border:1px solid #d1d5db;
    color:#374151;
    background:#fff;
}

.btn-edit{
    background:#0d6efd;
    color:#fff;
}

.btn-edit:hover{
    color:#fff;
}

@media(max-width:768px){
    .product-layout{
        grid-template-columns:1fr;
    }

    .product-image-box img{
        height:250px;
    }
}
</style>
@endpush

@section('content')

{{-- <div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <a href="{{ route('products.index') }}">Quản lý sản phẩm</a>
        <span class="sep">›</span>
        <span class="cur">Chi tiết sản phẩm</span>
    </div>
</div> --}}

<div class="product-detail-wrapper">

    <div class="product-detail-card">

        <div class="product-detail-header">
            <h2>
                <i class="fas fa-box-open"></i>
                Chi tiết sản phẩm
            </h2>
        </div>

        <div class="product-detail-body">

            <div class="product-layout">

                {{-- Ảnh sản phẩm --}}
                <div>

                    @if($product->image)

                        @php
                            $imageUrl = str_contains($product->image,'/')
                                ? asset('storage/'.$product->image)
                                : asset('images/'.$product->image);
                        @endphp

                        <div class="product-image-box">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $product->name }}">
                        </div>

                    @else

                        <div class="product-image-box">
                            <div class="product-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        </div>

                    @endif

                </div>

                {{-- Thông tin --}}
                <div>

                    <div class="info-table">

                        <div class="info-item">
                            <div class="info-label">ID</div>
                            <div class="info-value">
                                #{{ $product->id }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Tên sản phẩm</div>
                            <div class="info-value">
                                <strong>{{ $product->name }}</strong>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Danh mục</div>
                            <div class="info-value">
                                {{ $product->category->name ?? 'Chưa phân loại' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Giá bán</div>
                            <div class="info-value">
                                <span class="price-text">
                                    {{ number_format($product->price) }} đ
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Số lượng</div>
                            <div class="info-value">
                                {{ $product->quantity }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Trạng thái</div>
                            <div class="info-value">

                                @if($product->quantity > 0)
                                    <span class="status-badge status-in">
                                        <i class="fas fa-check-circle"></i>
                                        Còn hàng
                                    </span>
                                @else
                                    <span class="status-badge status-out">
                                        <i class="fas fa-times-circle"></i>
                                        Hết hàng
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>

                    @if($product->description)
                    <div class="mt-4">
                        <h5>Mô tả sản phẩm</h5>

                        <div class="description-box">
                            {{ $product->description }}
                        </div>
                    </div>
                    @endif

                </div>

            </div>

        </div>

        <div class="product-footer">

            <a href="{{ route('products.index') }}"
                class="btn-action btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>

            <a href="{{ route('products.edit', $product->id) }}"
                class="btn-action btn-edit">
                <i class="fas fa-pen"></i>
                Chỉnh sửa
            </a>

        </div>

    </div>

</div>

@endsection