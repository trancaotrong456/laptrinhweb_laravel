@extends('layout')
@section('title', 'Tin tức & Khuyến mãi')

@section('content')

@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success mb-0">{{ session('success') }}</div>
</div>
@endif

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 14px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tin tức & Khuyến mãi</li>
        </ol>
    </nav>

    <!-- Admin Bar & Filters -->
    @if($isAdmin)
        <!-- Admin Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded" style="background-color: #e3f2fd; border: 1px solid #bbdefb;">
            <div class="text-primary fw-medium">
                <i class="fas fa-shield-alt me-2"></i>Khu vực quản trị
            </div>
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i>Thêm khuyến mãi
            </a>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('posts.index') }}" class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="q" class="form-control" placeholder="Nhập tiêu đề hoặc nội dung"
                            value="{{ $filters['q'] ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Vị trí</label>
                        <select name="type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" {{ ($filters['type'] ?? '') === '1' ? 'selected' : '' }}>Banner</option>
                            <option value="0" {{ ($filters['type'] ?? '') === '0' ? 'selected' : '' }}>Thẻ nhỏ</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Sắp xếp</label>
                        <select name="sort" class="form-select">
                            <option value="priority"
                                {{ ($filters['sort'] ?? 'priority') === 'priority' ? 'selected' : '' }}>Ưu tiên</option>
                            <option value="latest" {{ ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' }}>Mới nhất
                            </option>
                            <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Cũ nhất
                            </option>
                            <option value="title" {{ ($filters['sort'] ?? '') === 'title' ? 'selected' : '' }}>Tiêu đề A-Z
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger flex-fill">
                            <i class="fas fa-search me-1"></i>Lọc
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    @endif

    <!-- Hero Banner -->
    <div class="p-5 text-center mb-5 rounded" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
        <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold" style="background-color: #ff9800; color: #fff; font-size: 13px;">
            <i class="fas fa-fire me-1"></i> SĂN SALE CỰC HOT
        </span>
        <h2 class="fw-bold mb-3" style="color: #2e7d32; font-size: 32px;">Siêu ưu đãi thực phẩm sạch<br>Tiết kiệm đến 40%</h2>
        <p class="text-muted mb-4" style="max-width: 600px; margin: 0 auto; font-size: 15px; color: #4caf50 !important;">
            Đừng bỏ lỡ các chương trình khuyến mãi hấp dẫn hằng tuần. Mua sắm thông minh, tiết kiệm thả ga với hàng nghìn sản phẩm tươi ngon mỗi ngày.
        </p>
        <a href="#special-offers" class="btn btn-success px-4 py-2" style="background-color: #2e7d32; border-color: #2e7d32; border-radius: 8px;">
            Xem ưu đãi ngay <i class="fas fa-arrow-down ms-1"></i>
        </a>
    </div>

    <!-- Special Offers Section -->
    <h4 id="special-offers" class="fw-bold mb-4" style="font-size: 20px;">
        <span style="color: #d32f2f;">|</span> 🎁 Ưu Đãi Đặc Biệt
    </h4>

    @if($sliderPosts->isEmpty() && $smallPosts->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-3">
                <div class="d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #f5f5f5; border-radius: 50%; color: #9e9e9e;">
                    <i class="fas fa-tags" style="font-size: 32px; transform: rotate(-45deg);"></i>
                </div>
            </div>
            <h5 class="fw-bold text-dark mb-2">Chưa có khuyến mãi</h5>
            <p class="text-muted" style="font-size: 14px;">Chương trình khuyến mãi sẽ sớm được cập nhật. Vui lòng quay lại sau!</p>
        </div>
    @else
        <!-- Carousel for Banners (if any) -->
        @if($sliderPosts->isNotEmpty())
        <div id="promoCarousel" class="carousel slide mb-5 shadow-sm rounded" data-bs-ride="carousel"
            data-bs-interval="3000">
            <div class="carousel-inner rounded">
                @foreach($sliderPosts as $index => $post)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/1200x450?text=Banner' }}"
                        class="d-block w-100" alt="{{ $post->title }}" style="height: 450px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block rounded p-3"
                        style="background: rgba(0,0,0,0.55); backdrop-filter: blur(2px); border-radius: 12px; bottom: 24px; left: 8%; right: 8%; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">
                        @if($isAdmin)
                        <div class="mb-2 d-flex justify-content-center gap-2">
                            <span class="badge bg-light text-dark">{{ $statuses[$post->status] ?? $post->status }}</span>
                            @if($post->published_at)
                            <span class="badge bg-info text-dark">Đăng lúc {{ $post->published_at->format('d/m/Y H:i') }}</span>
                            @endif
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Sửa
                            </a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Xóa bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>Xóa
                                </button>
                            </form>
                        </div>
                        @endif
                        <h4 style="color:#fff; font-weight:700; text-shadow: 0 2px 6px rgba(0,0,0,0.7);">{{ $post->title }}</h4>
                        <p style="color:rgba(255,255,255,0.9); font-size:14px; margin-bottom:12px;">{{ \Illuminate\Support\Str::limit($post->content, 80) }}</p>
                        <a href="{{ route('posts.show', $post->id) }}"
                            class="btn btn-success btn-sm rounded-pill px-4 mt-1">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @if($sliderPosts->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            @endif
        </div>
        @endif

        <!-- Grid for Small Posts (if any) -->
        @if($smallPosts->isNotEmpty())
        <div class="row g-4 mb-4">
            @foreach($smallPosts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 position-relative" style="border-radius: 12px; overflow: hidden; {{ $post->is_pinned ? 'background-color: #fffde7; border: 1px solid #fff59d !important;' : '' }}">
                    @if($post->is_pinned)
                    <div class="position-absolute top-0 end-0 m-2 z-index-1">
                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 shadow-sm" style="font-size:12px;">
                            <i class="fas fa-thumbtack me-1"></i>Đang ghim
                        </span>
                    </div>
                    @endif
                    <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/400x300?text=Post' }}"
                        class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <h5 class="card-title mb-0 fw-bold">{{ $post->title }}</h5>
                            @if($isAdmin)
                            <span class="badge {{ $post->isVisible() ? 'bg-success' : 'bg-secondary' }}">
                                {{ $statuses[$post->status] ?? $post->status }}
                            </span>
                            @endif
                        </div>
                        
                        @if($isAdmin && $post->published_at)
                        <p class="small text-muted mb-2"><i class="far fa-clock me-1"></i>Lịch đăng: {{ $post->published_at->format('d/m/Y H:i') }}</p>
                        @endif
                        
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($post->content, 90) }}</p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-outline-success btn-sm w-100 mb-2" style="border-radius: 8px;">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                            @if($isAdmin)
                            <div class="d-flex gap-2">
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm flex-fill" style="border-radius: 8px;">
                                    <i class="fas fa-edit me-1"></i>Sửa
                                </a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="flex-fill m-0"
                                    onsubmit="return confirm('Xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm w-100" style="border-radius: 8px;">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $smallPosts->links() }}
        </div>
        @endif
    @endif

    <!-- Features -->
    <div class="bg-white rounded p-4 p-md-5 shadow-sm mt-5 mb-4" style="border-radius: 16px !important;">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background-color: #e8f5e9; color: #2e7d32; border-radius: 20px;">
                    <i class="fas fa-shield-alt" style="font-size: 24px;"></i>
                </div>
                <h6 class="fw-bold mb-1">100%</h6>
                <div class="text-muted small">Sản phẩm an toàn</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background-color: #fff3e0; color: #f57c00; border-radius: 20px;">
                    <i class="fas fa-truck-fast" style="font-size: 24px;"></i>
                </div>
                <h6 class="fw-bold mb-1">2H</h6>
                <div class="text-muted small">Giao hàng siêu tốc</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background-color: #e3f2fd; color: #1976d2; border-radius: 20px;">
                    <i class="fas fa-sync-alt" style="font-size: 24px;"></i>
                </div>
                <h6 class="fw-bold mb-1">48H</h6>
                <div class="text-muted small">Đổi trả miễn phí</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background-color: #fce4ec; color: #c2185b; border-radius: 20px;">
                    <i class="fas fa-award" style="font-size: 24px;"></i>
                </div>
                <h6 class="fw-bold mb-1">Top 1</h6>
                <div class="text-muted small">Siêu thị uy tín</div>
            </div>
        </div>
    </div>
</div>

@endsection
