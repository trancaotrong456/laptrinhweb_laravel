@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Thêm bài viết / Khuyến mãi mới</h2>

    {{-- HIỂN THỊ LỖI VALIDATE --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- TIÊU ĐỀ --}}
        <div class="mb-3">
            <label class="form-label">Tiêu đề bài viết</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        {{-- ẢNH --}}
        <div class="mb-3">
            <label class="form-label">Ảnh minh họa</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        {{-- NỘI DUNG --}}
        <div class="mb-3">
            <label class="form-label">Nội dung chi tiết</label>
            <textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
        </div>


        {{-- TYPE --}}
        <div class="mb-3">
            <label class="form-label">Vị trí hiển thị</label>
            <select name="type" class="form-select">
                <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>Ảnh nhỏ</option>
                <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Banner lớn</option>
            </select>
        </div>

        {{-- PRIORITY --}}
        <div class="mb-3">
            <label class="form-label">Độ ưu tiên</label>
            <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}" min="0">
        </div>

        {{-- STATUS --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Trang thai</label>
                <select name="status" class="form-select">
                    @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', 'published') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Hen gio dang</label>
                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
                <small class="text-muted d-block mt-1">
                    Để trống nếu muốn đăng ngay khi trạng thái là Công khai.
                </small>
            </div>
        </div>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Lưu bài viết
            </button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection