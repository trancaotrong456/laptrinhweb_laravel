@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-tags me-2"></i>Quản lý danh mục
                        </h4>
                        @if(auth()->check() && auth()->user()->role === 1)
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Thêm danh mục mới
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form tìm kiếm + sắp xếp + bộ lọc trên cùng một hàng -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('categories.index') }}" id="searchForm" class="d-flex flex-wrap align-items-center gap-2">
                                <!-- Ô tìm kiếm (co giãn) -->
                                <div class="flex-grow-1" style="min-width: 200px;">
                                    <div class="position-relative">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                               placeholder="Tìm kiếm theo tên hoặc mô tả..."
                                               value="{{ $search ?? '' }}"
                                               list="searchSuggestions" autocomplete="off">
                                        <datalist id="searchSuggestions"></datalist>
                                    </div>
                                </div>
                                <!-- Nút tìm kiếm và nút xóa lọc -->
                                <div>
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-search me-1"></i>Tìm kiếm
                                    </button>
                                    @if($search)
                                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>Xóa
                                        </a>
                                    @endif
                                </div>
                                <!-- Sắp xếp -->
                                <div style="width: 200px;">
                                    <select name="sort" class="form-select" id="sortSelect">
                                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>🆕 Mới nhất</option>
                                        <option value="oldest" {{ ($sort ?? 'newest') === 'oldest' ? 'selected' : '' }}>⏳ Cũ nhất</option>
                                        <option value="quantity" {{ ($sort ?? 'newest') === 'quantity' ? 'selected' : '' }}>📦 Số lượng sản phẩm</option>
                                    </select>
                                </div>
                                <!-- Bộ lọc nhanh -->
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" id="filterAllBtn">
                                        <i class="fas fa-list me-1"></i>Tất cả
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="filterOutOfStockBtn">
                                        <i class="fas fa-check-circle me-1"></i>Hết hàng
                                    </button>
                                </div>
                                <!-- Checkbox ẩn danh mục hết hàng -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hideOutOfStock">
                                    <label class="form-check-label" for="hideOutOfStock">
                                        Ẩn danh mục hết hàng
                                    </label>
                                </div>
                                <!-- Tổng số -->
                                <div class="text-muted">
                                    Tổng: <strong>{{ $categories->total() }}</strong>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Tên danh mục</th>
                                        <th width="35%">Mô tả</th>
                                        <th width="15%">Số sản phẩm</th>
                                        <th width="20%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr class="category-row" data-out-of-stock="{{ $category->products_count == 0 ? '1' : '0' }}">
                                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if($category->parent)
                                                <small class="text-muted"> ({{ $category->parent->name }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $category->description ? Str::limit($category->description, 50) : 'Chưa có mô tả' }}
                                        </td>
                                        <td>
                                            @if($category->products_count > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-box me-1"></i>{{ $category->products_count }} sản phẩm
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Hết hàng
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('categories.show', $category) }}"
                                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(auth()->check() && auth()->user()->role === 1)
                                                    <a href="{{ route('categories.edit', $category) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('categories.destroy', $category) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Xóa" {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-center mt-4">
                            @if ($categories->hasPages())
                                <nav>
                                    <ul class="pagination">
                                        @if ($categories->onFirstPage())
                                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $categories->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                                        @endif

                                        @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                            @if ($page == $categories->currentPage())
                                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach

                                        @if ($categories->hasMorePages())
                                            <li class="page-item"><a class="page-link" href="{{ $categories->nextPageUrl() }}" rel="next">&raquo;</a></li>
                                        @else
                                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không tìm thấy danh mục nào</h5>
                            @if($search)
                                <p class="text-muted">Không có kết quả cho từ khóa: <strong>"{{ $search }}"</strong></p>
                                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i>Xem tất cả danh mục
                                </a>
                            @elseif(auth()->check() && auth()->user()->role === 1)
                                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Thêm danh mục đầu tiên
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tự động submit form khi thay đổi sắp xếp
    const sortSelect = document.getElementById('sortSelect');
    const searchForm = document.getElementById('searchForm');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }

    // Gợi ý tìm kiếm
    const searchInput = document.getElementById('searchInput');
    const searchDatalist = document.getElementById('searchSuggestions');
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            searchDatalist.innerHTML = '';
            if (query.length < 2) return;
            searchTimeout = setTimeout(function() {
                fetch(`{{ route('categories.searchSuggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchDatalist.innerHTML = '';
                        data.forEach(suggestion => {
                            const option = document.createElement('option');
                            option.value = suggestion.name;
                            option.label = suggestion.name + (suggestion.description ? ' - ' + suggestion.description : '');
                            searchDatalist.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Lỗi gợi ý:', error));
            }, 300);
        });

        // Khi chọn gợi ý thì submit form
        searchInput.addEventListener('change', function() {
            if (this.value.trim() !== '') {
                searchForm.submit();
            }
        });
    }

    // Lọc client-side (ẩn/hiện danh mục hết hàng)
    const hideOutOfStockCheckbox = document.getElementById('hideOutOfStock');
    const filterAllBtn = document.getElementById('filterAllBtn');
    const filterOutOfStockBtn = document.getElementById('filterOutOfStockBtn');
    let currentFilter = 'all'; // all, outofstock, hide

    function applyFilter() {
        const categoryRows = document.querySelectorAll('.category-row');
        const hideChecked = hideOutOfStockCheckbox.checked;
        categoryRows.forEach(row => {
            const isOutOfStock = row.dataset.outOfStock === '1';
            let shouldShow = true;
            if (currentFilter === 'outofstock') {
                shouldShow = isOutOfStock;
            } else if (currentFilter === 'hide' || hideChecked) {
                shouldShow = !isOutOfStock;
            } else {
                shouldShow = true;
            }
            row.style.display = shouldShow ? '' : 'none';
        });
        localStorage.setItem('categoryFilter', currentFilter);
        localStorage.setItem('hideOutOfStock', hideOutOfStockCheckbox.checked);
    }

    if (filterAllBtn && filterOutOfStockBtn && hideOutOfStockCheckbox) {
        filterAllBtn.addEventListener('click', function() {
            currentFilter = 'all';
            hideOutOfStockCheckbox.checked = false;
            applyFilter();
            filterAllBtn.classList.add('active');
            filterOutOfStockBtn.classList.remove('active');
        });
        filterOutOfStockBtn.addEventListener('click', function() {
            currentFilter = 'outofstock';
            hideOutOfStockCheckbox.checked = false;
            applyFilter();
            filterOutOfStockBtn.classList.add('active');
            filterAllBtn.classList.remove('active');
        });
        hideOutOfStockCheckbox.addEventListener('change', function() {
            if (this.checked) {
                currentFilter = 'hide';
                filterAllBtn.classList.remove('active');
                filterOutOfStockBtn.classList.remove('active');
            } else {
                if (currentFilter === 'hide') {
                    currentFilter = 'all';
                    filterAllBtn.classList.add('active');
                }
            }
            applyFilter();
        });

        // Khôi phục trạng thái từ localStorage
        const savedFilter = localStorage.getItem('categoryFilter');
        const savedHide = localStorage.getItem('hideOutOfStock') === 'true';
        if (savedFilter === 'outofstock') {
            currentFilter = 'outofstock';
            filterOutOfStockBtn.classList.add('active');
            hideOutOfStockCheckbox.checked = false;
        } else if (savedFilter === 'hide' || savedHide) {
            currentFilter = 'hide';
            hideOutOfStockCheckbox.checked = true;
        } else {
            currentFilter = 'all';
            filterAllBtn.classList.add('active');
            hideOutOfStockCheckbox.checked = false;
        }
        applyFilter();
    }
});
</script>
@endsection