@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chỉnh sửa bài viết: <span class="text-primary">{{ $post->title }}</span></h2>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. Tiêu đề --}}
        <div class="mb-3">
            <label class="form-label font-weight-bold">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ $post->title }}" required>
        </div>

        <div class="row">
            {{-- 2. Vị trí hiển thị --}}
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Vị trí hiển thị</label>
                <select name="type" class="form-select">
                    <option value="0" {{ $post->type == 0 ? 'selected' : '' }}>Ảnh nhỏ (Ưu đãi khác)</option>
                    <option value="1" {{ $post->type == 1 ? 'selected' : '' }}>Banner to (Lướt ở trên)</option>
                </select>
            </div>

            {{-- 3. Độ ưu tiên (Thứ tự) --}}
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Thứ tự ưu tiên</label>
                <input type="number" name="priority" class="form-control" value="{{ $post->priority }}"
                    placeholder="Số to hiện trước">
            </div>
        </div>

        {{-- 4. Ảnh quảng cáo --}}
        <div class="mb-3 p-3 border rounded bg-light">
            <label class="form-label font-weight-bold d-block">Ảnh quảng cáo</label>
            <div class="mb-2">
                <small class="text-muted d-block mb-1">Ảnh hiện tại:</small>
                <img src="{{ asset('images/' . $post->image) }}" width="150" class="rounded shadow-sm border">
            </div>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-info">Để trống nếu sếp không muốn đổi ảnh.</small>
        </div>

        {{-- 5. Nội dung --}}
        <div class="mb-3">
            <label class="form-label font-weight-bold">Nội dung</label>
            <textarea name="content" class="form-control" rows="5" required>{{ $post->content }}</textarea>
        </div>

        <hr>
        <div class="mb-5">
            <button type="submit" class="btn btn-warning px-4">Lưu thay đổi</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary px-4">Quay lại</a>
        </div>
    </form>
</div>
@endsection