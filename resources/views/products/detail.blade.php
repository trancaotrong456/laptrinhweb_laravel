@extends('layout')

@section('title', $product->name . ' - Chi tiết sản phẩm')

@php
    $productImage = null;

    if ($product->image) {
        if (\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://'])) {
            $productImage = $product->image;
        } elseif (\Illuminate\Support\Str::startsWith($product->image, ['products/'])) {
            $productImage = asset('storage/' . $product->image);
        } else {
            $productImage = asset('images/' . $product->image);
        }
    }

    $roundedAverage = (int) round($averageRating);
    $myReview = auth()->check() ? $reviews->firstWhere('user_id', auth()->id()) : null;
@endphp

@push('styles')
<style>
.product-detail-page {
    background:
        radial-gradient(circle at top left, rgba(249, 115, 22, .12), transparent 28%),
        radial-gradient(circle at bottom right, rgba(79, 70, 229, .12), transparent 28%),
        #f8fafc;
}

.detail-shell {
    padding: 42px 0 64px;
}

.detail-card,
.review-panel,
.comment-card,
.related-card {
    background: rgba(255, 255, 255, .92);
    border: 1px solid rgba(226, 232, 240, .9);
    border-radius: 28px;
    box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
}

.product-photo {
    min-height: 430px;
    border-radius: 24px;
    background: linear-gradient(135deg, #fff7ed, #eef2ff);
    overflow: hidden;
}

.product-photo img {
    width: 100%;
    height: 100%;
    min-height: 430px;
    object-fit: cover;
}

.price-text {
    color: #ef4444;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
}

.qty-input {
    max-width: 130px;
    border-radius: 16px;
    padding: 12px 16px;
}

.action-button {
    border: 0;
    border-radius: 18px;
    padding: 14px 20px;
    font-weight: 800;
}

.btn-cart-action {
    background: linear-gradient(135deg, #ffedd5, #fed7aa);
    color: #c2410c;
}

.btn-buy-action {
    background: linear-gradient(135deg, #f97316, #ef4444);
    color: white;
    box-shadow: 0 16px 30px rgba(249, 115, 22, .25);
}

.rating-number {
    color: #ff9800;
    font-size: 2.4rem;
    font-weight: 900;
}

.rating-star {
    color: #ff9800;
}

.rating-bar {
    height: 8px;
    background: #eef2f7;
    border-radius: 999px;
    overflow: hidden;
}

.rating-bar span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: #ff9800;
}

.rating-picker {
    display: inline-flex;
    flex-direction: row-reverse;
    gap: 6px;
}

.rating-picker input {
    display: none;
}

.rating-picker label {
    color: #cbd5e1;
    cursor: pointer;
    font-size: 1.8rem;
    transition: .2s ease;
}

.rating-picker input:checked ~ label,
.rating-picker label:hover,
.rating-picker label:hover ~ label {
    color: #ff9800;
}

.review-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    display: grid;
    place-items: center;
    font-weight: 800;
}

.related-card {
    transition: all .4s ease;
}

.related-card:hover {
    transform: translateY(-5px);
}
</style>
@endpush

@section('content')
<div class="product-detail-page">
    <div class="container detail-shell">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.all') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="detail-card p-3 p-lg-4 mb-5">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6">
                    <div class="product-photo d-flex align-items-center justify-content-center">
                        @if($productImage)
                        <img src="{{ $productImage }}" alt="{{ $product->name }}">
                        @else
                        <div class="text-center text-muted">
                            <i class="fas fa-box-open fa-5x mb-3"></i>
                            <div>Chưa có ảnh sản phẩm</div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="h-100 d-flex flex-column p-lg-3">
                        <div class="mb-3">
                            @if($product->category)
                            <span class="badge rounded-pill text-bg-primary px-3 py-2 mb-3">
                                {{ $product->category->name }}
                            </span>
                            @endif

                            <h1 class="fw-black display-6 mb-3">{{ $product->name }}</h1>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="rating-number fs-4">{{ number_format($averageRating, 1) }}</span>
                                <span>
                                    @for($star = 1; $star <= 5; $star++)
                                    <i class="{{ $star <= $roundedAverage ? 'fas' : 'far' }} fa-star rating-star"></i>
                                    @endfor
                                </span>
                                <a href="#reviews" class="text-decoration-none">{{ $reviewCount }} đánh giá</a>
                            </div>

                            <div class="price-text mb-3">{{ number_format($product->price) }}đ</div>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge rounded-pill {{ $product->quantity > 0 ? 'text-bg-success' : 'text-bg-secondary' }} px-3 py-2">
                                    {{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </span>
                                <span class="badge rounded-pill text-bg-light px-3 py-2">
                                    Kho còn: {{ $product->quantity }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4 text-secondary lh-lg">
                            {{ $product->description ?: 'Sản phẩm chất lượng, phù hợp sử dụng hằng ngày tại gia đình. Thông tin chi tiết sẽ được cửa hàng cập nhật thêm.' }}
                        </div>

                        <div class="mt-auto">
                            @auth
                            <form action="{{ route('cart.add') }}" method="POST" class="row g-3 align-items-end">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="col-sm-4">
                                    <label class="form-label fw-bold">Số lượng</label>
                                    <input type="number" name="quantity" class="form-control qty-input" value="1" min="1"
                                        max="{{ max(1, (int) $product->quantity) }}"
                                        {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-sm-8">
                                    <div class="d-grid d-md-flex gap-2">
                                        <button type="submit" class="btn action-button btn-cart-action flex-fill"
                                            {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ
                                        </button>

                                        <button type="submit" formaction="{{ route('products.buyNow', $product) }}"
                                            class="btn action-button btn-buy-action flex-fill"
                                            {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-bolt me-2"></i>Mua ngay
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="d-grid d-sm-flex gap-2">
                                <a href="{{ route('login') }}" class="btn action-button btn-buy-action">
                                    <i class="fas fa-right-to-bracket me-2"></i>Đăng nhập để mua hàng
                                </a>
                                <a href="#reviews" class="btn action-button btn-cart-action">
                                    Xem đánh giá
                                </a>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="reviews" class="row g-4 mb-5">
            <div class="col-lg-5">
                <div class="review-panel p-4 h-100">
                    <h2 class="fw-bold mb-4">Đánh giá</h2>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                        <div>
                            <div class="fs-4">
                                @for($star = 1; $star <= 5; $star++)
                                <i class="{{ $star <= $roundedAverage ? 'fas' : 'far' }} fa-star rating-star"></i>
                                @endfor
                            </div>
                            <a href="#comments" class="text-decoration-none">{{ $reviewCount }} đánh giá</a>
                        </div>
                    </div>

                    @for($rating = 5; $rating >= 1; $rating--)
                    @php
                        $count = $ratingCounts[$rating] ?? 0;
                        $percent = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0;
                    @endphp
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-nowrap" style="width: 42px;">
                            {{ $rating }} <i class="fas fa-star text-dark"></i>
                        </div>
                        <div class="rating-bar flex-grow-1"><span style="width: {{ $percent }}%"></span></div>
                        <strong style="width: 44px;">{{ $percent }}%</strong>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="col-lg-7">
                <div class="review-panel p-4 h-100">
                    <h3 class="fw-bold mb-3">{{ $myReview ? 'Cập nhật đánh giá của bạn' : 'Bình luận và đánh giá' }}</h3>

                    @auth
                    <form action="{{ route('products.reviews.store', $product) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <div class="rating-picker">
                                @for($rating = 5; $rating >= 1; $rating--)
                                <input type="radio" id="rating-{{ $rating }}" name="rating" value="{{ $rating }}"
                                    {{ (int) old('rating', $myReview->rating ?? 5) === $rating ? 'checked' : '' }}>
                                <label for="rating-{{ $rating }}"><i class="fas fa-star"></i></label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bình luận</label>
                            <textarea name="comment" rows="5" class="form-control rounded-4"
                                placeholder="Chia sẻ cảm nhận của bạn về sản phẩm...">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn action-button btn-buy-action">
                            <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                        </button>
                    </form>
                    @else
                    <div class="p-4 rounded-4 bg-light">
                        <p class="mb-3">Bạn cần đăng nhập để viết bình luận và chấm sao cho sản phẩm.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">
                            Đăng nhập
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        <div id="comments" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold mb-0">Bình luận mới nhất</h3>
                <span class="text-muted">{{ $reviewCount }} phản hồi</span>
            </div>

            <div class="row g-3">
                @forelse($reviews as $review)
                <div class="col-12">
                    <div class="comment-card p-4">
                        <div class="d-flex gap-3">
                            <div class="review-avatar">
                                {{ strtoupper(mb_substr($review->user_name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <strong>{{ $review->user_name }}</strong>
                                    <span class="text-success small">
                                        <i class="fas fa-circle-check me-1"></i>Khách hàng đã đăng nhập
                                    </span>
                                    <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="mb-2">
                                    @for($star = 1; $star <= 5; $star++)
                                    <i class="{{ $star <= $review->rating ? 'fas' : 'far' }} fa-star rating-star"></i>
                                    @endfor
                                </div>

                                <p class="mb-0 text-secondary">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="comment-card p-5 text-center text-muted">
                        <i class="far fa-comment-dots fa-4x mb-3 opacity-50"></i>
                        <h5 class="fw-bold">Chưa có bình luận nào</h5>
                        <p class="mb-0">Hãy là người đầu tiên đánh giá sản phẩm này.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        @if($relatedProducts->isNotEmpty())
        <section>
            <h3 class="fw-bold mb-3">Sản phẩm liên quan</h3>

            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                @php
                    $relatedImage = null;

                    if ($relatedProduct->image) {
                        if (\Illuminate\Support\Str::startsWith($relatedProduct->image, ['http://', 'https://'])) {
                            $relatedImage = $relatedProduct->image;
                        } elseif (\Illuminate\Support\Str::startsWith($relatedProduct->image, ['products/'])) {
                            $relatedImage = asset('storage/' . $relatedProduct->image);
                        } else {
                            $relatedImage = asset('images/' . $relatedProduct->image);
                        }
                    }
                @endphp
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 h-100 shadow-lg overflow-hidden rounded-4 related-card">
                        <a href="{{ route('products.detail', $relatedProduct) }}" class="position-relative overflow-hidden d-block"
                            style="height: 220px;">
                            @if($relatedImage)
                            <img src="{{ $relatedImage }}" class="card-img-top w-100 h-100 object-fit-cover"
                                alt="{{ $relatedProduct->name }}">
                            @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                                <i class="fas fa-box-open fa-3x"></i>
                            </div>
                            @endif
                        </a>

                        <div class="card-body p-3 d-flex flex-column">
                            <a href="{{ route('products.detail', $relatedProduct) }}" class="text-decoration-none text-dark">
                                <h6 class="fw-bold mb-2"
                                    style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $relatedProduct->name }}
                                </h6>
                            </a>

                            <div class="mb-2">
                                <span class="h5 fw-bolder text-danger">
                                    {{ number_format($relatedProduct->price) }}đ
                                </span>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('products.detail', $relatedProduct) }}"
                                    class="btn btn-outline-secondary w-100 mt-2 rounded-3">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection
