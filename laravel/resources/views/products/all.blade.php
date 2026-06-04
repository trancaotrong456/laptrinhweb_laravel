@extends('layout')

@section('title', 'Tất cả sản phẩm - Siêu thị trực tuyến')

@section('content')
<div class="container py-4">

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Tất cả sản phẩm</span>
    </div>
</div>

<div class="container">

    @if(session('success'))
    <div class="alert-st success mt-3">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-st error mt-3">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row g-4 mt-0">
        {{-- ─── SIDEBAR FILTER ─── --}}
        <div class="col-lg-3">
            <form id="filterForm" method="GET" action="{{ route('products.all') }}">
                <div class="filter-panel">
            
                    <h5>
                        <i class="fas fa-sliders-h me-2" style="color:#2e7d32;"></i>
                        Bộ lọc sản phẩm
                    </h5>
            
                    {{-- Tìm kiếm --}}
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            class="form-control"
                            placeholder="Nhập tên sản phẩm...">
                    </div>
            
                    <div class="filter-divider"></div>
            
                    {{-- Danh mục --}}
                    <h5>Danh mục</h5>
            
                    @foreach($categories as $category)
                    <div class="filter-label">
                        <input
                            type="checkbox"
                            name="category_id[]"
                            value="{{ $category->id }}"
                            {{ in_array($category->id, request('category_id', [])) ? 'checked' : '' }}
                        >
                        {{ $category->name }}
                    </div>
                    @endforeach
            
                    <div class="filter-divider"></div>
            
                    {{-- Giá --}}
                    <h5>Khoảng giá</h5>
            
                    <div class="mb-2">
                        <input
                            type="number"
                            name="min_price"
                            class="form-control"
                            placeholder="Giá từ"
                            value="{{ request('min_price') }}">
                    </div>
            
                    <div class="mb-3">
                        <input
                            type="number"
                            name="max_price"
                            class="form-control"
                            placeholder="Giá đến"
                            value="{{ request('max_price') }}">
                    </div>
            
                    <div class="filter-divider"></div>
            
                    {{-- Còn hàng --}}
                    <h5>Tình trạng</h5>
            
                    <div class="filter-label">
                        <input
                            type="checkbox"
                            name="in_stock"
                            value="1"
                            {{ request('in_stock') ? 'checked' : '' }}
                        >
                        Chỉ hiển thị sản phẩm còn hàng
                    </div>
            
                    <div class="filter-divider"></div>    
                    <div class="mt-4 d-grid gap-2">
                        <button class="btn-green">
                            <i class="fas fa-search"></i>
                            Áp dụng bộ lọc
                        </button>
            
                        <a href="{{ route('products.all') }}"
                            class="btn-green-outline text-center">
                            Xóa bộ lọc
                        </a>
                    </div>
            
                </div>
            </form>
        </div>

        {{-- ─── PRODUCT GRID ─── --}}
        <div class="col-lg-9">
            {{-- SORT BAR --}}
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div style="font-size:13.5px;color:#757575;">
                    Hiển thị <strong style="color:#212121;">{{ $products->count() }}</strong> sản phẩm
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:13px;color:#9e9e9e;">Sắp xếp theo:</span>
                    <select 
                    name="sort"
                    onchange="this.form.submit()"
                    form="filterForm"
                    style="height:36px;border:1.5px solid #e0e0e0;border-radius:8px;padding:0 12px;font-size:13px;
                                   font-family:inherit;outline:none;background:white;color:#212121;">
                        <option value="">Mặc định</option>
                        <option value="latest"
                        {{ request('sort')=='latest' ? 'selected' : '' }}>
                        Mới nhất
                        </option>
            
                        <option value="price_asc"
                            {{ request('sort')=='price_asc' ? 'selected' : '' }}>
                            Giá tăng dần
                        </option>
            
                        <option value="price_desc"
                            {{ request('sort')=='price_desc' ? 'selected' : '' }}>
                            Giá giảm dần
                        </option>
                    </select>
                </div>
            </div>

            <div class="row g-3">
                @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <a href="{{ route('products.detail', $product) }}" class="pc-img-wrap d-block">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     onerror="this.parentElement.innerHTML='<div class=\'no-img\'><i class=\'fas fa-image\'></i></div>'">
                            @else
                                <div class="no-img"><i class="fas fa-image"></i></div>
                            @endif

                            @if($loop->index < 4)
                                <span class="pc-badge new">Mới</span>
                            @endif
                            @if($product->quantity <= 0)
                                <span class="pc-badge sale" style="left:auto;right:10px;">Hết</span>
                            @endif

                            @auth
                            <form class="ajax-add-cart" action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="pc-add-overlay"
                                    {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                            @endauth
                        </a>
                        <div class="pc-body">
                            <div class="pc-origin">
                                @if($product->category) {{ $product->category->name }} @else Thực phẩm @endif
                            </div>
                            <a href="{{ route('products.detail', $product) }}" class="pc-name">{{ $product->name }}</a>
                            <div class="pc-price-row">
                                <span class="pc-price">{{ number_format($product->price) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h5>Không có sản phẩm nào</h5>
                        <a href="{{ route('home') }}" class="btn-green mt-3 d-inline-flex">Về trang chủ</a>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
            <div class="pagination-wrap">
                @if($products->onFirstPage())
                    <span class="pg-btn" style="opacity:.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="pg-btn"><i class="fas fa-chevron-left"></i></a>
                @endif

                @for($p=1;$p<=$products->lastPage();$p++)
                    @if($p==$products->currentPage())
                        <span class="pg-btn active">{{ $p }}</span>
                    @elseif($p==1||$p==$products->lastPage()||abs($p-$products->currentPage())<=1)
                        <a href="{{ $products->url($p) }}" class="pg-btn">{{ $p }}</a>
                    @elseif(abs($p-$products->currentPage())==2)
                        <span class="pg-btn dots">…</span>
                    @endif
                @endfor

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="pg-btn"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="pg-btn" style="opacity:.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
</div>

{{-- AJAX ADD TO CART --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.ajax-add-cart').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const btn = this.querySelector('button');
            btn.disabled = true;
            fetch(this.action, {
                method:'POST',
                headers:{
                    'X-Requested-With':'XMLHttpRequest',
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept':'application/json'
                },
                body: new FormData(this)
            })
            .then(r=>r.json())
            .then(data=>{
                if(data.success){
                    Swal.fire({toast:true,position:'top-end',icon:'success',title:data.message,
                        showConfirmButton:false,timer:2000,timerProgressBar:true});
                    const badge=document.querySelector('.cart-badge');
                    if(badge) badge.innerText=data.cartCount;
                } else {
                    Swal.fire({toast:true,position:'top-end',icon:'error',
                        title:data.message||'Có lỗi xảy ra',
                        showConfirmButton:false,timer:2000,timerProgressBar:true});
                    if(data.redirect) setTimeout(()=>window.location.href=data.redirect,1000);
                }
            })
            .catch(()=>{
                Swal.fire({toast:true,position:'top-end',icon:'error',title:'Không thể thêm sản phẩm',
                    showConfirmButton:false,timer:2000,timerProgressBar:true});
            })
            .finally(()=>btn.disabled=false);
        });
    });
});
</script>
@endsection