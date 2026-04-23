@extends('layout')

@section('content')
<div class="container mt-4">

    {{-- ================= PHẦN 1: BANNER LỚN TỰ ĐỘNG LƯỚT ================= --}}
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner rounded shadow-sm">
            @foreach($sliderPosts as $index => $post)
            {{-- Thằng đầu tiên phải có class 'active' thì nó mới hiện --}}
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

                {{-- Hiển thị ảnh: Nếu có ảnh thì lấy ảnh, không có thì lấy ảnh mặc định --}}
                <img src="{{ $post->image ? asset('images/' . $post->image) : 'https://via.placeholder.com/1200x450?text=Banner+Khuyen+Mai' }}"
                    class="d-block w-100" alt="{{ $post->title }}" style="height: 450px; object-fit: cover;">

                {{-- Khung đen mờ chứa Tiêu đề đè lên ảnh --}}
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    @if(Auth::check() && Auth::user()->role == 1)
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning w-auto mb-2 px-4">
                        ⚙️ Chỉnh sửa (Admin)
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
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection