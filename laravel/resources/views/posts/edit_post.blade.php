@extends('layouts.admin')
@section('title', 'Chỉnh sửa Flash Sale - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-edit" style="color:#2563eb;"></i> Chỉnh sửa Flash Sale / Tin tức</h1>
    <a href="{{ route('posts.index') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 800px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form method="POST" enctype="multipart/form-data" action="{{ route('posts.update', $post->id) }}">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom:16px;">
                <label class="form-label-ctrl">Tiêu đề bài viết/chương trình <span style="color:#ef4444;">*</span></label>
                <input type="text" name="title" class="form-ctrl" value="{{ old('title', $post->title) }}" required>
                @error('title')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Độ ưu tiên (Hiển thị nổi bật) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="priority" class="form-ctrl" value="{{ old('priority', $post->priority) }}" required>
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Số càng lớn càng được ưu tiên hiển thị trước.</div>
                </div>
                <div>
                    <label class="form-label-ctrl">Ảnh Banner/Thumb</label>
                    @if($post->image)
                    @php
                    $imageUrl = str_contains($post->image, '/') ? asset('storage/' . $post->image) : asset('images/' . $post->image);
                    @endphp
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;padding:8px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                        <img src="{{ $imageUrl }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                        <div style="font-size:12px;color:#64748b;">Ảnh hiện tại. Tải ảnh mới sẽ ghi đè.</div>
                    </div>
                    @endif
                    <input type="file" name="image" class="form-ctrl" accept="image/*" style="padding-top:9px;cursor:pointer;">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-ctrl">Nội dung chi tiết <span style="color:#ef4444;">*</span></label>
                <textarea name="content" class="form-ctrl" rows="8" required>{{ old('content', $post->content) }}</textarea>
                @error('content')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Cập nhật bài viết</button>
            </div>
        </form>
    </div>
</div>
@endsection