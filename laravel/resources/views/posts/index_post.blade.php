@extends('layout')

@section('content')
<<<<<<< Updated upstream
<div class="container mt-4">
=======
<!-- Admin Action Bar -->
@if(Auth::check() && Auth::user()->role == 1)
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted"><i class="fas fa-shield-alt me-2 text-primary"></i>Khu vực quản trị</span>
            <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-2"></i>Thêm khuyến mãi mới
            </a>
        </div>
    </div>
</section>
@endif

<!-- Hero Section - Fullwidth Banner with Parallax -->
<section class="promo-hero position-relative overflow-hidden mb-5"
    style="min-height: 60vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-8 text-white text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-4 animate-fade-in-up">🔥<br>SIÊU KHUYẾN MÃI</h1>
                <p class="lead mb-4 opacity-90 lh-lg">Tiết kiệm đến 70% - Săn sale cực đã cùng Siêu thị Mini!</p>
                <a href="#deals"
                    class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-semibold shadow-lg animate-pulse">
                    <i class="fas fa-fire me-2"></i> Xem ngay ưu đãi
                </a>
            </div>
        </div>
    </div>
</section>
>>>>>>> Stashed changes

    {{-- ================= PHẦN 1: BANNER LỚN TỰ ĐỘNG LƯỚT ================= --}}
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner rounded shadow-sm">
            @foreach($sliderPosts as $index => $post)
            {{-- Thằng đầu tiên phải có class 'active' thì nó mới hiện --}}
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

<<<<<<< Updated upstream
                {{-- Hiển thị ảnh: Nếu có ảnh thì lấy ảnh, không có thì lấy ảnh mặc định --}}
                <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/1200x450?text=Banner+Khuyen+Mai' }}"
                    class="d-block w-100" alt="{{ $post->title }}" style="height: 450px; object-fit: cover;">

                {{-- Khung đen mờ chứa Tiêu đề đè lên ảnh --}}
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    @if(Auth::check() && Auth::user()->role == 1)
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning w-auto mb-2 px-4">
                        ⚙️ Chỉnh sửa (Admin)
=======
        <div id="bannerCarousel" class="carousel slide shadow-lg rounded-5 overflow-hidden" data-bs-ride="carousel"
            data-bs-interval="4000">
            <div class="carousel-inner">
                @forelse($sliderPosts as $index => $post)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="row g-0 h-100 align-items-stretch">
                        <div class="col-md-8">
                            <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=500&fit=crop' }}"
                                class="d-block w-100 rounded-start h-100" style="object-fit: cover;"
                                alt="{{ $post->title }}">
                        </div>
                        <div class="col-md-4 bg-white bg-opacity-90 d-flex align-items-center p-5 glass-card">
                            @if(Auth::check() && Auth::user()->role == 1)
                            <div class="position-absolute top-3 end-3 d-flex gap-2">
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa khuyến mãi này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                            @endif
                            <div>
                                <h3 class="fw-bold mb-3 text-dark lh-1">{{ $post->title }}</h3>
                                <p class="text-muted mb-4 lh-lg">{{ Str::limit($post->content, 150) }}</p>
                                <div class="d-flex gap-3">
                                    <span class="badge bg-gradient-danger px-3 py-2 fw-semibold">
                                        <i class="fas fa-bolt me-1"></i>Flash Sale
                                    </span>
                                    <span class="badge bg-gradient-success px-3 py-2 fw-semibold">
                                        <i class="fas fa-clock me-1"></i>{{ $post->priority }}h
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                        <div class="text-center">
                            <i class="fas fa-gift fa-5x mb-4 opacity-50"></i>
                            <h3>Chưa có khuyến mãi</h3>
                            <p class="lead">Admin tạo ưu đãi đầu tiên nào! ✨</p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-white rounded-circle shadow-sm" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-white rounded-circle shadow-sm" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>

<!-- Deals Grid - Premium Cards -->
<section id="deals" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-3">🎁 Ưu Đãi Đặc Biệt</h2>
                <p class="lead text-muted mb-0">Sản phẩm hot nhất - Giá sốc nhất thị trường!</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($smallPosts as $post)
            <div class="col-lg-4 col-md-6">
                <div
                    class="deal-card h-100 shadow-lg rounded-4 overflow-hidden position-relative hover-lift animate-on-scroll">
                    <!-- Priority Badge -->
                    @if($post->priority > 5)
                    <div class="priority-badge top-0 start-0 m-3 position-absolute z-3">
                        <span class="badge bg-gradient-warning text-dark fw-bold px-3 py-2 fs-6 shadow">
                            <i class="fas fa-crown me-1"></i>TOP {{ $post->priority }}
                        </span>
                    </div>
                    @endif

                    <!-- Image with overlay -->
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://images.unsplash.com/photo-1580928752753-76bd92c0b9a9?w=500&h=350&fit=crop' }}"
                            class="card-img-top w-100"
                            style="height: 280px; object-fit: cover; transition: transform .5s ease;"
                            alt="{{ $post->title }}">
                        <div
                            class="image-overlay position-absolute bottom-0 start-0 w-100 bg-gradient-to-t from-black/80 to-transparent p-4">
                            <h4 class="text-white mb-2 fw-bold">{{ Str::limit($post->title, 40) }}</h4>
                            <p class="text-white-75 mb-3 small">{{ Str::limit($post->content, 80) }}</p>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 pt-0">
                        @if(Auth::check() && Auth::user()->role == 1)
                        <div class="mb-3 d-flex gap-2">
                            <a href="{{ route('posts.edit', $post->id) }}"
                                class="btn btn-sm btn-outline-warning w-100 rounded-pill">
                                <i class="fas fa-edit me-1"></i>Sửa
                            </a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="w-100"
                                onsubmit="return confirm('Bạn có chắc muốn xóa khuyến mãi này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-pill">
                                    <i class="fas fa-trash-alt me-1"></i>Xóa
                                </button>
                            </form>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span
                                class="badge bg-gradient-{{ $post->priority > 3 ? 'danger' : 'success' }} px-3 py-2 fw-semibold">
                                <i class="fas fa-star me-1"></i>{{ $post->priority }}*
                            </span>
                            <small class="text-muted">{{ now()->diffInHours($post->created_at) }}h trước</small>
                        </div>

                        <div class="action-buttons d-flex gap-2">
                            <a href="#" class="btn btn-success btn-lg w-100 rounded-pill fw-semibold shadow-sm">
                                <i class="fas fa-shopping-cart me-2"></i>Mua ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-gift fa-8x text-muted mb-4 opacity-50"></i>
                <h3 class="text-muted mb-4">Chưa có deal nào</h3>
                <p class="lead text-muted mb-0">@if(Auth::user()->role == 1)
                    <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-plus me-2"></i>Tạo ưu đãi đầu tiên
