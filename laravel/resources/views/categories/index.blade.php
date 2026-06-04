@extends('layouts.admin')

@section('title', 'Quản lý danh mục - TTP Admin')

@push('styles')
<style>
/* ── SEARCH BAR ─────────────────────────────────────────── */
.search-bar-wrapper {
    display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
    margin-bottom: 20px;
}
.search-input {
    flex: 1; height: 44px; padding: 0 18px; min-width: 250px;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 14px; font-family: inherit; color: #1e293b;
    background: #fff; outline: none; transition: all .2s;
}
.search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.search-btn {
    height: 44px; padding: 0 22px;
    background: #2563eb; color: #fff; border: none;
    border-radius: 10px; font-size: 13.5px; font-weight: 600;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all .2s; font-family: inherit;
}
.search-btn:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }

/* ── TABLE HEADER ──────────────────────────────────────── */
.cat-table-header {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.cat-col-id     { width: 50px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cat-col-name   { flex: 1; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cat-col-desc   { flex: 1.5; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cat-col-count  { width: 120px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cat-col-action { width: 120px; flex-shrink: 0; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }

/* ── CAT ROW ──────────────────────────────────────────── */
.cat-row {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
    transition: background .15s;
}
.cat-row:last-child { border-bottom: none; }
.cat-row:hover { background: #f8faff; }

.cat-row-id { width: 50px; flex-shrink: 0; font-size: 13.5px; font-weight: 600; color: #94a3b8; }
.cat-row-name { flex: 1; min-width: 0; font-size: 14px; font-weight: 600; color: #1e293b; }
.cat-row-desc { flex: 1.5; min-width: 0; font-size: 13px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cat-row-count { width: 120px; flex-shrink: 0; }
.cat-row-action { width: 120px; flex-shrink: 0; display: flex; align-items: center; gap: 7px; justify-content: flex-end; }

/* ── EMPTY ─────────────────────────────────────────────── */
.empty-box {
    text-align: center; padding: 60px 20px; color: #94a3b8;
}
.empty-box i { font-size: 48px; margin-bottom: 14px; display: block; opacity: .5; }
.empty-box p { font-size: 14px; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-tags" style="color:#2563eb;font-size:20px;"></i>
            Quản lý danh mục
        </h1>
        <div class="page-sub">Tổng cộng: <strong>{{ $categories->total() ?? 0 }}</strong> danh mục</div>
    </div>
    @if(auth()->check() && auth()->user()->role === 1)
    <a href="{{ route('categories.create') }}" class="btn-primary-admin">
        <i class="fas fa-plus"></i> Thêm danh mục mới
    </a>
    @endif
</div>

{{-- SUCCESS / ERRORS --}}
@if(session('success'))
<div class="admin-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if($errors->any())
<div class="admin-alert error">
    <i class="fas fa-exclamation-triangle"></i>
    <ul style="margin:0;padding-left:20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- SEARCH & FILTER --}}
<div class="search-bar-wrapper">
    <form method="GET" action="{{ route('categories.index') }}" style="display:flex;gap:12px;flex:1;" id="searchForm">
        <input type="text" name="search" class="search-input" id="searchInput"
            placeholder="Tìm kiếm theo tên hoặc mô tả..." value="{{ $search ?? '' }}"
            list="searchSuggestions" autocomplete="off">
        <datalist id="searchSuggestions"></datalist>
        <input type="hidden" name="sort" value="{{ $sort ?? 'newest' }}">
        <button type="submit" class="search-btn"><i class="fas fa-search"></i> Tìm kiếm</button>
        @if($search)
        <a href="{{ route('categories.index') }}" class="btn-outline-admin"><i class="fas fa-times"></i> Xóa</a>
        @endif
    </form>
    <div style="display:flex;gap:12px;align-items:center;">
        <form method="GET" action="{{ route('categories.index') }}" style="display:flex;">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <select name="sort" class="search-input" style="min-width:auto;width:auto;height:44px;font-weight:600;color:#475569;border-color:#e2e8f0;cursor:pointer;" onchange="this.form.submit()">
                <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="oldest" {{ ($sort ?? 'newest') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                <option value="quantity" {{ ($sort ?? 'newest') === 'quantity' ? 'selected' : '' }}>Số lượng sản phẩm</option>
            </select>
        </form>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#64748b;cursor:pointer;font-weight:500;">
            <input type="checkbox" id="hideOutOfStock" style="width:16px;height:16px;accent-color:#2563eb;"> Ẩn mục hết hàng
        </label>
    </div>
</div>

{{-- TABLE --}}
<div class="admin-card" style="overflow:hidden;">
    {{-- Header --}}
    <div class="cat-table-header">
        <div class="cat-col-id">#</div>
        <div class="cat-col-name">Tên danh mục</div>
        <div class="cat-col-desc">Mô tả</div>
        <div class="cat-col-count">Số sản phẩm</div>
        <div class="cat-col-action">Thao tác</div>
    </div>

    {{-- Rows --}}
    @if($categories->count() > 0)
    @foreach($categories as $category)
    <div class="cat-row category-row" data-out-of-stock="{{ $category->products_count == 0 ? '1' : '0' }}">
        <div class="cat-row-id">{{ $categories->firstItem() + $loop->index }}</div>
        <div class="cat-row-name">
            {{ $category->name }}
        </div>
        <div class="cat-row-desc">
            {{ $category->description ? $category->description : '—' }}
        </div>
        <div class="cat-row-count">
            @if($category->products_count > 0)
            <span class="badge-status active">
                {{ $category->products_count }} sản phẩm
            </span>
            @else
            <span class="badge-status error">
                <i class="fas fa-times-circle" style="font-size:10px;"></i> Hết hàng
            </span>
            @endif
        </div>
        <div class="cat-row-action">
            <a href="{{ route('categories.show', $category) }}" class="icon-btn view" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
            </a>
            @if(auth()->check() && auth()->user()->role === 1)
            <a href="{{ route('categories.edit', $category) }}" class="icon-btn edit" title="Chỉnh sửa">
                <i class="fas fa-pen"></i>
            </a>
            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn delete" title="Xóa" {{ $category->products_count > 0 ? 'disabled' : '' }} style="{{ $category->products_count > 0 ? 'opacity:0.3;cursor:not-allowed;' : '' }}">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </div>

        {{-- Child Categories --}}
        @if(isset($hasParentColumn) && $hasParentColumn && $category->children)
        @foreach($category->children as $child)
        <div class="cat-row category-row" data-out-of-stock="{{ $child->products_count == 0 ? '1' : '0' }}" style="background:#fcfcfc;">
            <div class="cat-row-id"></div>
            <div class="cat-row-name" style="padding-left:16px;">
                <span style="color:#cbd5e1;margin-right:8px;">└─</span> {{ $child->name }}
            </div>
            <div class="cat-row-desc">
                {{ $child->description ? $child->description : '—' }}
            </div>
            <div class="cat-row-count">
                @if($child->products_count > 0)
                <span class="badge-status active">
                    {{ $child->products_count }} sản phẩm
                </span>
                @else
                <span class="badge-status error">
                    <i class="fas fa-times-circle" style="font-size:10px;"></i> Hết hàng
                </span>
                @endif
            </div>
            <div class="cat-row-action">
                <a href="{{ route('categories.show', $child) }}" class="icon-btn view" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </a>
                @if(auth()->check() && auth()->user()->role === 1)
                <a href="{{ route('categories.edit', $child) }}" class="icon-btn edit" title="Chỉnh sửa">
                    <i class="fas fa-pen"></i>
                </a>
                <form action="{{ route('categories.destroy', $child) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="icon-btn delete" title="Xóa" {{ $child->products_count > 0 ? 'disabled' : '' }} style="{{ $child->products_count > 0 ? 'opacity:0.3;cursor:not-allowed;' : '' }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
        @endif
    @endforeach
    @else
    <div class="empty-box">
        <i class="fas fa-tags"></i>
        <p>Không tìm thấy danh mục nào</p>
        @if(auth()->check() && auth()->user()->role === 1)
        <a href="{{ route('categories.create') }}" class="btn-primary-admin" style="display:inline-flex;margin-top:10px;">
            <i class="fas fa-plus"></i> Thêm danh mục
        </a>
        @endif
    </div>
    @endif
</div>

{{-- PAGINATION --}}
@if(isset($categories) && $categories->hasPages())
<div style="margin-top:18px;display:flex;justify-content:center;">
    {{ $categories->links('pagination::bootstrap-5') }}
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchDatalist = document.getElementById('searchSuggestions');
    const hideOutOfStockCheckbox = document.getElementById('hideOutOfStock');

    // ========== Gợi ý tìm kiếm ==========
    let searchTimeout;
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            searchDatalist.innerHTML = '';
            if (query.length < 1) return;

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
                    .catch(error => console.error('Lỗi khi lấy gợi ý:', error));
            }, 300);
        });
    }

    // ========== Ẩn/hiện danh mục hết hàng ==========
    if(hideOutOfStockCheckbox) {
        hideOutOfStockCheckbox.addEventListener('change', function() {
            const categoryRows = document.querySelectorAll('.category-row');
            categoryRows.forEach(row => {
                const isOutOfStock = row.dataset.outOfStock === '1';
                row.style.display = this.checked && isOutOfStock ? 'none' : 'flex';
            });
            localStorage.setItem('hideOutOfStock', this.checked);
        });

        const savedState = localStorage.getItem('hideOutOfStock') === 'true';
        if (savedState) {
            hideOutOfStockCheckbox.checked = true;
            hideOutOfStockCheckbox.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush