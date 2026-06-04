@extends('layout')
@section('title', 'Trang chủ - Siêu thị trực tuyến')
@section('content')
{{-- SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- SESSION ALERTS --}}
@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: '{{ session("success") }}',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
});
</script>
@endif
@if(session('error'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: '{{ session("error") }}',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
});
</script>
@endif
<div class="container">
    {{-- ═══════════ HERO BANNER (SLIDER KHUYẾN MÃI) ═══════════ --}}
    @if(isset($banners) && $banners->count() > 0)
    <div id="homeBannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel"
        style="border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.08);">
        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ $banner->image ? asset('images/' . $banner->image) : 'https://via.placeholder.com/1200x400?text=Banner' }}"
                    class="d-block w-100" alt="{{ $banner->title }}" style="height:400px;object-fit:cover;">
                <div class="carousel-caption d-none d-md-block"
                    style="border-radius:12px;padding:20px;bottom:30px;left:5%;right:5%;text-align:left;text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">
                    <div
                        style="display:inline-block;background:#ffecb3;color:#f57f17;font-size:12px;font-weight:800;padding:6px 12px;border-radius:99px;margin-bottom:12px;text-shadow:none;">
                        <i class="fas fa-fire"></i> KHUYẾN MÃI
                    </div>
                    <h2 style="font-size:28px;font-weight:800;margin-bottom:12px;color:white;">{{ $banner->title }}</h2>
                    <p style="font-size:15px;margin-bottom:16px;opacity:1;max-width:600px;color:white;">
                        {{ Str::limit($banner->content, 100) }}</p>
                    <a href="{{ route('posts.index') }}" class="btn-green"
                        style="display:inline-block;text-shadow:none;">
                        Xem chi tiết <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="prev"
            style="width:50px;">
            <div
                style="width:40px;height:40px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#2e7d32;box-shadow:0 4px 10px rgba(0,0,0,.1);">
                <i class="fas fa-chevron-left"></i>
            </div>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="next"
            style="width:50px;">
            <div
                style="width:40px;height:40px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#2e7d32;box-shadow:0 4px 10px rgba(0,0,0,.1);">
                <i class="fas fa-chevron-right"></i>
            </div>
        </button>
    </div>
    @else
    <div class="hero-banner mb-4">
        <div style="position:relative;z-index:1;">
            <div class="hb-tag">
                <i class="fas fa-fire"></i> Khuyến mãi đặc biệt
            </div>
            <h1>Siêu ưu đãi thực phẩm<br>sạch</h1>
            <p>Giảm đến 30% cho các sản phẩm Organic đạt chuẩn quốc gia.<br>
                Thỏa thích lựa chọn hàng nghìn mặt hàng tươi sạch.</p>
            <a href="{{ route('products.all') }}" class="hb-btn">
                Mua ngay <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    @endif

    {{-- ═══════════ DANH MỤC NỔI BẬT ═══════════ --}}
    <div class="section-title mb-3">
        <h2>Danh mục nổi bật</h2>
    </div>
    <div class="row g-3 mb-4">
        @if(isset($categories) && $categories->count() > 0)
            @php
            // Mapping category names to icons
            $categoryIcons = [
                'Trái cây' => 'fas fa-seedling',
                'Trái Cây' => 'fas fa-seedling',
                'Rau củ' => 'fas fa-carrot',
                'Thịt & hải sản' => 'fas fa-fish',
                'Thịt & Hải sản' => 'fas fa-fish',
                'Sữa & Trứng' => 'fas fa-egg',
                'Bánh kẹo' => 'fas fa-bread-slice',
                'Bánh Kẹo' => 'fas fa-bread-slice',
                'Đồ uống' => 'fas fa-bottle-water',
            ];
            @endphp
            @foreach($categories as $cat)
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('products.all') }}" class="cat-icon-card d-flex">
                    <div class="ci-icon">
                        <i class="{{ $categoryIcons[$cat->name] ?? 'fas fa-box' }}"></i>
                    </div>
                    <div class="ci-name">{{ $cat->name }}</div>
                </a>
            </div>
            @endforeach
        @else
            {{-- Fallback to hardcoded if no categories --}}
            @php
            $cats = [
            ['icon'=>'fas fa-seedling', 'name'=>'Trái cây', 'route'=>'products.all'],
            ['icon'=>'fas fa-carrot', 'name'=>'Rau củ', 'route'=>'products.all'],
            ['icon'=>'fas fa-fish', 'name'=>'Thịt & hải sản','route'=>'products.all'],
            ['icon'=>'fas fa-egg', 'name'=>'Sữa & Trứng', 'route'=>'products.all'],
            ['icon'=>'fas fa-bread-slice', 'name'=>'Bánh kẹo', 'route'=>'products.all'],
            ['icon'=>'fas fa-bottle-water', 'name'=>'Đồ uống', 'route'=>'products.all'],
            ];
            @endphp
            @foreach($cats as $cat)
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route($cat['route']) }}" class="cat-icon-card d-flex">
                    <div class="ci-icon">
                        <i class="{{ $cat['icon'] }}"></i>
                    </div>
                    <div class="ci-name">{{ $cat['name'] }}</div>
                </a>
            </div>
            @endforeach
        @endif
    </div>


    {{-- ═══════════ FLASH SALE SECTION ═══════════ --}}
    @if(isset($activeFlashSales) && $activeFlashSales->count() > 0)
    @php
        $fsEndAt = $flashSaleEndsAt ?? now()->addHours(8);
        $fsEndTimestamp = $fsEndAt->timestamp;
    @endphp
    <div class="flash-sale-section mb-5">
        {{-- Header với đồng hồ đếm ngược --}}
        <div class="fs-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="fs-title-badge">
                    <i class="fas fa-bolt"></i>
                    <span>FLASH SALE</span>
                </div>
                <div class="fs-status-badge">Đang diễn ra</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span style="font-size:14px; color:#fff; font-weight:600; opacity:.85;">Kết thúc sau:</span>
                <div class="fs-countdown">
                    <div class="fs-cd-block">
                        <span class="fs-cd-num" id="fsH">00</span>
                        <span class="fs-cd-label">Giờ</span>
                    </div>
                    <span class="fs-cd-sep">:</span>
                    <div class="fs-cd-block">
                        <span class="fs-cd-num" id="fsM">00</span>
                        <span class="fs-cd-label">Phút</span>
                    </div>
                    <span class="fs-cd-sep">:</span>
                    <div class="fs-cd-block">
                        <span class="fs-cd-num" id="fsS">00</span>
                        <span class="fs-cd-label">Giây</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sản phẩm Flash Sale --}}
        <div class="fs-products-row">
            @foreach($activeFlashSales as $fs)
            @php
                $p = $fs->product;
                if (!$p) continue;
                $pImg = null;
                if ($p->image) {
                    $pImg = str_contains($p->image, '/')
                        ? asset('storage/' . $p->image)
                        : asset('images/' . $p->image);
                }
                $discount = $p->price > 0 ? round((1 - $fs->sale_price / $p->price) * 100) : 0;
            @endphp
            <div class="fs-product-card">
                <a href="{{ route('products.detail', $p) }}" class="fs-product-img-wrap">
                    @if($discount > 0)
                    <span class="fs-discount-badge">-{{ $discount }}%</span>
                    @endif
                    @if($pImg)
                        <img src="{{ $pImg }}" alt="{{ $p->name }}" class="fs-product-img">
                    @else
                        <div class="fs-product-no-img"><i class="fas fa-box-open"></i></div>
                    @endif
                </a>
                <div class="fs-product-info">
                    <a href="{{ route('products.detail', $p) }}" class="fs-product-name">{{ $p->name }}</a>
                    <div class="fs-product-price-row">
                        <span class="fs-product-sale-price">{{ number_format($fs->sale_price) }}đ</span>
                        <span class="fs-product-original-price">{{ number_format($p->price) }}đ</span>
                    </div>
                    @auth
                    <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <input type="hidden" name="price" value="{{ $fs->sale_price }}">
                        <button type="submit" class="fs-add-to-cart"
                            {{ $p->quantity <= 0 ? 'disabled' : '' }}>
                            Thêm vào giỏ
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="fs-add-to-cart">Thêm vào giỏ</a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <style>
    .flash-sale-section {
        background: linear-gradient(135deg, #ef4444, #e11d48);
        border-radius: 20px;
        padding: 20px 24px;
        overflow: hidden;
    }
    .fs-title-badge {
        display: flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.15);
        padding: 8px 18px; border-radius: 50px;
        color: #fff; font-size: 17px; font-weight: 900;
        letter-spacing: 1px;
        backdrop-filter: blur(4px);
    }
    .fs-title-badge i { color: #fbbf24; font-size: 18px; }
    .fs-status-badge {
        background: rgba(255,255,255,.95);
        color: #ef4444; font-weight: 700; font-size: 12px;
        padding: 5px 14px; border-radius: 50px;
        border: 1.5px solid rgba(255,255,255,.8);
    }
    .fs-countdown { display: flex; align-items: center; gap: 6px; }
    .fs-cd-block {
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(4px);
        border-radius: 10px;
        padding: 6px 14px; text-align: center; min-width: 54px;
        border: 1.5px solid rgba(255,255,255,.2);
    }
    .fs-cd-num {
        display: block; font-size: 22px; font-weight: 900;
        color: #fff; line-height: 1.1;
    }
    .fs-cd-label { font-size: 10px; color: rgba(255,255,255,.8); font-weight: 600; }
    .fs-cd-sep { font-size: 24px; font-weight: 900; color: #fff; margin-bottom: 12px; }

    /* Products Row */
    .fs-products-row {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
    }
    .fs-product-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 15px rgba(0,0,0,.1);
    }
    .fs-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,.18);
    }
    .fs-product-img-wrap {
        display: block; position: relative; width: 100%;
        aspect-ratio: 1/1; overflow: hidden; background: #f8fafc;
        text-decoration: none;
    }
    .fs-product-img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .3s;
    }
    .fs-product-card:hover .fs-product-img { transform: scale(1.05); }
    .fs-product-no-img {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; color: #cbd5e1;
    }
    .fs-discount-badge {
        position: absolute; top: 8px; left: 8px; z-index: 2;
        background: #ef4444; color: #fff;
        font-size: 11px; font-weight: 800;
        padding: 3px 8px; border-radius: 50px;
    }
    .fs-product-info { padding: 12px; }
    .fs-product-name {
        display: block; font-size: 13px; font-weight: 600;
        color: #1e293b; text-decoration: none; margin-bottom: 6px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .fs-product-name:hover { color: #ef4444; }
    .fs-product-price-row { display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; margin-bottom: 10px; }
    .fs-product-sale-price { font-size: 16px; font-weight: 800; color: #ef4444; }
    .fs-product-original-price { font-size: 12px; color: #94a3b8; text-decoration: line-through; }
    .fs-add-to-cart {
        display: block; width: 100%; padding: 8px;
        background: #ef4444; color: #fff;
        border: none; border-radius: 50px;
        font-size: 12px; font-weight: 700;
        text-align: center; cursor: pointer;
        text-decoration: none; transition: all .2s;
        font-family: inherit;
    }
    .fs-add-to-cart:hover { background: #dc2626; color: #fff; }
    .fs-add-to-cart:disabled { background: #94a3b8; cursor: not-allowed; }

    @media (max-width: 768px) {
        .fs-products-row { grid-template-columns: repeat(2, 1fr); }
    }
    </style>

    <script>
    (function() {
        var endTs = {{ $fsEndTimestamp }} * 1000;
        function pad(n) { return String(n).padStart(2, '0'); }
        function tick() {
            var diff = Math.max(0, endTs - Date.now());
            var h = Math.floor(diff / 3600000);
            var m = Math.floor((diff % 3600000) / 60000);
            var s = Math.floor((diff % 60000) / 1000);
            var hEl = document.getElementById('fsH');
            var mEl = document.getElementById('fsM');
            var sEl = document.getElementById('fsS');
            if(hEl) hEl.textContent = pad(h);
            if(mEl) mEl.textContent = pad(m);
            if(sEl) sEl.textContent = pad(s);
            if(diff <= 0) clearInterval(timer);
        }
        tick();
        var timer = setInterval(tick, 1000);
    })();
    </script>
    @endif

    {{-- 🌟 ═══════════ SECTION TRƯNG BÀY MÃ GIẢM GIÁ ═══════════ 🌟 --}}
    @if(isset($coupons) && $coupons->count() > 0)
    <div class="section-title mb-3 d-flex align-items-center justify-content-between">
        <div>
            <h2>Mã giảm giá độc quyền</h2>
            <div class="text-muted style-subtext" style="font-size:13px; margin-top:2px;">Thu thập mã giảm giá để mua
                sắm tiết kiệm hơn</div>
        </div>
        @auth
        <a href="{{ route('coupons.saved') }}"
            style="font-size:14px; text-decoration:none; color:#2e7d32; font-weight:600;">
            Ví Voucher của tôi <i class="fas fa-chevron-right" style="font-size:11px;"></i>
        </a>
        @endauth
    </div>
    <div class="row g-3 mb-5">
        @foreach($coupons as $coupon)
        @php $isSaved = in_array($coupon->id, $savedCouponIds ?? []); @endphp
        <div class="col-12 col-md-6 col-lg-4">
            <div class="home-voucher-ticket d-flex {{ $isSaved ? 'already-saved' : '' }}"
                id="coupon-card-{{ $coupon->id }}">
                <div
                    class="ticket-left d-flex flex-column justify-content-center align-items-center text-center text-white">
                    <i class="fas fa-ticket-alt mb-1"></i>
                    <span class="ticket-value">
                        @if($coupon->type === 'percent')
                        {{ rtrim(rtrim(number_format($coupon->value, 1, '.', ''), '0'), '.') }}%
                        @else
                        {{ $coupon->value >= 1000 ? ($coupon->value / 1000) . 'k' : number_format($coupon->value) }}
                        @endif
                    </span>
                    <span class="ticket-type-text">GIẢM GIÁ</span>
                </div>
                <div class="ticket-body d-flex flex-column justify-content-center p-3 flex-grow-1 bg-white">
                    <div class="ticket-code-badge mb-1">Code: <strong>{{ $coupon->code }}</strong></div>
                    <div class="ticket-condition text-dark fw-semibold mb-1" style="font-size:14px;">
                        Giảm @if($coupon->type === 'percent'){{ $coupon->value }}% @else
                        {{ number_format($coupon->value) }}đ @endif
                    </div>
                    <div class="ticket-min-order text-muted" style="font-size:12px;">
                        Đơn tối thiểu:
                        {{ $coupon->min_order_value ? number_format($coupon->min_order_value).'đ' : '0đ' }}
                    </div>
                    @if($coupon->ends_at)
                    <div class="ticket-expiry text-danger mt-1" style="font-size:11px;">
                        <i class="far fa-clock"></i> HSD: {{ \Carbon\Carbon::parse($coupon->ends_at)->format('d/m/Y') }}
                    </div>
                    @endif
                </div>
                <div
                    class="ticket-right d-flex align-items-center justify-content-center bg-white border-start border-dashed">
                    @auth
                    <form class="save-coupon-form m-0"
                        action="{{ $isSaved ? route('coupons.unsave', $coupon->id) : route('coupons.save', $coupon->id) }}"
                        method="POST" data-id="{{ $coupon->id }}">
                        @csrf
                        @if($isSaved) @method('DELETE') @endif
                        <button type="submit" class="ticket-btn {{ $isSaved ? 'btn-saved' : 'btn-collect' }}">
                            {{ $isSaved ? 'Đã lưu' : 'Lưu mã' }}
                        </button>
                    </form>
                    @else
                    <button class="ticket-btn btn-collect"
                        onclick="Swal.fire({icon: 'warning', title: 'Yêu cầu đăng nhập', text: 'Vui lòng đăng nhập để lưu mã giảm giá này!', showCancelButton: true, confirmButtonText: 'Đăng nhập'}).then((r) => { if(r.isConfirmed) window.location.href='{{ route('login') }}'; })">
                        Lưu mã
                    </button>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ═══════════ SẢN PHẨM BÁN CHẠY ═══════════ --}}
    <div class="section-title">
        <h2>Sản phẩm bán chạy</h2>
        <a href="{{ route('products.all') }}">Xem tất cả <i class="fas fa-chevron-right"
                style="font-size:11px;"></i></a>
    </div>
    <div class="row g-3 mb-4">
        @forelse($products->take(10) as $product)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2-4" style="flex:0 0 auto;width:20%;">
            <div class="product-card">
                <a href="{{ route('products.detail', $product) }}" class="pc-img-wrap d-block">
                    @if($product->image)
                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                        onerror="this.parentElement.innerHTML='<div class=\'no-img\'><i class=\'fas fa-image\'></i></div>'">
                    @else
                    <div class="no-img"><i class="fas fa-image"></i></div>
                    @endif
                    @if($loop->index < 3) <span class="pc-badge new">Mới</span>
                        @endif
                        {{-- Add to cart overlay --}}
                        @auth
                        <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="pc-add-overlay" title="Thêm vào giỏ"
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
        @if($loop->iteration == 5)
    </div>
    <div class="row g-3 mb-4">
        @endif
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-store-slash"></i>
                <h5>Chưa có sản phẩm nào</h5>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ═══════════ MÓN ĂN ĐỊA PHƯƠNG / FLASH BANNER ═══════════ --}}
    <div class="card-white mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
        style="background:linear-gradient(90deg,#fff8e1,#fffde7);border-color:#fff176;">
        <div>
            <div style="font-size:12px;font-weight:700;color:#f57c00;letter-spacing:.5px;margin-bottom:4px;">
                🔥 ƯU ĐÃI TRONG TUẦN
            </div>
            <div style="font-size:17px;font-weight:800;color:#212121;margin-bottom:4px;">
                Giảm giá đến 40% các mặt hàng rau củ quả Đà Lạt
            </div>
            <div style="font-size:13px;color:#757575;">Số lượng có hạn — chỉ còn hôm nay!</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="text-align:center;background:rgba(0,0,0,.06);border-radius:8px;padding:6px 14px;">
                <div style="font-size:20px;font-weight:800;" id="cdH">08</div>
                <div style="font-size:10px;color:#9e9e9e;">giờ</div>
            </div>
            <span style="font-size:18px;font-weight:700;">:</span>
            <div style="text-align:center;background:rgba(0,0,0,.06);border-radius:8px;padding:6px 14px;">
                <div style="font-size:20px;font-weight:800;" id="cdM">00</div>
                <div style="font-size:10px;color:#9e9e9e;">phút</div>
            </div>
            <span style="font-size:18px;font-weight:700;">:</span>
            <div style="text-align:center;background:rgba(0,0,0,.06);border-radius:8px;padding:6px 14px;">
                <div style="font-size:20px;font-weight:800;" id="cdS">00</div>
                <div style="font-size:10px;color:#9e9e9e;">giây</div>
            </div>
            <a href="{{ route('products.all') }}" class="btn-green ms-2">
                Xem ngay
            </a>
        </div>
    </div>

    {{-- ═══════════ ĐĂNG KÝ NHẬN TIN ═══════════ --}}
    <div class="card-white mb-4 text-center py-5" style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
        <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
            <i class="fas fa-envelope" style="font-size:24px;color:#2e7d32;"></i>
            <h4 style="font-size:17px;font-weight:800;margin:0;">Đăng ký nhận khuyến mãi</h4>
        </div>
        <p style="color:#757575;font-size:13.5px;margin-bottom:18px;">
            Nhận ngay voucher 15% cho đơn hàng đầu tiên và cập nhật sản phẩm mới hằng tuần.
        </p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <input type="email" placeholder="Email của bạn..." style="height:42px;border:1.5px solid #c8e6c9;border-radius:8px;padding:0 16px;font-size:13.5px;
                          outline:none;width:280px;font-family:inherit;background:white;">
            <button class="btn-green">Đăng ký</button>
        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    {
        {
            --AJAX ADD TO CART--
        }
    }
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                })
                .then(async r => {
                    const data = await r.json();
                    if (!r.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        const badge = document.querySelector('.cart-badge');
                        if (badge) badge.innerText = data.cartCount;
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message || 'Có lỗi xảy ra',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        if (data.redirect) setTimeout(() => window.location.href = data
                            .redirect, 1000);
                    }
                })
                .catch(err => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: err.message || 'Không thể thêm sản phẩm',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                })
                .finally(() => btn.disabled = false);
        });
    });

    {
        {
            --🌟AJAX XỬ LÝ LƯU / HỦY LƯU VOUCHER KHÔNG LOAD TRANG--
        }
    }
    document.querySelectorAll('.save-coupon-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const currentForm = this;
            const couponId = currentForm.getAttribute('data-id');
            const btn = currentForm.querySelector('.ticket-btn');
            const card = document.getElementById(`coupon-card-${couponId}`);

            btn.disabled = true;

            fetch(currentForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: new FormData(currentForm)
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        // Kiểm tra trạng thái hiện tại là vừa Lưu hay vừa Hủy lưu
                        const isDeleteMethod = currentForm.querySelector(
                            'input[name="_method"]');

                        if (!isDeleteMethod) {
                            // Trạng thái: Vừa lưu mã thành công -> Đổi form sang trạng thái DELETE (Hủy lưu)
                            card.classList.add('already-saved');
                            btn.className = 'ticket-btn btn-saved';
                            btn.innerText = 'Đã lưu';

                            // Thay đổi action và thêm method DELETE vào form
                            currentForm.action = `{{ url('/coupons') }}/${couponId}/unsave`;
                            let methodInput = document.createElement('input');
                            methodInput.setAttribute('type', 'hidden');
                            methodInput.setAttribute('name', '_method');
                            methodInput.setAttribute('value', 'DELETE');
                            currentForm.appendChild(methodInput);
                        } else {
                            // Trạng thái: Vừa hủy lưu thành công -> Đổi form về lại POST (Lưu mã)
                            card.classList.remove('already-saved');
                            btn.className = 'ticket-btn btn-collect';
                            btn.innerText = 'Lưu mã';

                            currentForm.action = `{{ url('/coupons') }}/${couponId}/save`;
                            isDeleteMethod.remove();
                        }
                    }
                })
                .catch(error => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: error.message || 'Thao tác không thành công',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                })
                .finally(() => {
                    btn.disabled = false;
                });
        });
    });

    // Countdown
    function pad(n) {
        return String(n).padStart(2, '0');
    }
    let end = Date.now() + 8 * 3600 * 1000;

    function tick() {
        const diff = Math.max(0, end - Date.now());
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        const hEl = document.getElementById('cdH');
        const mEl = document.getElementById('cdM');
        const sEl = document.getElementById('cdS');
        if (hEl) hEl.textContent = pad(h);
        if (mEl) mEl.textContent = pad(m);
        if (sEl) sEl.textContent = pad(s);
    }
    setInterval(tick, 1000);
    tick();
});
</script>

