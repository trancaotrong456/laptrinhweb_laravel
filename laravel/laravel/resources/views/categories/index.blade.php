@extends('layout')

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
                        @if(auth()->check() && (int) auth()->user()->role === 1)
                        <a href="{{ route('categories.create') }}" class="btn btn-success">
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

                    <!-- Form tìm kiếm và sắp xếp -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <form method="GET" action="{{ route('categories.index') }}" class="d-flex gap-2"
                                id="searchForm">
                                <div class="position-relative flex-grow-1">
                                    <input type="text" name="search" class="form-control" id="searchInput"
                                        placeholder="Tìm kiếm theo tên hoặc mô tả..." value="{{ $search ?? '' }}"
                                        list="searchSuggestions" autocomplete="off">
                                    <datalist id="searchSuggestions"></datalist>
                                </div>
                                <input type="hidden" name="sort" value="{{ $sort ?? 'newest' }}">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($search)
                                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form method="GET" action="{{ route('categories.index') }}" class="d-flex gap-2">
                                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>
                                        Mới nhất
                                    </option>
                                    <option value="oldest" {{ ($sort ?? 'newest') === 'oldest' ? 'selected' : '' }}>
                                        Cũ nhất
                                    </option>
                                    <option value="quantity" {{ ($sort ?? 'newest') === 'quantity' ? 'selected' : '' }}>
                                        Số lượng sản phẩm
                                    </option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-3 d-flex gap-2 justify-content-end align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hideOutOfStock">
                                <label class="form-check-label text-secondary" for="hideOutOfStock">
                                    Ẩn danh mục hết hàng
                                </label>
                            </div>
                            <small class="text-muted" style="white-space: nowrap;">
                                Tổng: <strong>{{ $categories->total() }}</strong>
                            </small>
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
                                <tr class="category-row"
                                    data-out-of-stock="{{ $category->products_count == 0 ? '1' : '0' }}">
                                    <td>{{ $categories->firstItem() + $loop->index }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $category->description ? Str::limit($category->description, 50) : 'Chưa có mô tả' }}
                                    </td>
                                    <td>
                                        @if($category->products_count > 0)
                                        <span class="badge bg-success">
                                            {{ $category->products_count }} sản phẩm
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
                                            @if(auth()->check() && (int) auth()->user()->role === 1)
                                            <a href="{{ route('categories.edit', $category) }}"
                                                class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                    {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @if($hasParentColumn)
                                @foreach($category->children as $child)
                                <tr class="category-row"
                                    data-out-of-stock="{{ $child->products_count == 0 ? '1' : '0' }}">
                                    <td></td>
                                    <td>
                                        <span class="text-muted">&nbsp;&nbsp;&nbsp;└─</span>
                                        {{ $child->name }}
                                    </td>
                                    <td>
                                        {{ $child->description ? Str::limit($child->description, 50) : 'Chưa có mô tả' }}
                                    </td>
                                    <td>
                                        @if($child->products_count > 0)
                                        <span class="badge bg-success">
                                            {{ $child->products_count }} sản phẩm
                                        </span>
                                        @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Hết hàng
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $child) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->check() && (int) auth()->user()->role === 1)
                                            <a href="{{ route('categories.edit', $child) }}"
                                                class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $child) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                    {{ $child->products_count > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $categories->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không tìm thấy danh mục nào</h5>
                        @if($search)
                        <p class="text-muted">Không có kết quả cho từ khóa: <strong>"{{ $search }}"</strong></p>
                        <a href="{{ route('categories.index') }}" class="btn btn-success">
                            <i class="fas fa-arrow-left me-1"></i>Xem tất cả danh mục
                        </a>
                        @elseif(auth()->check() && (int) auth()->user()->role === 1)
                        <a href="{{ route('categories.create') }}" class="btn btn-success">
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
    const searchInput = document.getElementById('searchInput');
    const searchDatalist = document.getElementById('searchSuggestions');
    const hideOutOfStockCheckbox = document.getElementById('hideOutOfStock');

    // ========== Gợi ý tìm kiếm ==========
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        // Xóa các suggestion cũ
        searchDatalist.innerHTML = '';

        if (query.length < 1) {
            return;
        }

        searchTimeout = setTimeout(function() {
            fetch(`{{ route('categories.searchSuggestions') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchDatalist.innerHTML = '';
                    data.forEach(suggestion => {
                        const option = document.createElement('option');
                        option.value = suggestion.name;
                        option.label = suggestion.name + (suggestion.description ?
                            ' - ' + suggestion.description : '');
                        searchDatalist.appendChild(option);
                    });
                })
                .catch(error => console.error('Lỗi khi lấy gợi ý:', error));
        }, 300); // Delay 300ms để tránh nhiều request
    });

    // ========== Ẩn/hiện danh mục hết hàng ==========
    hideOutOfStockCheckbox.addEventListener('change', function() {
        const categoryRows = document.querySelectorAll('.category-row');

        categoryRows.forEach(row => {
            const isOutOfStock = row.dataset.outOfStock === '1';
            row.style.display = this.checked && isOutOfStock ? 'none' : '';
        });

        // Lưu trạng thái checkbox vào localStorage
        localStorage.setItem('hideOutOfStock', this.checked);
    });

    // Khôi phục trạng thái checkbox từ localStorage
    const savedState = localStorage.getItem('hideOutOfStock') === 'true';
    if (savedState) {
        hideOutOfStockCheckbox.checked = true;
        hideOutOfStockCheckbox.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
