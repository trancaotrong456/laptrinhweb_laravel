@extends('layout')
@section('title', 'Khuyến mãi - Siêu thị trực tuyến')
@section('content')
{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Tin tức & Khuyến mãi</span>
    </div>
</div>
{{-- ADMIN ACTION --}}
@if(Auth::check() && Auth::user()->role == 1)
<div class="container mt-3">
    <div
        style="background:#e3f2fd;border:1px solid #bbdefb;border-radius:10px;padding:12px 20px;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:13.5px;color:#1565c0;font-weight:600;"><i class="fas fa-shield-alt me-2"></i>Khu vực quản
            trị</span>
        <a href="{{ route('posts.create') }}" class="btn-green" style="background:#1976d2;border-color:#1976d2;">
            <i class="fas fa-plus"></i> Thêm khuyến mãi
        </a>
    </div>
</div>
@endif
<div class="container py-4">
    {{-- HERO PROMO --}}
    <div
        style="background:linear-gradient(135deg,#c8e6c9,#e8f5e9);border-radius:20px;padding:40px 24px;text-align:center;margin-bottom:40px;">
        <div
            style="display:inline-block;background:#ffecb3;color:#f57f17;font-size:12px;font-weight:800;padding:6px 16px;border-radius:99px;margin-bottom:16px;">
            <i class="fas fa-fire"></i> SĂN SALE CỰC HOT
        </div>
        <h1 style="font-size:32px;font-weight:800;color:#2e7d32;margin-bottom:16px;line-height:1.3;">
            Siêu ưu đãi thực phẩm sạch<br>Tiết kiệm đến 40%
        </h1>
        <p style="font-size:14px;color:#43a047;max-width:600px;margin:0 auto 24px;">
            Đừng bỏ lỡ các chương trình khuyến mãi hấp dẫn hằng tuần. Mua sắm thông minh, tiết kiệm thả ga với hàng
            nghìn sản phẩm tươi ngon mỗi ngày.
        </p>
        <a href="#deals" class="btn-green" style="font-size:15px;padding:12px 32px;">
            Xem ưu đãi ngay <i class="fas fa-arrow-down ms-2"></i>
        </a>
    </div>
    {{-- SLIDER --}}
    @if($sliderPosts->count() > 0)
    <div id="promoCarousel" class="carousel slide mb-5" data-bs-ride="carousel"
        style="border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.08);">
        <div class="carousel-inner">
            @foreach($sliderPosts as $index => $post)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/1200x400?text=Banner' }}"
                    class="d-block w-100" alt="{{ $post->title }}" style="height:400px;object-fit:cover;">

                <div class="carousel-caption d-none d-md-block"
                    style="border-radius:12px;padding:20px;bottom:30px;text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">
                    <h4 style="font-size:20px;font-weight:800;margin-bottom:8px;color:white;">{{ $post->title }}</h4>
                    <p style="font-size:14px;margin-bottom:0;opacity:1;color:white;">
                        {{ Str::limit($post->content, 80) }}</p>
                    @if(Auth::check() && Auth::user()->role == 1)
                    <div style="margin-top:12px;display:flex;gap:8px;justify-content:center;">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn-green"
                            style="background:#fbc02d;border-color:#fbc02d;color:#212121;padding:6px 12px;font-size:12px;">Sửa</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                            onsubmit="return confirm('Xóa?')">
                            @csrf @method('DELETE')
                            <button class="btn-green"
                                style="background:#e53935;border-color:#e53935;padding:6px 12px;font-size:12px;">Xóa</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev"
            style="width:50px;">
            <div
                style="width:40px;height:40px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#2e7d32;box-shadow:0 4px 10px rgba(0,0,0,.1);">
                <i class="fas fa-chevron-left"></i>
            </div>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next"
            style="width:50px;">
            <div
                style="width:40px;height:40px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#2e7d32;box-shadow:0 4px 10px rgba(0,0,0,.1);">
                <i class="fas fa-chevron-right"></i>
            </div>
        </button>
    </div>
    @endif
    {{-- DEALS --}}
    <div id="deals" class="section-title mb-4">
        <h2><i class="fas fa-gift" style="color:#e53935;"></i> Ưu Đãi Đặc Biệt</h2>
    </div>
    <div class="row g-4">
        @forelse($smallPosts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card-white"
                style="padding:0;overflow:hidden;height:100%;display:flex;flex-direction:column;position:relative;">
                @if($post->priority > 5)
                <div
                    style="position:absolute;top:12px;left:12px;background:#ffca28;color:#212121;font-size:11px;font-weight:800;padding:4px 10px;border-radius:6px;box-shadow:0 4px 10px rgba(0,0,0,.15);z-index:2;">
                    <i class="fas fa-crown"></i> HOT DEAL
                </div>
                @endif
                <div style="height:200px;overflow:hidden;">
                    <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/400x300' }}"
                        alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;transition:.4s;"
                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="padding:20px;flex:1;display:flex;flex-direction:column;">
                    <h5 style="font-size:16px;font-weight:800;color:#212121;margin-bottom:8px;line-height:1.4;">
                        {{ $post->title }}</h5>
                    <p style="font-size:13.5px;color:#757575;line-height:1.6;margin-bottom:16px;">
                        {{ Str::limit($post->content, 90) }}</p>
                    <div style="margin-top:auto;">
                        <a href="{{ route('products.all') }}" class="btn-green-outline"
                            style="width:100%;font-size:13px;padding:8px;">Mua sắm theo ưu đãi</a>
                        @if(Auth::check() && Auth::user()->role == 1)
                        <div style="display:flex;gap:8px;margin-top:8px;">
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn-green-outline"
                                style="flex:1;font-size:12px;padding:6px;color:#f57c00;border-color:#ffcc80;">Sửa</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="flex:1;"
                                onsubmit="return confirm('Xóa bài này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-green-outline"
                                    style="width:100%;font-size:12px;padding:6px;color:#e53935;border-color:#ef9a9a;">Xóa</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <h5>Chưa có khuyến mãi</h5>
                <p style="font-size:13.5px;">Chương trình khuyến mãi sẽ sớm được cập nhật. Vui lòng quay lại sau!
                </p>
            </div>
        </div>
        @endforelse
    </div>
    {{-- STATS --}}
    <div
        style="background:#fff;border-radius:16px;padding:32px 24px;margin-top:40px;display:flex;flex-wrap:wrap;gap:24px;justify-content:space-around;box-shadow:0 4px 20px rgba(0,0,0,.04);">
        <div style="text-align:center;">
            <div
                style="width:60px;height:60px;background:#e8f5e9;color:#2e7d32;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 12px;">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div style="font-size:20px;font-weight:800;color:#212121;margin-bottom:4px;">100%</div>
            <div style="font-size:13px;color:#757575;font-weight:600;">Sản phẩm an toàn</div>
        </div>
        <div style="text-align:center;">
            <div
                style="width:60px;height:60px;background:#fff3e0;color:#ef6c00;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 12px;">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div style="font-size:20px;font-weight:800;color:#212121;margin-bottom:4px;">2H</div>
            <div style="font-size:13px;color:#757575;font-weight:600;">Giao hàng siêu tốc</div>
        </div>
        <div style="text-align:center;">
            <div
                style="width:60px;height:60px;background:#e3f2fd;color:#1565c0;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 12px;">
                <i class="fas fa-sync"></i>
            </div>
            <div style="font-size:20px;font-weight:800;color:#212121;margin-bottom:4px;">48H</div>
            <div style="font-size:13px;color:#757575;font-weight:600;">Đổi trả miễn phí</div>
        </div>
        <div style="text-align:center;">
            <div
                style="width:60px;height:60px;background:#fce4ec;color:#c2185b;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 12px;">
                <i class="fas fa-award"></i>
            </div>
            <div style="font-size:20px;font-weight:800;color:#212121;margin-bottom:4px;">Top 1</div>
            <div style="font-size:13px;color:#757575;font-weight:600;">Siêu thị uy tín</div>
        </div>
    </div>
</div>
@endsection