@extends('layout')

@section('title', '🔥 Khuyến mãi Hot - Siêu thị Mini')

@section('content')
@if(Auth::check() && Auth::user()->role == 1)
<section class="py-3 bg-light border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="text-muted"><i class="fas fa-shield-alt me-2 text-primary"></i>Khu vực quản trị</span>
        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Thêm khuyến mãi mới
        </a>
    </div>
</section>
@endif

<section class="promo-hero position-relative overflow-hidden mb-5"
    style="min-height: 60vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-8 text-white text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-4">🔥<br>SIÊU KHUYẾN MÃI</h1>
                <p class="lead mb-4 opacity-90">Tiết kiệm đến 70% - Săn sale cực đã cùng Siêu thị Mini!</p>
                <a href="#deals" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-semibold shadow-lg">
                    <i class="fas fa-fire me-2"></i>Xem ngay ưu đãi
                </a>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div id="promoCarousel" class="carousel slide mb-5 shadow-sm rounded" data-bs-ride="carousel"
        data-bs-interval="3000">
        <div class="carousel-inner rounded">
            @forelse($sliderPosts as $index => $post)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/1200x450?text=Banner' }}"
                    class="d-block w-100" alt="{{ $post->title }}" style="height: 450px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    @if(Auth::check() && Auth::user()->role == 1)
                    <div class="mb-2 d-flex justify-content-center gap-2">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">✏️ Sửa</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Xóa thật không?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑️ Xóa</button>
                        </form>
                    </div>
                    @endif
                    <h4>{{ $post->title }}</h4>
                    <p>{{ Str::limit($post->content, 60) }}</p>
                </div>
            </div>
            @empty
            <div class="carousel-item active">
                <div class="d-flex justify-content-center align-items-center bg-light" style="height:450px;">
                    <p class="text-muted">Chưa có banner nào</p>
                </div>
            </div>
            @endforelse
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <section id="deals" class="py-4">
        <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <h3 class="mb-0">🎁 Ưu Đãi Đặc Biệt</h3>
            </div>

            <form method="GET" action="{{ route('posts.index') }}" class="d-flex gap-2 align-items-center search"
                id="postSearchForm">
                <div class="category-search-wrap position-relative" style="min-width: 320px;">
                    <input type="text" name="q" class="form-control" placeholder="Tìm bài viết..."
                        value="{{ $filters['q'] ?? '' }}" autocomplete="off" id="postSearchInput" style="width:100%;" />
                    <div id="postSearchSuggestions" class="category-suggestions d-none"></div>
                </div>

                <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'priority' }}">
                <input type="hidden" name="type" value="{{ $filters['type'] ?? '' }}">
                <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
                <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="row g-4">
            @forelse($smallPosts as $post)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-lg rounded-4 overflow-hidden hover-lift">
                    @if($post->priority > 5)
                    <div class="position-absolute top-0 start-0 m-3 z-3">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                            <i class="fas fa-crown me-1"></i>TOP {{ $post->priority }}
                        </span>
                    </div>
                    @endif
                    <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/400x300' }}"
                        class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($post->content, 80) }}</p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-danger btn-sm w-100 mb-2">Xem ngay</a>
                            @if(Auth::check() && Auth::user()->role == 1)
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm w-100 mb-2">✏️
                                Sửa</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                onsubmit="return confirm('Xóa thật không?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm w-100">🗑️ Xóa</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-gift fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">Chưa có deal nào</h3>
            </div>
            @endforelse
        </div>
    </section>

    <section class="py-5">
        <div class="row text-center g-4">
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold text-primary">10K+</h3>
                <p class="text-muted">Khách hàng</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-fire fa-3x text-danger mb-3"></i>
                <h3 class="fw-bold text-danger">{{ $sliderPosts->count() + $smallPosts->count() }}</h3>
                <p class="text-muted">Ưu đãi hiện có</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-shipping-fast fa-3x text-success mb-3"></i>
                <h3 class="fw-bold text-success">2h</h3>
                <p class="text-muted">Giao hàng</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="fas fa-award fa-3x text-warning mb-3"></i>
                <h3 class="fw-bold text-warning">5⭐</h3>
                <p class="text-muted">Đánh giá</p>
            </div>
        </div>
    </section>

</div>

<style>
.hover-lift {
    transition: transform .3s ease, box-shadow .3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, .15) !important;
}
</style>

<script>
(function() {
    const input = document.getElementById('postSearchInput');
    const suggestions = document.getElementById('postSearchSuggestions');
    if (!input || !suggestions) return;

    let t;

    const escapeHtml = (text) => String(text ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '<')
        .replaceAll('>', '>')
        .replaceAll('"', '"')
        .replaceAll("'", '&#039;');

    input.addEventListener('input', function() {
        clearTimeout(t);
        const q = this.value.trim();

        suggestions.innerHTML = '';
        suggestions.classList.add('d-none');

        if (q.length < 1) return;

        t = setTimeout(() => {
            fetch(`{{ route('posts.index') }}?q=${encodeURIComponent(q)}&suggest=1`)
                .then(res => res.json())
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) return;

                    data.forEach(item => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className =
                            'btn btn-light border w-100 text-start px-3 py-2 mb-2';
                        btn.innerHTML = `
                            <div class="fw-bold">${escapeHtml(item.title)}</div>
                            <div class="text-muted small">${escapeHtml(item.content_short)}</div>
                        `;
                        btn.addEventListener('click', () => {
                            input.value = item.title;
                            suggestions.innerHTML = '';
                            suggestions.classList.add('d-none');
                            input.form.submit();
                        });
                        suggestions.appendChild(btn);
                    });

                    suggestions.classList.remove('d-none');
                })
                .catch(() => {
                    // bỏ qua
                });
        }, 250);
    });

    document.addEventListener('click', function(e) {
        if (e.target !== input && !suggestions.contains(e.target)) {
            suggestions.classList.add('d-none');
        }
    });
})();
</script>

@endsection