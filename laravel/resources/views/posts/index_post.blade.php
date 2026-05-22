@extends('layout')

@section('content')

@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success mb-0">{{ session('success') }}</div>
</div>
@endif

@if($isAdmin)
<section class="py-3 bg-light border-bottom">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="text-muted">
            <i class="fas fa-shield-alt me-2 text-primary"></i>Khu vuc quan tri bai viet
        </span>
        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Them khuyen mai moi
        </a>
    </div>
</section>
@endif

<div class="container mt-4">
    <form method="GET" action="{{ route('posts.index') }}" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Tim kiem</label>
                    <input type="text" name="q" class="form-control" placeholder="Nhap tieu de hoac noi dung"
                        value="{{ $filters['q'] ?? '' }}">
                </div>

                @if($isAdmin)
                <div class="col-md-2">
                    <label class="form-label">Vi tri</label>
                    <select name="type" class="form-select">
                        <option value="">Tat ca</option>
                        <option value="1" {{ ($filters['type'] ?? '') === '1' ? 'selected' : '' }}>Banner</option>
                        <option value="0" {{ ($filters['type'] ?? '') === '0' ? 'selected' : '' }}>The nho</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Trang thai</label>
                    <select name="status" class="form-select">
                        <option value="">Tat ca</option>
                        @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-2">
                    <label class="form-label">Sap xep</label>
                    <select name="sort" class="form-select">
                        <option value="priority"
                            {{ ($filters['sort'] ?? 'priority') === 'priority' ? 'selected' : '' }}>Uu tien</option>
                        <option value="latest" {{ ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' }}>Moi nhat
                        </option>
                        <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Cu nhat
                        </option>
                        <option value="title" {{ ($filters['sort'] ?? '') === 'title' ? 'selected' : '' }}>Tieu de A-Z
                        </option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-danger flex-fill">
                        <i class="fas fa-search me-1"></i>Loc
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    {{-- CAROUSEL --}}
    <div id="promoCarousel" class="carousel slide mb-5 shadow-sm rounded" data-bs-ride="carousel"
        data-bs-interval="3000">
        <div class="carousel-inner rounded">
            @forelse($sliderPosts as $index => $post)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ $post->image ? asset('storage/images/' . $post->image) : 'https://via.placeholder.com/1200x450?text=Banner' }}"
                    class="d-block w-100" alt="{{ $post->title }}" style="height: 450px; object-fit: cover;">

                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    @if($isAdmin)
                    <div class="mb-2 d-flex justify-content-center gap-2">
                        <span class="badge bg-light text-dark">{{ $statuses[$post->status] ?? $post->status }}</span>
                        @if($post->published_at)
                        <span class="badge bg-info text-dark">Dang luc
                            {{ $post->published_at->format('d/m/Y H:i') }}</span>
                        @endif
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Sua
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Xoa bai viet nay?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash me-1"></i>Xoa
                            </button>
                        </form>
                    </div>
                    @endif
                    <h4>{{ $post->title }}</h4>
                    <p>{{ \Illuminate\Support\Str::limit($post->content, 80) }}</p>
                </div>
            </div>
            @empty
            <div class="carousel-item active">
                <div class="d-flex justify-content-center align-items-center bg-light" style="height:450px;">
                    <p class="text-muted">Chua co banner nao</p>
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

    {{-- CARD GRID --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h3 class="mb-0">Uu dai khac danh cho ban</h3>
        <span class="text-muted small">Trang {{ $smallPosts->currentPage() }} / {{ $smallPosts->lastPage() }}</span>
    </div>

    <div class="row">
        @forelse($smallPosts as $post)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $post->image ? asset('storage/images/' . $post->image) : 'https://via.placeholder.com/400x300?text=Post' }}"
                    class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="card-title mb-0">{{ $post->title }}</h5>
                        @if($isAdmin)
                        <span class="badge {{ $post->isVisible() ? 'bg-success' : 'bg-secondary' }}">
                            {{ $statuses[$post->status] ?? $post->status }}
                        </span>
                        @endif
                    </div>

                    @if($isAdmin && $post->published_at)
                    <p class="small text-muted mb-2">Lich dang: {{ $post->published_at->format('d/m/Y H:i') }}</p>
                    @endif

                    <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($post->content, 90) }}</p>

                    <div class="mt-auto">
                        <a href="#" class="btn btn-outline-danger btn-sm w-100 mb-2">Xem ngay</a>
                        @if($isAdmin)
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>Sua
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                            onsubmit="return confirm('Xoa bai viet nay?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-trash me-1"></i>Xoa
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Khong tim thay khuyen mai phu hop.</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $smallPosts->links() }}
    </div>
</div>

@endsection