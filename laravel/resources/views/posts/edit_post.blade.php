@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chinh sua bai viet: <span class="text-primary">{{ $post->title }}</span></h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Tieu de</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vi tri hien thi</label>
                    <select name="type" class="form-select">
                        <option value="0" {{ old('type', $post->type) == 0 ? 'selected' : '' }}>The uu dai nho</option>
                        <option value="1" {{ old('type', $post->type) == 1 ? 'selected' : '' }}>Banner lon</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Do uu tien</label>
                    <input type="number" name="priority" class="form-control" value="{{ old('priority', $post->priority) }}" min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trang thai</label>
                    <select name="status" class="form-select">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $post->status) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Hen gio dang</label>
                    <input
                        type="datetime-local"
                        name="published_at"
                        class="form-control"
                        value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                    <small class="text-muted">Bai viet chi hien thi khi da toi thoi gian nay.</small>
                </div>
            </div>

            <div class="mb-3 p-3 border rounded bg-light">
                <label class="form-label d-block">Anh dai dien</label>

                @if($post->image)
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Anh hien tai:</small>
                        <img src="{{ asset('images/' . $post->image) }}" width="180" class="rounded shadow-sm border" alt="{{ $post->title }}">
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                        <label class="form-check-label" for="remove_image">Xoa anh hien tai</label>
                    </div>
                @else
                    <p class="text-muted small mb-2">Bai viet chua co anh dai dien.</p>
                @endif

                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Chon anh moi neu muon thay anh hien tai.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Noi dung</label>
                <textarea name="content" class="form-control" rows="6" required>{{ old('content', $post->content) }}</textarea>
            </div>
        </div>

        <div class="card-footer bg-white d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4">Luu thay doi</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary px-4">Quay lai</a>
        </div>
    </form>
</div>
@endsection
