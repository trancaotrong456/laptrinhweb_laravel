@extends('layouts.admin')
@section('title', 'Chỉnh sửa sản phẩm - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-edit" style="color:#2563eb;"></i> Chỉnh sửa sản phẩm</h1>
    <a href="{{ route('products.index') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 800px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form method="POST" enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Tên sản phẩm <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" class="form-ctrl" value="{{ old('name', $product->name) }}" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Giá bán (VNĐ) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="price" class="form-ctrl" value="{{ old('price', (int)$product->price) }}" required>
                </div>
                <div>
                    <label class="form-label-ctrl">Số lượng trong kho <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="quantity" class="form-ctrl" value="{{ old('quantity', $product->quantity) }}" required>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Danh mục sản phẩm <span style="color:#ef4444;">*</span></label>
                <select name="category_id" class="form-select-ctrl" style="cursor:pointer;" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $cate)
                    <option value="{{ $cate->id }}" {{ old('category_id', $product->category_id) == $cate->id ? 'selected' : '' }}>
                        {{ $cate->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Hình ảnh sản phẩm</label>
                
                @if($product->image)
                @php
                $imageUrl = str_contains($product->image, '/') ? asset('storage/' . $product->image) : asset('images/' . $product->image);
                @endphp
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;padding:12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                    <img src="{{ $imageUrl }}" alt="Current Image" style="width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #cbd5e1;">
                    <div>
                        <div style="font-size:13.5px;font-weight:600;color:#1e293b;">Ảnh hiện tại</div>
                        <div style="font-size:12px;color:#64748b;">Tải ảnh mới lên sẽ ghi đè ảnh cũ</div>
                    </div>
                </div>
                @endif
                
                <input type="file" name="image" class="form-ctrl" style="padding-top:9px;cursor:pointer;" accept="image/*">
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-ctrl">Mô tả sản phẩm</label>
                <textarea name="description" class="form-ctrl" rows="5">{{ old('description', $product->description) }}</textarea>
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Cập nhật sản phẩm</button>
            </div>
        </form>
    </div>
</div>
@endsection