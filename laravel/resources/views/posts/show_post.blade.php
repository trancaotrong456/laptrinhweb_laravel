@extends('layout')
@section('title', $post->meta_title ?: $post->title . ' | Tin tức & Khuyến mãi')

@section('meta')
    <meta name="description" content="{{ $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}">
    @if($post->image)
    <meta property="og:image" content="{{ asset('images/' . $post->image) }}">
    @endif
    <meta property="og:type" content="article">
@endsection

@section('content')

{{-- ========== HERO HEADER ========== --}}
<div class="post-hero-section position-relative overflow-hidden mb-0"
    style="background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 40%, #388e3c 100%); min-height: 340px;">

    {{-- Background image overlay --}}
    @if($post->image)
    <div class="position-absolute top-0 start-0 w-100 h-100"
        style="background-image: url('{{ asset('images/' . $post->image) }}');
               background-size: cover; background-position: center;
               opacity: 0.18; mix-blend-mode: luminosity;">
    </div>
    @endif

    {{-- Decorative circles --}}
    <div class="position-absolute" style="width:300px;height:300px;border-radius:50%;
        background:rgba(255,255,255,0.04); top:-80px; right:-60px;"></div>
    <div class="position-absolute" style="width:180px;height:180px;border-radius:50%;
        background:rgba(255,255,255,0.06); bottom:-40px; left:40px;"></div>

    <div class="container position-relative py-5">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:13px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Trang chủ</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('posts.index') }}" class="text-white-50 text-decoration-none">Tin tức & Khuyến mãi</a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page"
                    style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    {{ Str::limit($post->title, 40) }}
                </li>
            </ol>
        </nav>

        {{-- Type badge --}}
        <div class="mb-3">
            @if($post->type == 1)
                <span class="badge rounded-pill px-3 py-2 fw-semibold"
                    style="background: rgba(255,193,7,0.18); color:#ffd54f; border:1px solid rgba(255,193,7,0.4); font-size:12px;">
                    <i class="fas fa-star me-1"></i> Banner Nổi Bật
                </span>
            @else
                <span class="badge rounded-pill px-3 py-2 fw-semibold"
                    style="background: rgba(129,199,132,0.18); color:#a5d6a7; border:1px solid rgba(129,199,132,0.4); font-size:12px;">
                    <i class="fas fa-tag me-1"></i> Tin Tức & Khuyến Mãi
                </span>
            @endif

            @if($isAdmin)
                @php
                    $statusColors = [
                        'published' => ['bg'=>'rgba(76,175,80,0.2)','color'=>'#a5d6a7','border'=>'rgba(76,175,80,0.4)'],
                        'draft'     => ['bg'=>'rgba(255,193,7,0.2)','color'=>'#fff176','border'=>'rgba(255,193,7,0.4)'],
                        'hidden'    => ['bg'=>'rgba(158,158,158,0.2)','color'=>'#e0e0e0','border'=>'rgba(158,158,158,0.4)'],
                    ];
                    $sc = $statusColors[$post->status] ?? $statusColors['hidden'];
                @endphp
                <span class="badge rounded-pill px-3 py-2 fw-semibold ms-1"
                    style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }}; border:1px solid {{ $sc['border'] }}; font-size:12px;">
                    <i class="fas fa-circle me-1" style="font-size:8px;"></i>
                    {{ $statuses[$post->status] ?? $post->status }}
                </span>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="text-white fw-bold mb-3 lh-sm" style="font-size: clamp(24px,4vw,38px); text-shadow:0 2px 8px rgba(0,0,0,0.3);">
            {{ $post->title }}
        </h1>

        {{-- Meta info --}}
        <div class="d-flex flex-wrap align-items-center gap-3 text-white-50" style="font-size:14px;">
            <span><i class="far fa-calendar-alt me-1"></i>
                {{ $post->created_at->format('d/m/Y') }}
            </span>
            <span><i class="far fa-eye me-1"></i>
                {{ number_format($post->views) }} lượt xem
            </span>
            <span><i class="far fa-heart me-1"></i>
                {{ $post->likes_count }} thích
            </span>
            <span><i class="far fa-comment me-1"></i>
                {{ $post->comments_count }} bình luận
            </span>
            @if($post->published_at)
            <span><i class="fas fa-bullhorn me-1"></i>
                Đăng lúc {{ $post->published_at->format('d/m/Y H:i') }}
            </span>
            @endif
            @if($isAdmin)
            <span><i class="fas fa-sort-amount-up me-1"></i>
                Ưu tiên: {{ $post->priority ?? 0 }}
            </span>
            @endif
        </div>

        {{-- Countdown nếu có expires_at --}}
        @if($post->expires_at && $post->expires_at->gt(now()))
        <div class="mt-3 d-inline-flex align-items-center gap-2 px-4 py-2 rounded-3"
            style="background:rgba(255,235,59,0.15);border:1px solid rgba(255,235,59,0.35);">
            <i class="fas fa-fire text-warning"></i>
            <span class="text-white fw-semibold" style="font-size:13px;">Kết thúc sau:</span>
            <span id="postCountdown" class="text-warning fw-bold" style="font-size:14px;font-family:monospace;"
                data-expires="{{ $post->expires_at->getTimestamp() * 1000 }}">
                Đang tính...
            </span>
        </div>
        @endif

        {{-- Tags --}}
        @if(!empty($post->tags_array))
        <div class="mt-3 d-flex flex-wrap gap-2">
            @foreach($post->tags_array as $tag)
            <a href="{{ route('posts.index', ['tag' => $tag]) }}"
               class="badge rounded-pill px-3 py-2 text-decoration-none"
               style="background:rgba(255,255,255,0.12);color:#e8f5e9;border:1px solid rgba(255,255,255,0.25);font-size:12px;">
                #{{ $tag }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ========== ADMIN TOOLBAR ========== --}}
