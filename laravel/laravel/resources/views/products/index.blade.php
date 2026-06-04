@extends('layout')

@section('title', 'Quản lý sản phẩm')

@push('styles')
<style>
.product-admin-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 18px;
}

.product-admin-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-admin-title .title-icon {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: var(--green-pale);
    color: var(--green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.product-admin-title h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: var(--text);
}

.product-filter-card {
    margin-bottom: 18px;
}

.product-filter-grid {
    display: grid;
    grid-template-columns: minmax(220px, 1fr) 190px 170px auto auto;
    gap: 10px;
    align-items: center;
}

.product-table-card {
    padding: 0;
    overflow: hidden;
}

.product-thumb {
    width: 58px;
    height: 58px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #f5f5f5;
    object-fit: cover;
    display: block;
}

.product-no-thumb {
    width: 58px;
    height: 58px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--green-pale2);
    color: var(--green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.product-name-cell {
    min-width: 180px;
}

.product-name-cell strong {
    display: block;
    font-size: 13.5px;
    color: var(--text);
}

.product-name-cell small {
    display: block;
    margin-top: 3px;
    color: var(--text-light);
    font-size: 12px;
}

/* Các nút xem, sửa, xóa thẳng hàng */
.product-action-group {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: nowrap;
    /* Không cho phép rớt dòng */
}

.product-icon-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    background: white;
    color: var(--text-soft);
    transition: var(--transition);
}

.product-icon-btn:hover {
    color: var(--green);
    border-color: var(--green-light);
    background: var(--green-pale);
}

.product-icon-btn.warning:hover {
    color: #ef6c00;
    border-color: #ffe0b2;
    background: #fff3e0;
}

.product-icon-btn.danger:hover {
    color: var(--red);
    border-color: #ffcdd2;
    background: #ffebee;
}

/* ── KHU VỰC SỬA: Thay đổi vị trí dòng "Showing..." xuống dưới thanh phân trang ── */
.product-pagination {
    padding: 20px 18px;
    border-top: 1px solid var(--border);
    display: flex;
    flex-direction: column-reverse;
    /* Đảo ngược vị trí: Thanh phân trang lên trên, Text mặc định xuống dưới */
    align-items: center;
    justify-content: center;
    gap: 12px;
}

/* Ép thanh phân trang mặc định của Bootstrap căn giữa */
.product-pagination>div:last-child .pagination,
.product-pagination .pagination {
    margin-bottom: 0;
    justify-content: center !important;
}

/* Ép text mặc định của Bootstrap (Showing 1 to 5...) ẩn phần hiển thị gốc hoặc căn giữa */
.product-pagination>div:last-child>div:first-child {
    display: none !important;
    /* Ẩn đi phần text mặc định bị lệch nếu có */
}

.product-pagination>div:last-child>div:last-child {
    text-align: center !important;
}

@media (max-width: 991px) {
    .product-filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .product-filter-grid .filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 575px) {

    .product-admin-head,
    .product-filter-grid {
        grid-template-columns: 1fr;
    }

    .product-admin-head {
        align-items: stretch;
        flex-direction: column;
    }

    .product-admin-head a,
    .product-filter-grid .filter-actions,
    .product-filter-grid .filter-actions a,
    .product-filter-grid .filter-actions button {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Quản lý sản phẩm</span>
    </div>
</div>

<div class="container py-4">
    <div class="product-admin-head">
        <div class="product-admin-title">
            <span class="title-icon">
                <i class="fas fa-box-open"></i>
            </span>
            <div>
                <h2>Danh sách sản phẩm</h2>
                <div class="text-muted" style="font-size:13px;">
                    Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} trong
                    {{ $products->total() }} sản phẩm
                </div>
            </div>
        </div>

        <a class="btn-green" href="{{ route('products.create') }}">
            <i class="fas fa-plus"></i>
            Thêm sản phẩm
        </a>
    </div>

    @if(session('success'))
    <div class="alert-st success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="card-white product-filter-card">
        <form method="GET" action="{{ route('products.index') }}" class="product-filter-grid">
            <input type="text" name="keyword" class="form-control-st" placeholder="Nhập tên sản phẩm..."
                value="{{ $keyword ?? '' }}">

            <select name="category" class="form-control-st">
                <option value="">Tất cả danh mục</option>
                @foreach ($categories as $category)
                <option value="{{ $category->name }}" {{ request('category') === $category->name ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>

            <select name="sort" class="form-control-st">
                <option value="">Sắp xếp mặc định</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần
                </option>
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
            </select>

            <button type="submit" class="btn-green">
                <i class="fas fa-search"></i>
                Tìm kiếm
            </button>

            <div class="filter-actions">
                <a href="{{ route('products.index') }}" class="btn-green-outline">
                    <i class="fas fa-rotate-right"></i>
                    Xóa lọc
                </a>
            </div>
        </form>
    </div>

    <div class="card-white product-table-card">
        <div class="table-responsive">
            <table class="admin-table mb-0">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Ảnh</th>
                        <th>Trạng thái</th>
                        <th style="width:145px; text-align: center;">Thao tác</th>
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
                            <strong>{{ $product->name }}</strong>
                            <small>ID: {{ $product->id }}</small>
                        </td>
                        <td>
                            {{ $product->category->name ?? 'Chưa phân loại' }}
                        </td>
                        <td>
                            <strong class="text-red">{{ number_format($product->price) }}đ</strong>
                        </td>
                        <td>
                            {{ $product->quantity }}
                        </td>
                        <td>
                            @if($imageUrl)
                            <img class="product-thumb" src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                onerror="this.replaceWith(Object.assign(document.createElement('span'), {className: 'product-no-thumb', innerHTML: '<i class=&quot;fas fa-image&quot;></i>'}))">
                            @else
                            <span class="product-no-thumb">
                                <i class="fas fa-image"></i>
                            </span>
                            @endif
                        </td>
                        <td>
                            @if((int) $product->quantity > 0)
                            <span class="stock-badge in">
                                <i class="fas fa-check-circle"></i>
                                Còn hàng
                            </span>
                            @else
                            <span class="stock-badge out">
                                <i class="fas fa-times-circle"></i>
                                Hết hàng
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="product-action-group justify-content-center">
                                <a class="product-icon-btn" href="{{ route('products.show', $product->id) }}"
                                    title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a class="product-icon-btn warning" href="{{ route('products.edit', $product->id) }}"
                                    title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Xóa sản phẩm này?')">
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
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <h5>Không có sản phẩm nào</h5>
                                <a href="{{ route('products.create') }}" class="btn-green mt-2">
                                    Thêm sản phẩm đầu tiên
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── VÙNG ĐỔI CẤU TRÚC: Đưa text thông tin / số trang xuống dưới cùng và căn giữa ── --}}
        @if($products->hasPages())
        <div class="product-pagination">
            <div style="font-size:13px; color:var(--text-soft); font-weight: 500; text-align: center; width: 100%;">
                Trang {{ $products->currentPage() }} / {{ $products->lastPage() }}
            </div>
            <div style="width: 100%;">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection