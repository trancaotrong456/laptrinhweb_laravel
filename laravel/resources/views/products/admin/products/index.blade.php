@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')

<div class="container py-4">
    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success" style="width: 44px; height: 44px; font-size: 20px;">
                <i class="fas fa-box-open"></i>
            </div>
            <div>
                <h2 class="m-0 fs-4 fw-bold text-dark">Danh sách sản phẩm</h2>
                <div class="text-muted" style="font-size: 13px;">
                    Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} trong {{ $products->total() }} sản phẩm
                </div>
            </div>
        </div>

        <a class="btn btn-success d-flex align-items-center justify-content-center gap-2 px-3 fw-medium" href="{{ route('products.create') }}">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <div class="card card-body border-0 shadow-sm mb-4">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6 col-lg-3">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sản phẩm..." value="{{ $keyword ?? '' }}">
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <select name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <select name="sort" class="form-select">
                    <option value="">Sắp xếp mặc định</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                </select>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="row g-2">
                    <div class="col-6">
                        <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-search"></i> Tìm
                        </button>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-rotate-right"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-3" style="min-width: 200px;">Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Ảnh</th>
                        <th>Trạng thái</th>
                        <th class="text-center pe-3" style="width: 145px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    @php
                        $imageUrl = $product->image ? asset('storage/' . $product->image) : null;
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="d-block fw-bold text-dark" style="font-size: 14px;">{{ $product->name }}</span>
                            <small class="text-muted d-block mt-0.5" style="font-size: 12px;">ID: {{ $product->id }}</small>
                        </td>
                        <td>
                            <span class="text-secondary">{{ $product->category->name ?? 'Chưa phân loại' }}</span>
                        </td>
                        <td>
                            <strong class="text-danger">{{ number_format($product->price) }}đ</strong>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $product->quantity }}</span>
                        </td>
                        <td>
                            @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="rounded-2 border bg-light" style="width: 52px; height: 52px; object-fit: cover; display: block;"
                                 onerror="this.replaceWith(Object.assign(document.createElement('span'), {className: 'd-inline-flex align-items-center justify-content-center rounded-2 border bg-success bg-opacity-10 text-success', style: 'width: 52px; height: 52px; font-size: 16px;', innerHTML: '<i class=&quot;fas fa-image&quot;></i>'}))">
                            @else
                            <span class="d-inline-flex align-items-center justify-content-center rounded-2 border bg-success bg-opacity-10 text-success" style="width: 52px; height: 52px; font-size: 16px;">
                                <i class="fas fa-image"></i>
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($product->status == 'Còn hàng')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 12px; font-weight: 500;">
                                <i class="fas fa-check-circle me-1"></i> Còn hàng
                            </span>
                            @else
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1" style="font-size: 12px; font-weight: 500;">
                                <i class="fas fa-times-circle me-1"></i> Hết hàng
                            </span>
                            @endif
                        </td>
                        <td class="pe-3">
                            <div class="d-flex align-items-center justify-content-center gap-1.5">
                                <a class="btn btn-sm btn-light border p-0 text-secondary d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px;" href="{{ route('products.show', $product->id) }}" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-light border p-0 text-warning d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px;" href="{{ route('products.edit', $product->id) }}" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="updated_at" value="{{ $product->updated_at }}">
                                    <button type="submit" class="btn btn-sm btn-light border p-0 text-danger d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px;" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="fas fa-box-open fs-1 text-secondary opacity-50"></i>
                                <h5 class="m-0 mt-2 text-dark fw-semibold" style="font-size: 16px;">Không có sản phẩm nào</h5>
                                <a href="{{ route('products.create') }}" class="btn btn-success btn-sm mt-1">
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
        <div class="card-footer bg-white border-top p-3 d-flex flex-column-reverse align-items-center gap-2">
            <div class="text-secondary fw-medium" style="font-size: 13px;">
                Trang {{ $products->currentPage() }} / {{ $products->lastPage() }}
            </div>
            <div class="w-100 d-flex justify-content-center custom-bootstrap-pagination">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Khắc phục triệt để lỗi text lặp lại (Showing X to Y...) của Bootstrap layout --}}
@push('styles')
<style>
    .custom-bootstrap-pagination > nav div:first-child { display: none !important; }
    .custom-bootstrap-pagination .pagination { margin-bottom: 0 !important; }
</style>
@endpush
@endsection