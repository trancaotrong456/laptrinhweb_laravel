@extends('layout')

@section('title', 'Chỉnh sửa sản phẩm')

@push('styles')
<style>
/* Tổng quan bộ khung trang Admin */
.admin-edit-wrapper {
    max-width: 850px;
    margin: 40px auto;
    padding: 0 15px;
}

.card-admin-form {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}

/* Thanh tiêu đề Header Card */
.form-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 16px;
    background: #fafafa;
}

.btn-back-arrow {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    color: #495057;
    background: #ffffff;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back-arrow:hover {
    background: #f1f3f5;
    color: #212529;
    border-color: #ced4da;
}

.form-card-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #212121;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-card-header h2 i {
    color: #2e7d32;
}

/* Nội dung Form */
.form-card-body {
    padding: 28px 24px;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group-st {
    margin-bottom: 22px;
}

.form-group-st label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 14.5px;
    color: #2c3e50;
    margin-bottom: 8px;
}

.form-group-st label i {
    font-size: 14px;
    color: #546e7a;
    width: 16px;
    text-align: center;
}

/* Ô nhập liệu Input Custom */
.form-control-st {
    width: 100%;
    height: 42px;
    padding: 10px 14px;
    border: 1px solid #cccccc;
    border-radius: 8px;
    font-size: 14px;
    color: #333333;
    background-color: #ffffff;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control-st:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.12);
}

.form-help-text {
    font-size: 12.5px;
    color: #757575;
    margin-top: 6px;
    display: block;
}

/* Khu vực xử lý hình ảnh */
.image-management-zone {
    background: #f8f9fa;
    border: 1px dashed #ced4da;
    border-radius: 10px;
    padding: 20px;
    margin-top: 10px;
}

.preview-img-container {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
}

.preview-img-wrapper {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    overflow: hidden;
    background: #ffffff;
}

.preview-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Chân trang chứa nhóm nút hành động */
.form-card-footer {
    padding: 18px 24px;
    background: #fdfdfd;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn-admin-cancel {
    height: 40px;
    padding: 0 20px;
    border: 1px solid #cccccc;
    border-radius: 8px;
    background: #ffffff;
    color: #6c757d;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-admin-cancel:hover {
    background: #f8f9fa;
    color: #343a40;
    border-color: #adb5bd;
}

.btn-admin-submit {
    height: 40px;
    padding: 0 22px;
    border: none;
    border-radius: 8px;
    background: #0d6efd;
    /* Giữ màu xanh dương chuẩn nút hành động admin */
    color: #ffffff;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-admin-submit:hover {
    background: #0b5ed7;
}

@media (max-width: 768px) {
    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>
@endpush

@section('content')
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <a href="{{ route('products.index') }}">Quản lý sản phẩm</a>
        <span class="sep">›</span>
        <span class="cur">Sửa sản phẩm</span>
    </div>
</div>

<div class="admin-edit-wrapper">
    <div class="card-white card-admin-form">

        <div class="form-card-header">
            <a href="{{ route('products.index') }}" class="btn-back-arrow" title="Quay lại danh sách">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2>
                <i class="fas fa-box-open"></i> Chỉnh sửa thông tin sản phẩm
            </h2>
        </div>

        <form method="POST" enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="form-card-body">

                <div class="form-group-st">
                    <label><i class="fas fa-tag"></i> Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control-st @error('name') is-invalid @enderror" placeholder="Nhập tên sản phẩm chính xác..."
                        value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    <span class="form-help-text">Tên sản phẩm nên bao gồm loại sản phẩm và đặc tính riêng thương
                        hiệu.</span>
                </div>

                <div class="form-grid-2">
                    <div class="form-group-st">
                        <label><i class="fas fa-coins"></i> Giá bán (đ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control-st @error('price') is-invalid @enderror" placeholder="Ví dụ: 150000"
                            value="{{ old('price', (int)$product->price) }}" required>
                            @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        <span class="form-help-text">Nhập số nguyên dương, hệ thống tự động định dạng hiển thị tiền
                            tệ.</span>
                    </div>

                    <div class="form-group-st">
                        <label><i class="fas fa-cubes"></i> Số lượng kho <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control-st @error('quantity') is-invalid @enderror" placeholder="Ví dụ: 50"
                            value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        <span class="form-help-text">Số lượng sản phẩm hiện tại còn khả dụng trong kho.</span>
                    </div>
                </div>

                <div class="form-group-st">
                    <label><i class="fas fa-list"></i> Danh mục sản phẩm <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-control-st @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cate)
                        <option value="{{ $cate->id }}" {{ old('category_id', $product->category_id) == $cate->id ? 'selected' : '' }}>
                            {{ $cate->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <span class="form-help-text">Chọn danh mục thích hợp cho sản phẩm này.</span>
                </div>

                <div class="form-group-st">
                    <label><i class="fas fa-align-left"></i> Mô tả sản phẩm</label>
                    <textarea name="description" class="form-control-st @error('description') is-invalid @enderror" style="height: 120px; resize: vertical;" placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description', $product->description) }}</textarea>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <span class="form-help-text">Mô tả giúp khách hàng hiểu rõ hơn về sản phẩm.</span>
                </div>

                <div class="form-group-st">
                    <label><i class="fas fa-image"></i> Hình ảnh sản phẩm</label>

                    <div class="image-management-zone">
                        @if($product->image)

                            <div class="preview-img-container">
                                <div class="preview-img-wrapper">

                                    @if(file_exists(public_path('storage/' . $product->image)))
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('images/' . $product->image) }}"
                                            alt="{{ $product->name }}">
                                    @endif

                                </div>

                                <div>
                                    <div class="fw-bold text-dark" style="font-size:13.5px;">
                                        Ảnh hiện tại của sản phẩm
                                    </div>

                                    <div class="text-muted" style="font-size:12px;">
                                        Nếu tải ảnh mới lên, ảnh hiện tại sẽ bị thay thế.
                                    </div>
                                </div>
                            </div>

                        @endif

                        <div class="pt-1">
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept=".png,.jpg,.jpeg,.webp"
                                style="font-size: 13.5px; border-radius: 6px;">
                                @error('category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            <span class="form-help-text">Hỗ trợ định dạng file: JPG, PNG, JPEG, WEBP. Dung lượng tối đa
                                2MB.</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-card-footer">
                <a href="{{ route('products.index') }}" class="btn-admin-cancel">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
                <button type="submit" class="btn-admin-submit">
                    <i class="fas fa-save"></i> Cập nhật sản phẩm
                </button>
            </div>
        </form>

    </div>
</div>
@endsection