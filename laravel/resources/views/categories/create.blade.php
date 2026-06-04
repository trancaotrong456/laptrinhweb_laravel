@extends('layouts.admin')
@section('title', 'Thêm danh mục - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-tags" style="color:#2563eb;"></i> Thêm danh mục mới</h1>
    <a href="{{ route('categories.index') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 600px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            
            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Tên danh mục <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" class="form-ctrl" value="{{ old('name') }}" placeholder="Nhập tên danh mục..." required>
                @error('name')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-ctrl">Mô tả danh mục</label>
                <textarea name="description" class="form-ctrl" rows="4" placeholder="Nhập mô tả (không bắt buộc)...">{{ old('description') }}</textarea>
                @error('description')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Thêm danh mục</button>
            </div>
        </form>
    </div>
</div>
@endsection