@push('styles')
<style>
/* 🌟 CSS DESIGN RIÊNG CHO TẤM VOUCHER */
.home-voucher-ticket {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.home-voucher-ticket:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.ticket-left {
    background: linear-gradient(135deg, #43a047, #2e7d32);
    width: 90px;
    min-width: 90px;
    padding: 10px;
}

.ticket-value {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
}

.ticket-type-text {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.5px;
    opacity: 0.9;
}

.ticket-code-badge {
    display: inline-block;
    background: #e8f5e9;
    color: #2e7d32;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11.5px;
    width: fit-content;
}

.border-dashed {
    border-style: dashed !important;
}

.ticket-right {
    width: 85px;
    min-width: 85px;
    padding: 5px;
}

.ticket-btn {
    border: none;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 20px;
    transition: all 0.2s ease;
    width: 75px;
}

.btn-collect {
    background-color: #2e7d32;
    color: white;
}

.btn-collect:hover {
    background-color: #1b5e20;
}

/* Style khi voucher đã được lưu trước đó */
.home-voucher-ticket.already-saved .ticket-left {
    background: linear-gradient(135deg, #9e9e9e, #757575) !important;
}

.home-voucher-ticket.already-saved .ticket-code-badge {
    background: #f5f5f5;
    color: #616161;
}

.btn-saved {
    background-color: #e0e0e0;
    color: #616161;
}

.btn-saved:hover {
    background-color: #d32f2f;
    color: white;
}

.btn-saved:hover::after {
    content: none;
}

@media(max-width:991px) {
    [style*="width:20%"] {
        width: 50% !important;
    }
}

@media(max-width:575px) {
    [style*="width:20%"] {
        width: 50% !important;
    }
}
</style>
@endpush
@endsection