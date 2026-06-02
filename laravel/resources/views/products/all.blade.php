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
            <div class="filter-panel">
                <h5><i class="fas fa-sliders-h me-2" style="color:#2e7d32;"></i>Phân loại</h5>
                <div class="filter-label"><input type="checkbox" checked> Hữu cơ (Organic)</div>
                <div class="filter-label"><input type="checkbox" checked> Nhập khẩu</div>
                <div class="filter-label"><input type="checkbox"> Nội địa (VietGAP)</div>

                <div class="filter-divider"></div>
                <h5>Khoảng giá (VNĐ)</h5>
                <div class="price-range">
                    <input type="range" min="0" max="1000000" value="1000000" id="priceRange"
                           oninput="document.getElementById('priceVal').textContent=Number(this.value).toLocaleString('vi-VN')">
                    <div class="price-vals">
                        <span>0đ</span>
                        <span id="priceVal">1.000.000đ</span>
                    </div>
                </div>

                <div class="filter-divider"></div>
                <h5>Thương hiệu</h5>
                <div class="filter-label"><input type="checkbox"> Dalat Gap</div>
                <div class="filter-label"><input type="checkbox"> VinEco</div>
                <div class="filter-label"><input type="checkbox"> Zirapit</div>

                <div class="filter-divider"></div>
                <h5>Đánh giá</h5>
                <div class="rating-filter">
                    @for($r=5;$r>=4;$r--)
                    <div class="rf-row">
                        <div class="rf-stars">
                            @for($s=1;$s<=5;$s++)
                            <i class="{{ $s<=$r ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <span style="font-size:12.5px;margin-left:4px;color:#757575;">Từ {{ $r }}.0 sao</span>
                    </div>
                    @endfor
                </div>
            </div>
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
                    <select style="height:36px;border:1.5px solid #e0e0e0;border-radius:8px;padding:0 12px;font-size:13px;
                                   font-family:inherit;outline:none;background:white;color:#212121;">
                        <option>Mới nhất</option>
                        <option>Giá tăng dần</option>
                        <option>Giá giảm dần</option>
                        <option>Đánh giá cao</option>
                    </select>
                </div>
            </div>

            <div class="row g-3">
                @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <a href="{{ route('products.detail', $product) }}" class="pc-img-wrap d-block">
                            @if($product->image)
                                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
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