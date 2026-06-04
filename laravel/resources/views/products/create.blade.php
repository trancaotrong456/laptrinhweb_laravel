@extends('layouts.admin')
@section('title', 'Thêm sản phẩm - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-box-open" style="color:#2563eb;"></i> Thêm sản phẩm mới</h1>
    <a href="{{ route('products.index') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 800px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form method="POST" enctype="multipart/form-data" action="{{ route('products.store') }}">
            @csrf

            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Tên sản phẩm <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" class="form-ctrl" placeholder="Nhập tên sản phẩm..." required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Giá bán (VNĐ) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="price" class="form-ctrl" placeholder="Ví dụ: 150000" required>
                </div>
                <div>
                    <label class="form-label-ctrl">Số lượng trong kho <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="quantity" class="form-ctrl" placeholder="Ví dụ: 100" required>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Danh mục sản phẩm <span style="color:#ef4444;">*</span></label>
                <select name="category_id" class="form-ctrl" style="cursor:pointer;" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $cate)
                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Ảnh sản phẩm</label>
                <input type="file" name="image" class="form-ctrl" style="padding-top:9px;cursor:pointer;" accept="image/*">
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-ctrl">Mô tả sản phẩm</label>
                <textarea name="description" class="form-ctrl" rows="5" placeholder="Nhập mô tả chi tiết cho sản phẩm..."></textarea>
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Thêm sản phẩm</button>
            </div>
        </form>
    </div>
</div>
@endsection