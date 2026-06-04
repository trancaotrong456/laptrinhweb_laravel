@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm - TTP Admin')

@push('styles')
<style>
.product-show-card {
    background: #fff;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    margin-bottom: 24px;
}

.product-show-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 40px;
}
@media (max-width: 991px) {
    .product-show-grid { grid-template-columns: 1fr; }
}

.product-show-img-wrap {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-show-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-no-img {
    font-size: 64px;
    color: #cbd5e1;
}

.product-info-title {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 8px;
    line-height: 1.3;
}
.product-info-id {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 24px;
}

.info-table {
    width: 100%;
    border-collapse: collapse;
}
.info-table td {
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
}
.info-label {
    width: 140px;
    color: #64748b;
    font-weight: 600;
    font-size: 14px;
}
.info-value {
    color: #1e293b;
    font-size: 15px;
    font-weight: 500;
}
.info-value.price {
    font-size: 22px;
    font-weight: 800;
    color: #118a44; /* theme-green */
}
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 600;
}
.status-badge.in { background: #d1fae5; color: #059669; }
.status-badge.out { background: #fee2e2; color: #dc2626; }

.action-buttons {
    margin-top: 32px;
    display: flex;
    gap: 12px;
}
.btn-primary-action {
    background: #118a44;
    color: #fff;
    padding: 12px 28px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all .2s;
    border: none;
}
.btn-primary-action:hover {
    background: #0e7338; color: #fff; transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(17,138,68,0.2);
}
.btn-secondary-action {
    background: #fff;
    color: #118a44;
    padding: 12px 28px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all .2s;
    border: 1px solid #118a44;
}
.btn-secondary-action:hover {
    background: #f0fdf4;
    color: #0e7338;
}
</style>
@endpush

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title" style="font-size:24px;font-weight:700;color:#2d3748;">
            <i class="fas fa-info-circle me-2" style="color:#118a44;"></i>
            Chi tiết sản phẩm
        </h1>
        <div class="page-sub text-muted mt-1">Xem thông tin chi tiết về sản phẩm</div>
    </div>
    <a href="{{ route('products.index') }}" class="btn-secondary-action" style="padding: 10px 20px;">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="product-show-card">
    <div class="product-show-grid">
        
        {{-- Hình ảnh --}}
        <div class="product-show-img-wrap">
            @php
                $imageUrl = null;
                if ($product->image) {
                    $imageUrl = str_contains($product->image, '/')
                        ? asset('storage/' . $product->image)
                        : asset('images/' . $product->image);
                }
            @endphp
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-show-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <i class="fas fa-box-open product-no-img" style="display:none;"></i>
            @else
                <i class="fas fa-box-open product-no-img"></i>
            @endif
        </div>

        {{-- Thông tin --}}
        <div>
            <h2 class="product-info-title">{{ $product->name }}</h2>
            <div class="product-info-id">Mã sản phẩm: #{{ $product->id }}</div>

            <table class="info-table">
                <tr>
                    <td class="info-label">Danh mục</td>
                    <td class="info-value">
                        <span style="background: #f1f5f9; padding: 6px 12px; border-radius: 8px; color: #475569; font-size:14px; font-weight:600;">
                            {{ $product->category->name ?? 'Chưa phân loại' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Giá bán</td>
                    <td class="info-value price">{{ number_format($product->price) }} ₫</td>
                </tr>
                <tr>
                    <td class="info-label">Kho</td>
                    <td class="info-value"><strong>{{ $product->quantity }}</strong> sản phẩm</td>
                </tr>
                <tr>
                    <td class="info-label">Trạng thái</td>
                    <td class="info-value">
                        @if((int) $product->quantity > 0)
                            <span class="status-badge in">
                                <i class="fas fa-check-circle"></i> Còn hàng
                            </span>
                        @else
                            <span class="status-badge out">
                                <i class="fas fa-times-circle"></i> Hết hàng
                            </span>
                        @endif
                    </td>
                </tr>
                @if($product->description)
                <tr>
                    <td class="info-label" style="vertical-align: top;">Mô tả</td>
                    <td class="info-value" style="color: #475569; line-height: 1.6;">
                        {{ $product->description }}
                    </td>
                </tr>
                @endif
            </table>

            <div class="action-buttons">
                <a href="{{ route('products.edit', $product->id) }}" class="btn-primary-action">
                    <i class="fas fa-edit"></i> Chỉnh sửa sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection