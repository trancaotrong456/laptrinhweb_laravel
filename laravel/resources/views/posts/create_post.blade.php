@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Them bai viet / Khuyen mai moi</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm">
        @csrf

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Tieu de bai viet</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Anh dai dien</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Chap nhan jpg, jpeg, png, webp. Toi da 2MB.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Noi dung chi tiet</label>
                <textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vi tri hien thi</label>
                    <select name="type" class="form-select">
                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Banner lon</option>
                        <option value="0" {{ old('type', 0) == 0 ? 'selected' : '' }}>The uu dai nho</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Do uu tien</label>
                    <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}" min="0">
                    <small class="text-muted">So cang lon cang hien truoc.</small>
                </div>
            </div>

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
                    <small class="text-muted">De trong neu muon dang ngay khi trang thai la Cong khai.</small>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Luu bai viet
            </button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lai</a>
        </div>
    </form>
</div>
@endsection