>>>>>>> Stashed changes
                    </a>
                    @endif
                    <h3>{{ $post->title }}</h3>
                    <p>{{ Str::limit($post->content, 60) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Hai nút bấm qua lại --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>


    {{-- ================= PHẦN 2: KHUYẾN MÃI & QUẢNG CÁO NHỎ ================= --}}
    <h3 class="mt-5 mb-4 border-bottom pb-2">🔥 Ưu Đãi Khác Dành Cho Bạn</h3>

    <div class="row">
        @foreach($smallPosts as $post)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm transition-hover">

                {{-- Ảnh bài viết nhỏ: Có ảnh thì lấy, không có thì xài ảnh mặc định --}}
                <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/400x300?text=Hinh+Minh+Hoa' }}"
                    class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $post->title }}</h5>

                    {{-- Dùng Str::limit để cắt ngắn chữ, tránh làm vỡ khung --}}
                    <p class="card-text text-muted">{{ Str::limit($post->content, 80) }}</p>

                    {{-- Nút xem chi tiết đẩy xuống đáy card --}}
                    <div class="mt-auto">
                        <a href="#" class="btn btn-outline-danger btn-sm w-100 mb-2">Xem ngay</a>

                        {{-- KIỂM TRA: Nếu là Admin (Role = 1) thì mới hiện nút Sửa --}}
                        @if(Auth::check() && Auth::user()->role == 1)
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning w-100">
                            ⚙️ Chỉnh sửa (Admin)
                        </a>
                        @endif
    {{-- ================= BANNER ================= --}}
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow-sm">

            @forelse($sliderPosts as $index => $post)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

                <img src="{{ $post->image ? asset('images/'.$post->image) : 'https://via.placeholder.com/1200x450' }}"
                    class="d-block w-100" style="height: 450px; object-fit: cover;">

                <div class="carousel-caption bg-dark bg-opacity-50 rounded p-3">

                    {{-- ADMIN ACTION --}}
                    @if(Auth::check() && Auth::user()->role == 1)
                    <div class="mb-2">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                            ✏️ Sửa
                        </a>

                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Xóa thật không?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                🗑️ Xóa
                            </button>
                        </form>
                    </div>
                    @endif

                    <h4>{{ $post->title }}</h4>
                    <p>{{ Str::limit($post->content, 60) }}</p>
                </div>

            </div>
            @empty
            <p class="text-center mt-3">Chưa có banner</p>
            @endforelse

        </div>

        <button class="carousel-control-prev" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    {{-- BUTTON ADD --}}
    @if(Auth::check() && Auth::user()->role == 1)
    <div class="text-end mt-3">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            ➕ Thêm khuyến mãi
        </a>
    </div>
    @endif

    {{-- ================= CARD ================= --}}
    <h3 class="mt-5 mb-4">🔥 Ưu đãi khác</h3>

    <div class="row">
        @forelse($smallPosts as $post)
        <div class="col-md-4 mb-4">

            <div class="card h-100 shadow-sm">

                <img src="{{ $post->image ? asset('images/'.$post->image) : 'https://via.placeholder.com/400x300' }}"
                    class="card-img-top" style="height:200px;object-fit:cover;">

                <div class="card-body d-flex flex-column">
                    <h5>{{ $post->title }}</h5>
                    <p class="text-muted">
                        {{ Str::limit($post->content, 80) }}
                    </p>

                    <div class="mt-auto">

                        <a href="#" class="btn btn-outline-danger btn-sm w-100 mb-2">
                            Xem ngay
                        </a>

                        {{-- ADMIN --}}
                        @if(Auth::check() && Auth::user()->role == 1)

                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm w-100 mb-2">
                            ✏️ Sửa
                        </a>

                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                            onsubmit="return confirm('Xóa thật không?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm w-100">
                                🗑️ Xóa
                            </button>
                        </form>

                        @endif

                    </div>
                </div>

            </div>

        </div>
<<<<<<< Updated upstream
        @endforeach
=======
        @empty
        <p>Chưa có khuyến mãi</p>
        @endforelse
>>>>>>> Stashed changes
    </div>

</div>
@endsection