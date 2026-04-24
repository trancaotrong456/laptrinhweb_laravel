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

<<<<<<< Updated upstream
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
            <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}">
        </div>

        {{-- BUTTON --}}
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Lưu bài viết
        </button>
=======
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Vị trí hiển thị</label>
                <select name="type" class="form-select">
                    <option value="0">Ảnh nhỏ (Ưu đãi khác)</option>
                    <option value="1">Banner to (Trượt ở trên)</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Thứ tự ưu tiên</label>
                <input type="number" name="priority" class="form-control" placeholder="Số to hiện trước" value="0">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Bài Viết</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>
>>>>>>> Stashed changes
    </form>
</div>
@endsection