@if($isAdmin)
<div style="background:linear-gradient(90deg,#e3f2fd,#bbdefb); border-bottom:2px solid #90caf9;">
    <div class="container py-2">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2 text-primary fw-semibold" style="font-size:14px;">
                <i class="fas fa-shield-alt"></i>
                <span>Chế độ Admin — Bạn đang xem trước bài viết</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i>Danh sách
                </a>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning rounded-pill px-3">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger rounded-pill px-3">
                        <i class="fas fa-trash me-1"></i>Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ========== MAIN CONTENT ========== --}}
<div class="container py-5">
    <div class="row g-4 g-lg-5">

        {{-- ---- LEFT: Article ---- --}}
        <div class="col-lg-8">

            {{-- Hình ảnh chính --}}
            @if($post->image)
            <div class="mb-4 rounded-4 overflow-hidden shadow"
                style="max-height:480px;">
                <img src="{{ asset('images/' . $post->image) }}"
                    alt="{{ $post->title }}"
                    class="w-100 d-block"
                    style="object-fit:cover; max-height:480px; width:100%;">
            </div>
            @endif

            {{-- Nội dung bài viết --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="post-content" style="font-size:16px; line-height:1.85; color:#333;">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>

            {{-- Share / Action bar --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4
                        p-3 rounded-3 border" style="background:#f9fbe7;">
                <div class="text-muted" style="font-size:13px;">
                    <i class="fas fa-info-circle me-1 text-success"></i>
                    Cập nhật lần cuối: {{ $post->updated_at->format('d/m/Y H:i') }}
                </div>
                <div class="d-flex align-items-center gap-2">
                    {{-- LIKE BUTTON --}}
                    @auth
                    <form id="likeForm" action="{{ route('posts.like.toggle', $post->id) }}" method="POST">
                        @csrf
                        <button type="button" id="likeBtn"
                            class="btn btn-sm rounded-pill px-3 {{ $userLiked ? 'btn-danger' : 'btn-outline-danger' }}"
                            onclick="toggleLike({{ $post->id }})">
                            <i class="fas fa-heart me-1"></i>
                            <span id="likeCount">{{ $post->likes_count }}</span>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fas fa-heart me-1"></i>{{ $post->likes_count }}
                    </a>
                    @endauth

                    {{-- SHARE Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank" rel="noopener"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fab fa-facebook-f me-1"></i>Share
                    </a>

                    {{-- COPY LINK --}}
                    <button type="button" onclick="copyLink('{{ url()->current() }}')"
                        class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="copyLinkBtn">
                        <i class="fas fa-link me-1"></i>Copy link
                    </button>

                    <a href="{{ route('posts.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                        <i class="fas fa-th-large me-1"></i>Tất cả KM
                    </a>
                </div>
            </div>

            {{-- ---- RELATED POSTS ---- --}}
            @if($relatedPosts->isNotEmpty())
            <div>
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2" style="font-size:18px;">
                    <span style="display:inline-block;width:4px;height:22px;background:#2e7d32;border-radius:4px;"></span>
                    {{ $post->type == 1 ? 'Banner khác' : 'Tin tức & Khuyến mãi liên quan' }}
                </h5>
                <div class="row g-3">
                    @foreach($relatedPosts as $related)
                    <div class="col-sm-6 col-md-3">
                        <a href="{{ route('posts.show', $related->id) }}"
                            class="text-decoration-none d-block h-100">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden post-related-card">
                                <div class="position-relative overflow-hidden" style="height:130px;">
                                    <img src="{{ $related->image ? asset('images/'.$related->image) : 'https://via.placeholder.com/400x300?text=Post' }}"
                                        alt="{{ $related->title }}"
                                        class="w-100 h-100"
                                        style="object-fit:cover; transition:transform .35s ease;">
                                    @if($isAdmin)
                                    <div class="position-absolute top-0 end-0 m-1">
                                        <span class="badge {{ $related->isVisible() ? 'bg-success' : 'bg-secondary' }}"
                                            style="font-size:10px;">
                                            {{ $statuses[$related->status] ?? $related->status }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body p-2">
                                    <p class="mb-0 fw-semibold text-dark lh-sm"
                                        style="font-size:13px; display:-webkit-box;
                                               -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                        {{ $related->title }}
                                    </p>
                                    <p class="mb-0 text-muted mt-1" style="font-size:11px;">
                                        {{ $related->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ════ BÌNH LUẬN ════ --}}
            <div class="mt-4" id="comments-section">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2" style="font-size:18px;">
                    <span style="display:inline-block;width:4px;height:22px;background:#2e7d32;border-radius:4px;"></span>
                    <i class="far fa-comments me-1 text-success"></i>
                    Bình luận ({{ $post->comments_count }})
                </h5>

                @if(session('comment_success'))
                <div class="alert alert-success alert-dismissible rounded-3 py-2 mb-3" style="font-size:14px;">
                    {{ session('comment_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                <div id="ajax-comment-message" class="d-none alert alert-success alert-dismissible rounded-3 py-2 mb-3" style="font-size:14px;"></div>

                {{-- Form gửi bình luận --}}
                @auth
                <form id="commentForm" action="{{ route('posts.comment.store', $post->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                            style="width:40px;height:40px;min-width:40px;background:linear-gradient(135deg,#2e7d32,#66bb6a);font-size:14px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <textarea name="content" class="form-control rounded-3" rows="2"
                                placeholder="Viết bình luận..." required maxlength="1000"></textarea>
                            <div class="mt-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                                    <i class="fas fa-paper-plane me-1"></i><span class="btn-text">Gửi bình luận</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                @else
                <div class="alert alert-light border rounded-3 text-center py-3 mb-4" style="font-size:14px;">
                    <a href="{{ route('login') }}" class="text-success fw-semibold">
                        <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                    </a> để viết bình luận.
                </div>
                @endauth

                {{-- Danh sách bình luận --}}
                <div id="comments-list">
                @forelse($comments as $comment)
                <div class="d-flex gap-3 mb-3 p-3 rounded-3 border bg-white comment-item" id="comment-{{ $comment->id }}">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                        style="width:38px;height:38px;min-width:38px;background:linear-gradient(135deg,#43a047,#66bb6a);font-size:13px;">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold" style="font-size:14px;">{{ $comment->user->name }}</span>
                                <span class="text-muted ms-2" style="font-size:12px;">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if(Auth::check() && Auth::id() == $comment->user_id)
                                <button type="button" onclick="editComment(this)" class="btn btn-link text-primary p-0 me-2" style="font-size:12px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif
                                @if(Auth::check() && (Auth::id() == $comment->user_id || (int)Auth::user()->role === 1))
                                <form action="{{ route('posts.comment.destroy', $comment->id) }}" method="POST" class="delete-comment-form">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="deleteComment(this)" class="btn btn-link text-danger p-0" style="font-size:12px;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        <div class="mb-0 mt-1 comment-body" data-update-url="{{ route('posts.comment.update', $comment->id) }}" data-raw="{{ $comment->content }}" style="font-size:14px;white-space:pre-wrap;">{{ $comment->content }}</div>
                    </div>
                </div>
                @empty
                <div id="no-comments-msg" class="text-center text-muted py-4 border rounded-3 bg-white" style="font-size:14px;">
                    <i class="far fa-comment-dots fa-2x mb-2 d-block text-muted opacity-50"></i>
                    Chưa có bình luận nào. Hãy là người đầu tiên!
                </div>
                @endforelse
                </div>

                @if($comments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $comments->links() }}
                </div>
                @endif
            </div>

        </div>{{-- end col-lg-8 --}}

        {{-- ---- RIGHT: Sidebar ---- --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top:80px;">

                {{-- Card thông tin bài viết --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header border-0 py-3 px-4 fw-bold"
                        style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9); color:#1b5e20; font-size:15px;">
                        <i class="fas fa-info-circle me-2"></i>Thông tin bài viết
                    </div>
                    <div class="card-body px-4 py-3">
                        <ul class="list-unstyled mb-0" style="font-size:14px;">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Loại</span>
                                <span class="fw-semibold text-dark">
                                    {{ $post->type == 1 ? '🖼 Banner' : '📰 Tin tức / KM' }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Ngày tạo</span>
                                <span class="fw-semibold text-dark">{{ $post->created_at->format('d/m/Y') }}</span>
                            </li>
                            @if($post->published_at)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Lịch đăng</span>
                                <span class="fw-semibold text-dark">{{ $post->published_at->format('d/m/Y H:i') }}</span>
                            </li>
                            @endif
                            @if($isAdmin)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Trạng thái</span>
                                @php
                                    $badge = match($post->status) {
                                        'published' => 'success',
                                        'draft'     => 'warning text-dark',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ $statuses[$post->status] ?? $post->status }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted">Mức ưu tiên</span>
                                <span class="fw-bold text-success">{{ $post->priority ?? 0 }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Admin quick actions --}}
                @if($isAdmin)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header border-0 py-3 px-4 fw-bold"
                        style="background:linear-gradient(135deg,#e3f2fd,#bbdefb); color:#1565c0; font-size:15px;">
                        <i class="fas fa-tools me-2"></i>Công cụ Admin
                    </div>
                    <div class="card-body px-4 py-3 d-flex flex-column gap-2">
                        <a href="{{ route('posts.edit', $post->id) }}"
                            class="btn btn-warning w-100 rounded-3 fw-semibold">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa bài viết
                        </a>
                        <a href="{{ route('posts.create') }}"
                            class="btn btn-success w-100 rounded-3 fw-semibold">
                            <i class="fas fa-plus me-2"></i>Thêm bài mới
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger w-100 rounded-3 fw-semibold">
                                <i class="fas fa-trash me-2"></i>Xóa bài viết này
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Back to list --}}
                <a href="{{ route('posts.index') }}"
                    class="btn w-100 rounded-3 py-2 fw-semibold"
                    style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9); color:#1b5e20; border:none; font-size:15px;">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>

            </div>
        </div>{{-- end col-lg-4 --}}

    </div>
</div>

{{-- ========== STYLES ========== --}}
<style>
    .post-hero-section { padding-bottom: 0; }

    .post-related-card { transition: transform .25s ease, box-shadow .25s ease; }
    .post-related-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.13) !important;
    }
    .post-related-card:hover img { transform: scale(1.06); }

    .post-content p { margin-bottom: 1.1rem; }
    .post-content h2, .post-content h3 { color: #1b5e20; font-weight: 700; margin-top: 1.8rem; }
    .post-content a { color: #2e7d32; }
    .post-content a:hover { text-decoration: underline; }

    @media (max-width: 767px) {
        .post-hero-section { min-height: 260px; }
    }
</style>

@push('scripts')
<script>
// ── Copy Link ──
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('copyLinkBtn');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã copy';
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        btn.classList.add('text-white');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.replace('btn-success', 'btn-outline-secondary');
            btn.classList.remove('text-white');
        }, 2000);
    });
}

// ── Like AJAX ──
async function toggleLike(postId) {
    const btn = document.getElementById('likeBtn');
    const countSpan = document.getElementById('likeCount');
    const token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value;

    try {
        const res = await fetch(`/khuyen-mai/${postId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        
        countSpan.textContent = data.count;
        if (data.liked) {
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
        } else {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        }
    } catch (err) {
        console.error('Lỗi like:', err);
    }
}

// ── Countdown Timer ──
const cdEl = document.getElementById('postCountdown');
if (cdEl) {
    const expiresMs = parseInt(cdEl.getAttribute('data-expires'));
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = expiresMs - now;
        
        if (distance < 0) {
            cdEl.innerHTML = "Đã kết thúc";
            clearInterval(timerInt);
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        let text = "";
        if (days > 0) text += days + "d ";
        text += String(hours).padStart(2, '0') + "h ";
        text += String(minutes).padStart(2, '0') + "m ";
        text += String(seconds).padStart(2, '0') + "s";
        
        cdEl.innerHTML = text;
    }
    
    updateCountdown();
    const timerInt = setInterval(updateCountdown, 1000);
}
// ── Comment AJAX ──
document.addEventListener("DOMContentLoaded", function() {
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.querySelector('textarea').value = '';
                    
                    const emptyMsg = document.getElementById('no-comments-msg');
                    if (emptyMsg) emptyMsg.remove();
                    
                    const html = `
                        <div class="d-flex gap-3 mb-3 p-3 rounded-3 border bg-white comment-item" id="comment-${data.comment.id}">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                                style="width:38px;height:38px;min-width:38px;background:linear-gradient(135deg,#43a047,#66bb6a);font-size:13px;">
                                ${data.comment.user_initial}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="fw-semibold" style="font-size:14px;">${data.comment.user_name}</span>
                                        <span class="text-muted ms-2" style="font-size:12px;">${data.comment.created_at}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        ${data.comment.can_edit ? `
                                        <button type="button" onclick="editComment(this)" class="btn btn-link text-primary p-0 me-2" style="font-size:12px;">
                                            <i class="fas fa-edit"></i>
                                        </button>` : ''}
                                        ${data.comment.can_delete ? `
                                        <form action="${data.comment.delete_url}" method="POST" class="delete-comment-form">
                                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button" onclick="deleteComment(this)" class="btn btn-link text-danger p-0" style="font-size:12px;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>` : ''}
                                    </div>
                                </div>
                                <div class="mb-0 mt-1 comment-body" data-update-url="${data.comment.update_url}" data-raw="${data.comment.content.replace(/"/g, '&quot;')}" style="font-size:14px;white-space:pre-wrap;">${data.comment.content}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('comments-list').insertAdjacentHTML('afterbegin', html);
                    
                    const msgBox = document.getElementById('ajax-comment-message');
                    if(msgBox) {
                        msgBox.innerHTML = `${data.message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                        msgBox.classList.remove('d-none');
                        setTimeout(() => msgBox.classList.add('d-none'), 3000);
                    }
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
});

function deleteComment(btn) {
    if (!confirm('Xóa bình luận này?')) return;
    
    const form = btn.closest('form');
    const commentDiv = form.closest('.comment-item');
    const url = form.action;
    
    fetch(url, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            commentDiv.remove();
            
            const list = document.getElementById('comments-list');
            if (list.querySelectorAll('.comment-item').length === 0) {
                list.innerHTML = `
                <div id="no-comments-msg" class="text-center text-muted py-4 border rounded-3 bg-white" style="font-size:14px;">
                    <i class="far fa-comment-dots fa-2x mb-2 d-block text-muted opacity-50"></i>
                    Chưa có bình luận nào. Hãy là người đầu tiên!
                </div>`;
            }
        }
    })
    .catch(err => console.error(err));
}

function editComment(btn) {
    const commentItem = btn.closest('.comment-item');
    const bodyDiv = commentItem.querySelector('.comment-body');
    const rawContent = bodyDiv.getAttribute('data-raw');
    const updateUrl = bodyDiv.getAttribute('data-update-url');
    
    // Lưu lại HTML cũ để có thể cancel
    if (!bodyDiv.hasAttribute('data-original-html')) {
        bodyDiv.setAttribute('data-original-html', bodyDiv.innerHTML);
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    bodyDiv.innerHTML = `
        <form class="edit-comment-form mt-2" action="${updateUrl}" method="POST" onsubmit="submitEditComment(event, this)">
            <input type="hidden" name="_token" value="${token}">
            <input type="hidden" name="_method" value="PUT">
            <textarea name="content" class="form-control rounded-3" rows="2" required maxlength="1000">${rawContent}</textarea>
            <div class="mt-2 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" onclick="cancelEditComment(this)">Hủy</button>
                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Lưu thay đổi</button>
            </div>
        </form>
    `;
    
    // Tạm ẩn nút Sửa & Xóa để tránh ấn nhầm lúc đang sửa
    commentItem.querySelector('.d-flex.align-items-center').classList.add('d-none');
}

function cancelEditComment(btn) {
    const commentItem = btn.closest('.comment-item');
    const bodyDiv = commentItem.querySelector('.comment-body');
    
    bodyDiv.innerHTML = bodyDiv.getAttribute('data-original-html');
    commentItem.querySelector('.d-flex.align-items-center').classList.remove('d-none');
}

function submitEditComment(e, form) {
    e.preventDefault();
    const commentItem = form.closest('.comment-item');
    const bodyDiv = commentItem.querySelector('.comment-body');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Đang lưu...';
    
    fetch(form.action, {
        method: 'POST', // Vẫn là POST nhưng có _method=PUT trong formData
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new FormData(form)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            bodyDiv.innerHTML = data.content;
            bodyDiv.setAttribute('data-raw', data.raw_content);
            bodyDiv.setAttribute('data-original-html', data.content);
            commentItem.querySelector('.d-flex.align-items-center').classList.remove('d-none');
            
            const msgBox = document.getElementById('ajax-comment-message');
            if(msgBox) {
                msgBox.innerHTML = `${data.message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                msgBox.classList.remove('d-none');
                setTimeout(() => msgBox.classList.add('d-none'), 3000);
            }
        }
    })
    .catch(err => console.error(err))
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Lưu thay đổi';
    });
}
</script>
@endpush

@endsection
