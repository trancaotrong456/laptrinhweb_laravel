@extends('layouts.admin')
@section('title', 'Thêm Flash Sale - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-bolt" style="color:#2563eb;"></i> Thêm Flash Sale / Tin tức</h1>
    <a href="{{ route('posts.index') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 800px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form method="POST" enctype="multipart/form-data" action="{{ route('posts.store') }}">
            @csrf
            
            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Tiêu đề bài viết/chương trình <span style="color:#ef4444;">*</span></label>
                <input type="text" name="title" class="form-ctrl" value="{{ old('title') }}" placeholder="VD: Sale sập sàn 50%..." required>
                @error('title')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Độ ưu tiên (Hiển thị nổi bật) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="priority" class="form-ctrl" value="{{ old('priority', 0) }}" required>
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Số càng lớn càng được ưu tiên hiển thị trước (>5 sẽ có tag HOT DEAL).</div>
                </div>
                <div>
                    <label class="form-label-ctrl">Ảnh Banner/Thumb</label>
                    <input type="file" name="image" class="form-ctrl" accept="image/*" style="padding-top:9px;cursor:pointer;">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-ctrl">Nội dung chi tiết <span style="color:#ef4444;">*</span></label>
                <textarea name="content" class="form-ctrl" rows="8" placeholder="Nhập nội dung chương trình..." required>{{ old('content') }}</textarea>
                @error('content')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Đăng bài viết</button>
            </div>
        </form>
    </div>
</div>
@endsection