@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Tên danh mục <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $category->name) }}"
                                   placeholder="Nhập tên danh mục..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tên danh mục phải là duy nhất trong hệ thống</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1"></i>Mô tả
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Nhập mô tả chi tiết về danh mục...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mô tả giúp người dùng hiểu rõ hơn về danh mục này</div>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-info-circle me-1"></i>Thông tin hiện tại
                                        </h6>
                                        <p class="mb-1"><strong>ID:</strong> {{ $category->id }}</p>
                                        <p class="mb-1"><strong>Ngày tạo:</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="mb-0"><strong>Cập nhật:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-boxes me-1"></i>Sản phẩm liên quan
                                        </h6>
                                        <h4 class="mb-0">{{ $category->products_count }}</h4>
                                        <small>sản phẩm trong danh mục này</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus vào trường tên
    document.getElementById('name').focus();

    // Validation phía client
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        if (!name) {
            e.preventDefault();
            alert('Vui lòng nhập tên danh mục!');
            document.getElementById('name').focus();
        }
    });
});
</script>
@endpush
@endsection