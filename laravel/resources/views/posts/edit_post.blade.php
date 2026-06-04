@extends('layout')
@section('title', 'Sửa bài viết: ' . $post->title)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4" style="max-width:860px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i>Xem bài
        </a>
        <h4 class="mb-0 fw-bold"><i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa bài viết</h4>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger rounded-3">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ── CỘT TRÁI ────────────────────────────────── --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3"
                                value="{{ old('title', $post->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nội dung chi tiết <span class="text-danger">*</span></label>
                            <textarea name="content" id="postContent" rows="10">{{ old('content', $post->content) }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Tags <small class="text-muted fw-normal">(phân cách bằng dấu phẩy)</small></label>
                            <input type="text" name="tags" class="form-control rounded-3"
                                value="{{ old('tags', $post->tags) }}"
                                placeholder="VD: flash-sale, mới về, hot deal">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CỘT PHẢI ────────────────────────────────── --}}
            <div class="col-lg-4">

                {{-- Ảnh đại diện --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-header border-0 py-3 px-4 fw-bold rounded-top-4"
                        style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);color:#1b5e20;">
                        <i class="fas fa-image me-2"></i>Ảnh đại diện
                    </div>
                    <div class="card-body p-3">
                        @if($post->image)
                        <div class="mb-2">
                            <img id="imgPreview" src="{{ asset('images/' . $post->image) }}"
                                class="rounded-3 border w-100" style="max-height:160px;object-fit:cover;" alt="{{ $post->title }}">
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                            <label class="form-check-label text-danger small" for="remove_image">Xóa ảnh hiện tại</label>
                        </div>
                        @else
                        <div id="imgPreviewWrap" class="mb-2 text-center d-none">
                            <img id="imgPreview" src="#" class="rounded-3 border w-100" style="max-height:160px;object-fit:cover;" alt="preview">
                        </div>
                        @endif
                        <input type="file" name="image" id="imageInput" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted d-block mt-1">JPG, PNG, WebP. Tối đa 2MB.</small>
                    </div>
                </div>

                {{-- Cài đặt --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-header border-0 py-3 px-4 fw-bold rounded-top-4"
                        style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);color:#1565c0;">
                        <i class="fas fa-cog me-2"></i>Cài đặt xuất bản
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-3">

                        <div>
                            <label class="form-label fw-semibold mb-1">Vị trí hiển thị</label>
                            <select name="type" class="form-select rounded-3">
                                <option value="0" {{ old('type', $post->type) == 0 ? 'selected' : '' }}>📰 Thẻ tin tức nhỏ</option>
                                <option value="1" {{ old('type', $post->type) == 1 ? 'selected' : '' }}>🖼 Banner lớn (Slideshow)</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label fw-semibold mb-1">Trạng thái</label>
                            <select name="status" class="form-select rounded-3">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $post->status) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label fw-semibold mb-1">Độ ưu tiên</label>
                            <input type="number" name="priority" class="form-control rounded-3"
                                value="{{ old('priority', $post->priority) }}" min="0">
                        </div>

                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_pinned" id="isPinnedBtn" value="1" {{ old('is_pinned', $post->is_pinned) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isPinnedBtn">
                                📌 Ghim bài viết lên đầu trang
                            </label>
                        </div>

                        <div>
                            <label class="form-label fw-semibold mb-1">⏰ Hẹn giờ đăng</label>
                            <input type="datetime-local" name="published_at" class="form-control rounded-3"
                                value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div>
                            <label class="form-label fw-semibold mb-1">⏳ Hết hạn lúc</label>
                            <input type="datetime-local" name="expires_at" class="form-control rounded-3"
                                value="{{ old('expires_at', optional($post->expires_at)->format('Y-m-d\TH:i')) }}">
                            <small class="text-muted">Để trống nếu không giới hạn thời gian.</small>
                        </div>
                    </div>
                </div>

                {{-- Tối ưu SEO --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-header border-0 py-3 px-4 fw-bold rounded-top-4"
                        style="background:linear-gradient(135deg,#fff3e0,#ffe0b2);color:#e65100;">
                        <i class="fas fa-search me-2"></i>Tối ưu SEO
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-semibold mb-1">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control rounded-3"
                                value="{{ old('meta_title', $post->meta_title) }}" placeholder="Tiêu đề SEO (tùy chọn)">
                        </div>
                        <div>
                            <label class="form-label fw-semibold mb-1">Meta Description</label>
                            <textarea name="meta_description" class="form-control rounded-3" rows="3"
                                placeholder="Mô tả SEO ngắn gọn (tùy chọn)...">{{ old('meta_description', $post->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Gửi thông báo Email --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 border-success">
                    <div class="card-body p-3">
                        <div class="form-check form-switch d-flex align-items-center gap-2 m-0 p-0">
                            <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" name="send_email" id="sendEmailBtn" value="1" style="width:40px;height:20px;cursor:pointer;">
                            <label class="form-check-label fw-bold text-success mb-0" for="sendEmailBtn" style="cursor:pointer;padding-left:10px;">
                                Gửi lại Email thông báo
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Dùng khi bạn muốn thông báo lại bài viết này cho tất cả khách hàng (chỉ gửi nếu trạng thái Công khai).</small>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mb-5">
                    <button type="submit" class="btn btn-warning rounded-3 py-2 fw-semibold">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-3">Quay lại danh sách</a>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
new EasyMDE({
    element: document.getElementById('postContent'),
    spellChecker: false,
    autosave: { enabled: true, uniqueId: 'post-edit-{{ $post->id }}' },
    toolbar: ['bold','italic','heading','|','quote','unordered-list','ordered-list','|','link','image','|','preview','side-by-side','fullscreen'],
});

// Preview ảnh mới
const imgInput = document.getElementById('imageInput');
if (imgInput) {
    imgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('imgPreview');
            if (preview) { preview.src = e.target.result; preview.closest('.d-none')?.classList.remove('d-none'); }
            const wrap = document.getElementById('imgPreviewWrap');
            if (wrap) wrap.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
