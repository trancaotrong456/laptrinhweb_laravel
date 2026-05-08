@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Thêm bài viết / Khuyến mãi mới</h2>

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

        <div class="mb-3">
            <label class="form-label">Tiêu đề bài viết</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh minh họa</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Nội dung chi tiết</label>
            <textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Vị trí hiển thị</label>
            <select name="type" class="form-select">
                <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>🖼️ Banner lớn (Slider trên cùng)</option>
                <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>🃏 Ảnh nhỏ (Ưu đãi bên dưới)</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Độ ưu tiên <small class="text-muted">(số to hiện trước)</small></label>
            <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Lưu bài viết
        </button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>

    </form>
</div>
@endsection