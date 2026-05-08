@extends('layout')

@section('content')

{{-- ===== ADMIN BAR ===== --}}
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

<div class="container mt-4">

    {{-- ===== CAROUSEL (slider posts - type=1) ===== --}}
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

    {{-- ===== CARD GRID (small posts - type=0) ===== --}}
    <h3 class="mb-4 border-bottom pb-2">🔥 Ưu Đãi Khác Dành Cho Bạn</h3>

    <div class="row">
        @forelse($smallPosts as $post)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
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
            <p class="text-muted">Chưa có khuyến mãi nào</p>
        </div>
        @endforelse
    </div>

</div>

@endsection