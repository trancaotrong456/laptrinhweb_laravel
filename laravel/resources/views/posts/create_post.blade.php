@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Thêm bài viết / Khuyến mãi mới</h2>

    {{-- Chú ý cực mạnh: Phải có enctype thì mới up được ảnh --}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tiêu đề bài viết</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh minh họa (Banner)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Nội dung chi tiết</label>
            <textarea name="content" class="form-control" rows="6" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Bài Viết</button>
        <div class="mb-3">
            <label class="form-label">Vị trí hiển thị</label>
            <select name="type" class="form-select">
                <option value="0">Ảnh nhỏ (Ưu đãi khác)</option>
                <option value="1">Banner to (Trượt ở trên)</option>
            </select>
        </div>
    </form>
</div>
@endsection