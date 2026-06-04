@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@push('styles')
<style>
/* ĐỒNG BỘ STYLE TRANG CHỦ */
:root {
    --theme-green: #118a44; /* Màu xanh lá chuẩn của trang chủ */
    --theme-green-hover: #0e7338;
    --theme-bg-pale: #f0fdf4;
}

/* Header & Tiêu đề */
.product-admin-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 24px;
}

.product-admin-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-admin-title .title-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--theme-bg-pale);
    color: var(--theme-green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.product-admin-title h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
}

/* Nút bấm (Bo tròn giống nút Mua Ngay ở trang chủ) */
.btn-theme-green {
    background: var(--theme-green);
    color: white;
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-theme-green:hover {
    background: var(--theme-green-hover);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(17, 138, 68, 0.2);
}

.btn-theme-outline {
    background: white;
    color: var(--theme-green);
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 600;
    border: 1px solid var(--theme-green);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-theme-outline:hover {
    background: var(--theme-bg-pale);
    color: var(--theme-green-hover);
}

/* Form Lọc & Tìm kiếm (Bo tròn giống thanh tìm kiếm trang chủ) */
.product-filter-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    margin-bottom: 24px;
}

.product-filter-grid {
    display: grid;
    grid-template-columns: minmax(250px, 1fr) 180px 180px auto auto;
    gap: 16px;
    align-items: center;
}

.filter-input-rounded {
    border-radius: 50px !important;
    padding: 10px 20px !important;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
    transition: all 0.2s ease;
    width: 100%;
    outline: none;
}
.filter-input-rounded:focus {
    background-color: white;
    border-color: var(--theme-green);
    box-shadow: 0 0 0 3px var(--theme-bg-pale);
}

/* Bảng dữ liệu */
.product-table-card {
    background: white;
    border-radius: 16px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
}

.admin-table th {
    background: var(--theme-bg-pale);
    color: var(--theme-green);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    padding: 16px;
    border-bottom: 2px solid #e2e8f0;
}

.admin-table td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #edf2f7;
}

.admin-table tbody tr:hover {
    background-color: #f8fafc;
}

/* Nút thao tác (Sửa/Xóa) */
.product-action-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.product-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    transition: all 0.2s ease;
}
.product-icon-btn:hover { background: var(--theme-bg-pale); color: var(--theme-green); }
.product-icon-btn.warning:hover { background: #fff7ed; color: #ea580c; }
.product-icon-btn.danger:hover { background: #fef2f2; color: #dc2626; }

/* Phân trang */
.product-pagination {
    padding: 24px;
    background: white;
    display: flex;
    flex-direction: column-reverse;
    align-items: center;
    gap: 12px;
}
.product-pagination .pagination { margin-bottom: 0; justify-content: center !important; }
.product-pagination > div:last-child > div:first-child { display: none !important; }

@media (max-width: 991px) {
    .product-filter-grid { grid-template-columns: 1fr 1fr; }
    .product-filter-grid .filter-actions { grid-column: span 2; }
}

@media (max-width: 575px) {
    .product-admin-head, .product-filter-grid { grid-template-columns: 1fr; }
    .product-admin-head { flex-direction: column; align-items: stretch; }
}
</style>
@endpush

@section('content')
<div class="product-admin-head">
    <div class="product-admin-title">
        <span class="title-icon">
            <i class="fas fa-leaf"></i>
        </span>
        <div>
            <h2>Danh sách sản phẩm</h2>
            <div class="text-muted" style="font-size:14px; margin-top:4px;">
                Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} trong
                <strong>{{ $products->total() }}</strong> sản phẩm
            </div>
        </div>
    </div>

    <a class="btn-theme-green" href="{{ route('products.create') }}">
        <i class="fas fa-plus"></i>
        Thêm sản phẩm mới
    </a>
</div>

@if(session('success'))
<div class="alert alert-success" style="border-radius: 12px; background: var(--theme-bg-pale); color: var(--theme-green); border: 1px solid #bbf7d0;">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
</div>
@endif

<div class="product-filter-card">
    <form method="GET" action="{{ route('products.index') }}" class="product-filter-grid">
        <input type="text" name="keyword" class="filter-input-rounded" placeholder="Tìm kiếm sản phẩm..."
            value="{{ $keyword ?? '' }}">

        <select name="category" class="filter-input-rounded">
            <option value="">Tất cả danh mục</option>
            @foreach ($categories as $category)
            <option value="{{ $category->name }}" {{ request('category') === $category->name ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>

        <select name="sort" class="filter-input-rounded">
            <option value="">Sắp xếp mặc định</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
        </select>

        <button type="submit" class="btn-theme-green" style="padding: 10px 20px;">
            <i class="fas fa-search"></i> Lọc
        </button>

        <div class="filter-actions">
            <a href="{{ route('products.index') }}" class="btn-theme-outline" style="padding: 10px 20px;">
                <i class="fas fa-sync-alt"></i> Xóa
            </a>
        </div>
    </form>
</div>

<div class="product-table-card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Kho</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $imageUrl = null;
                    if ($product->image) {
                        $imageUrl = str_contains($product->image, '/')
                            ? asset('storage/' . $product->image)
                            : asset('images/' . $product->image);
                    }
                @endphp
                <tr>
                    <td class="product-name-cell">
                        <strong style="font-size: 14px; color: #1e293b;">{{ $product->name }}</strong>
                        <small style="color: #94a3b8; display: block; margin-top: 4px;">Mã SP: #{{ $product->id }}</small>
                    </td>
                    <td>
                        <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 13px; color: #475569;">
                            {{ $product->category->name ?? 'Chưa phân loại' }}
                        </span>
                    </td>
                    <td>
                        <strong style="color: var(--theme-green); font-size: 15px;">{{ number_format($product->price) }} ₫</strong>
                    </td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        @if($imageUrl)
                        <img class="product-thumb" src="{{ $imageUrl }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0;"
                            onerror="this.replaceWith(Object.assign(document.createElement('span'), {className: 'product-no-thumb', innerHTML: '<i class=&quot;fas fa-image&quot;></i>'}))">
                        @else
                        <span class="product-no-thumb" style="display:inline-flex; width:50px; height:50px; border-radius:8px; background:#f1f5f9; align-items:center; justify-content:center; color:#94a3b8;">
                            <i class="fas fa-image"></i>
                        </span>
                        @endif
                    </td>
                    <td>
                        @if((int) $product->quantity > 0)
                        <span style="color: #059669; background: #d1fae5; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-check-circle me-1"></i> Còn hàng
                        </span>
                        @else
                        <span style="color: #dc2626; background: #fee2e2; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-times-circle me-1"></i> Hết hàng
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="product-action-group justify-content-center">
                            <a class="product-icon-btn" href="{{ route('products.show', $product->id) }}" title="Chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a class="product-icon-btn warning" href="{{ route('products.edit', $product->id) }}" title="Sửa">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="product-icon-btn danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 40px 20px;">
                        <div class="empty-state">
                            <div style="font-size: 40px; color: #cbd5e1; margin-bottom: 16px;"><i class="fas fa-box-open"></i></div>
                            <h5 style="color: #475569; margin-bottom: 12px;">Chưa có sản phẩm nào</h5>
                            <a href="{{ route('products.create') }}" class="btn-theme-green">
                                Thêm sản phẩm đầu tiên
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="product-pagination">
        <div style="font-size:13px; color:#64748b; font-weight: 500; text-align: center;">
            Đang hiển thị trang {{ $products->currentPage() }} trên tổng số {{ $products->lastPage() }} trang
        </div>
        <div style="width: 100%;">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection