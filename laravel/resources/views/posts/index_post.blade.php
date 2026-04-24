@extends('layout')

@section('title', '🔥 Khuyến mãi Hot - Tiết kiệm lớn tại Siêu thị Mini')

@section('content')
<<<<<<< HEAD
=======
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

>>>>>>> feature/posts
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
<<<<<<< HEAD

<!-- Slider Banners - Glassmorphism Cards -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-3">Ưu đãi Nổi Bật</h2>
                <p class="lead text-muted">Những ưu đãi đặc biệt chỉ có trong tuần này!</p>
=======
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
>>>>>>> feature/posts
            </div>
        </div>

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
                            <div class="position-absolute top-3 end-3">
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
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
                        <div class="mb-3">
                            <a href="{{ route('posts.edit', $post->id) }}"
                                class="btn btn-sm btn-outline-warning w-100 rounded-pill">
                                <i class="fas fa-edit me-1"></i>Sửa nhanh (Admin)
                            </a>
                        </div>
                        @endif
<<<<<<< HEAD

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
=======
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

>>>>>>> feature/posts
                    </div>
                </div>

            </div>
<<<<<<< HEAD
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-gift fa-8x text-muted mb-4 opacity-50"></i>
                <h3 class="text-muted mb-4">Chưa có deal nào</h3>
                <p class="lead text-muted mb-0">@if(Auth::user()->role == 1)
                    <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-plus me-2"></i>Tạo ưu đãi đầu tiên
                    </a>
                    @endif
                </p>
            </div>
            @endforelse
        </div>
=======

        </div>
<<<<<<< Updated upstream
        @endforeach
=======
        @empty
        <p>Chưa có khuyến mãi</p>
        @endforelse
>>>>>>> Stashed changes
>>>>>>> feature/posts
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-gradient-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold text-primary">10K+</h3>
                <p class="lead text-muted mb-0">Khách hàng</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-fire fa-3x text-danger mb-3"></i>
                <h3 class="fw-bold text-danger">{{ $sliderPosts->count() + $smallPosts->count() }}</h3>
                <p class="lead text-muted mb-0">Ưu đãi hiện có</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-shipping-fast fa-3x text-success mb-3"></i>
                <h3 class="fw-bold text-success">2h</h3>
                <p class="lead text-muted mb-0">Giao hàng</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-award fa-3x text-warning mb-3"></i>
                <h3 class="fw-bold text-warning">5⭐</h3>
                <p class="lead text-muted mb-0">Đánh giá</p>
            </div>
        </div>
    </div>
</section>

<style>
.glass-card {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, .1);
}

.hover-lift {
    transition: all .4s cubic-bezier(.25, .46, .45, .94);
}

.hover-lift:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0, 0, 0, .15) !important;
}

.animate-fade-in-up {
    animation: fadeInUp 1s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, .7);
    }

    70% {
        box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
}

.bg-gradient-primary {
    background: var(--primary-gradient) !important;
}

.bg-gradient-success {
    background: var(--success-gradient) !important;
}

.bg-gradient-danger {
    background: var(--danger-gradient) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%) !important;
}

.image-overlay {
    transition: all .4s ease;
}

.hover-lift:hover .image-overlay {
    background: rgba(0, 0, 0, .9);
}

.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all .6s ease;
}

.animate-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
document.addEventListener('scroll', () => {
    document.querySelectorAll('.animate-on-scroll').forEach((el, index) => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.8) {
            setTimeout(() => el.classList.add('visible'), index * 100);
        }
    });
});
</script>
@endsection