@extends('layouts.app')

@section('title', 'Danh mục sản phẩm — Siêu thị trực tuyến')

@push('styles')
<style>
/* Tổng quan layout */
.category-page-wrap {
    padding: 40px 0;
    background-color: #f8f9fa;
    min-height: calc(100vh - 400px);
    font-family: 'Be Vietnam Pro', sans-serif;
}

/* Tiêu đề trang */
.page-header-box {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    margin-bottom: 30px;
}

.page-main-title {
    color: #2e7d32;
    font-weight: 700;
    font-size: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

/* Thanh bộ lọc và Tìm kiếm */
.filter-card {
    background: white;
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    padding: 20px;
    margin-bottom: 30px;
}

.search-input-group {
    position: relative;
}

.search-input-group input {
    height: 45px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    padding-left: 16px;
    padding-right: 45px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}

.search-input-group input:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

.search-input-group .btn-search-submit {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 34px;
    height: 34px;
    background: #2e7d32;
    border: none;
    border-radius: 8px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.search-input-group .btn-search-submit:hover {
    background: #1b5e20;
}

.filter-select {
    height: 45px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    color: #495057;
}

.filter-select:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

/* Thiết kế Lưới Thẻ Danh Mục thay cho Table */
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

.category-card-item {
    background: white;
    border-radius: 16px;
    border: 1px solid #edf0f2;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    padding: 24px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-height: 200px;
}

.category-card-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(46, 125, 50, 0.08);
    border-color: rgba(46, 125, 50, 0.2);
}

/* Danh mục con (Thụt lề và có đường nối cấu trúc cây) */
.category-card-item.is-child {
    border-left: 4px solid #81c784;
    background: #fcfdfe;
}

.child-badge-indicator {
    font-size: 11px;
    color: #757575;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 8px;
}

.cat-card-body h5 {
    font-size: 18px;
    font-weight: 700;
    color: #212121;
    margin-bottom: 8px;
    line-height: 1.4;
}

.cat-card-desc {
    font-size: 13.5px;
    color: #616161;
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Trạng thái số lượng / Kho hàng */
.cat-stock-status {
    margin-bottom: 18px;
}

.badge-stock-success {
    background-color: rgba(46, 125, 50, 0.1);
    color: #2e7d32;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12.5px;
}

.badge-stock-empty {
    background-color: rgba(211, 47, 47, 0.1);
    color: #d32f2f;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12.5px;
}

/* Nhóm nút thao tác */
.cat-action-group {
    display: flex;
    gap: 8px;
    border-top: 1px solid #f1f3f5;
    padding-top: 16px;
    margin-top: auto;
}

.cat-btn-action {
    flex: 1;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s;
    text-decoration: none;
}

.cat-btn-view {
    background-color: #f1f8e9;
    color: #2e7d32;
    border: none;
}

.cat-btn-view:hover {
    background-color: #2e7d32;
    color: white;
}

.cat-btn-edit {
    background-color: #fff8e1;
    color: #f57f17;
    border: none;
}

.cat-btn-edit:hover {
    background-color: #f57f17;
    color: white;
}

.cat-btn-delete {
    background-color: #ffebee;
    color: #c62828;
    border: none;
}

.cat-btn-delete:hover:not(:disabled) {
    background-color: #c62828;
    color: white;
}

.cat-btn-delete:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Alert & Checkbox custom */
.custom-form-check .form-check-input:checked {
    background-color: #2e7d32;
    border-color: #2e7d32;
}
</style>
@endsection

@section('content')
<div class="category-page-wrap">
    <div class="container">

        <div class="page-header-box d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <h1 class="page-main-title">
                    <i class="fas fa-layer-group"></i> Danh mục sản phẩm
                </h1>
                <p class="text-muted small mb-0 mt-1">Khám phá và quản lý hệ thống phân loại ngành hàng</p>
            </div>
            @if(auth()->check() && (int) auth()->user()->role === 1)
            <a href="{{ route('categories.create') }}" class="btn btn-success d-inline-flex align-items-center gap-2"
                style="background-color:#2e7d32; border-radius:10px; padding:10px 20px; font-weight:600;">
                <i class="fas fa-plus"></i> Thêm danh mục mới
            </a>
            @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
            style="background-color: #e8f5e9; color: #2e7d32; border-radius:12px;" role="alert">
            <i class="fas fa-check-circle me-2"></i><strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" style="border-radius:12px;"
            role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="filter-card">
            <div class="row g-3 align-items-center">
                <div class="col-lg-5 col-md-6">
                    <form method="GET" action="{{ route('categories.index') }}" id="searchForm">
                        <div class="search-input-group">
                            <input type="text" name="search" class="form-control" id="searchInput"
                                placeholder="Tìm kiếm danh mục theo tên hoặc mô tả..." value="{{ $search ?? '' }}"
                                list="searchSuggestions" autocomplete="off">
                            <datalist id="searchSuggestions"></datalist>
                            <input type="hidden" name="sort" value="{{ $sort ?? 'newest' }}">

                            <button type="submit" class="btn-search-submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 col-md-6">
                    <form method="GET" action="{{ route('categories.index') }}" class="w-100">
                        <input type="hidden" name="search" value="{{ $search ?? '' }}">
                        <select name="sort" class="form-select filter-select" onchange="this.form.submit()">
                            <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Sắp xếp: Mới
                                nhất</option>
                            <option value="oldest" {{ ($sort ?? 'newest') === 'oldest' ? 'selected' : '' }}>Sắp xếp: Cũ
                                nhất</option>
                            <option value="quantity" {{ ($sort ?? 'newest') === 'quantity' ? 'selected' : '' }}>Sắp xếp:
                                Số lượng sản phẩm</option>
                        </select>
                    </form>
                </div>

                <div class="col-lg-4 col-12 d-flex gap-3 justify-content-md-end align-items-center">
                    <div class="form-check custom-form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="hideOutOfStock">
                        <label class="form-check-label text-secondary fw-medium" for="hideOutOfStock"
                            style="font-size: 14px; cursor: pointer;">
                            Ẩn danh mục hết hàng
                        </label>
                    </div>
                    <div class="vertical-divider d-none d-sm-block"
                        style="width: 1px; height: 20px; background: #e0e0e0;"></div>
                    <div class="text-secondary" style="font-size:14px;">
                        Tổng số: <strong class="text-dark"
                            style="color: #2e7d32 !important;">{{ $categories->total() }}</strong> danh mục
                    </div>
                </div>
            </div>
        </div>

        @if($categories->count() > 0)
        <div class="category-grid" id="categoryGridBox">
            @foreach($categories as $category)

            <div class="category-card-item" data-out-of-stock="{{ $category->products_count == 0 ? '1' : '0' }}">
                <div class="cat-card-body">
                    <h5>{{ $category->name }}</h5>
                    <p class="cat-card-desc">
                        {{ $category->description ?: 'Chưa có mô tả chi tiết cho ngành hàng này.' }}</p>

                    <div class="cat-stock-status">
                        @if($category->products_count > 0)
                        <span class="badge-stock-success">
                            <i class="fas fa-box me-1"></i> {{ $category->products_count }} sản phẩm
                        </span>
                        @else
                        <span class="badge-stock-empty">
                            <i class="fas fa-minus-circle me-1"></i> Tạm hết hàng
                        </span>
                        @endif
                    </div>
                </div>

                <div class="cat-action-group">
                    <a href="{{ route('categories.show', $category) }}" class="cat-btn-action cat-btn-view"
                        title="Xem chi tiết">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                    @if(auth()->check() && (int) auth()->user()->role === 1)
                    <a href="{{ route('categories.edit', $category) }}" class="cat-btn-action cat-btn-edit"
                        title="Chỉnh sửa">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                        class="d-inline flex-grow-1"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục cha này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cat-btn-action cat-btn-delete w-100" title="Xóa"
                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Kiểm tra an toàn biến hasParentColumn bằng isset() trước khi duyệt danh mục con --}}
            @if(isset($hasParentColumn) && $hasParentColumn && $category->children)
            @foreach($category->children as $child)
            <div class="category-card-item is-child" data-out-of-stock="{{ $child->products_count == 0 ? '1' : '0' }}">
                <div class="cat-card-body">
                    <div class="child-badge-indicator">
                        <i class="fas fa-level-up-alt fa-rotate-90"></i> Thuộc: {{ $category->name }}
                    </div>
                    <h5>{{ $child->name }}</h5>
                    <p class="cat-card-desc">{{ $child->description ?: 'Chưa có mô tả chi tiết cho phân loại này.' }}
                    </p>

                    <div class="cat-stock-status">
                        @if($child->products_count > 0)
                        <span class="badge-stock-success">
                            <i class="fas fa-box me-1"></i> {{ $child->products_count }} sản phẩm
                        </span>
                        @else
                        <span class="badge-stock-empty">
                            <i class="fas fa-minus-circle me-1"></i> Tạm hết hàng
                        </span>
                        @endif
                    </div>
                </div>

                <div class="cat-action-group">
                    <a href="{{ route('categories.show', $child) }}" class="cat-btn-action cat-btn-view"
                        title="Xem chi tiết">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                    @if(auth()->check() && (int) auth()->user()->role === 1)
                    <a href="{{ route('categories.edit', $child) }}" class="cat-btn-action cat-btn-edit"
                        title="Chỉnh sửa">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <form action="{{ route('categories.destroy', $child) }}" method="POST" class="d-inline flex-grow-1"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục con này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cat-btn-action cat-btn-delete w-100" title="Xóa"
                            {{ $child->products_count > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
            @endif

            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $categories->links() }}
        </div>

        @else
        <div class="text-center py-5 bg-white shadow-sm" style="border-radius:16px; border:1px dashed #e0e0e0;">
            <i class="fas fa-inbox fa-4x mb-3 text-muted" style="color: #cbd5e1 !important;"></i>
            <h4 class="text-secondary fw-bold">Không tìm thấy danh mục nào</h4>
            @if(isset($search) && $search)
            <p class="text-muted">Không tìm thấy kết quả phù hợp cho từ khóa: <strong
                    class="text-danger">"{{ $search }}"</strong></p>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-success px-4"
                style="border-radius:8px; border-color:#2e7d32; color:#2e7d32;">
                <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách tất cả
            </a>
            @elseif(auth()->check() && (int) auth()->user()->role === 1)
            <p class="text-muted">Hệ thống chưa thiết lập danh mục sản phẩm nào.</p>
            <a href="{{ route('categories.create') }}" class="btn btn-success px-4"
                style="background-color:#2e7d32; border-radius:8px;">
                <i class="fas fa-plus me-2"></i> Tạo danh mục đầu tiên
            </a>
            @endif
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchDatalist = document.getElementById('searchSuggestions');
    const hideOutOfStockCheckbox = document.getElementById('hideOutOfStock');

    // ========== Gợi ý tìm kiếm tự động (Live suggest) ==========
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            searchDatalist.innerHTML = '';
            if (query.length < 1) return;

            searchTimeout = setTimeout(function() {
                fetch(
                        `{{ route('categories.searchSuggestions') }}?q=${encodeURIComponent(query)}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        searchDatalist.innerHTML = '';
                        data.forEach(suggestion => {
                            const option = document.createElement('option');
                            option.value = suggestion.name;
                            option.label = suggestion.name + (suggestion
                                .description ? ' - ' + suggestion.description :
                                '');
                            searchDatalist.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Lỗi khi lấy gợi ý:', error));
            }, 300);
        });
    }

    // ========== Ẩn/Hiện thẻ danh mục hết hàng linh hoạt ==========
    if (hideOutOfStockCheckbox) {
        hideOutOfStockCheckbox.addEventListener('change', function() {
            const categoryCards = document.querySelectorAll('.category-card-item');

            categoryCards.forEach(card => {
                const isOutOfStock = card.dataset.outOfStock === '1';
                card.style.display = this.checked && isOutOfStock ? 'none' : 'flex';
            });

            // Lưu trạng thái vào bộ nhớ trình duyệt
            localStorage.setItem('hideCategoriesOutOfStock', this.checked);
        });

        // Khôi phục bộ lọc khi người dùng F5 tải lại trang
        const savedState = localStorage.getItem('hideCategoriesOutOfStock') === 'true';
        if (savedState) {
            hideOutOfStockCheckbox.checked = true;
            hideOutOfStockCheckbox.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush