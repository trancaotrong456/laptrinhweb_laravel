@extends('layouts.admin')

@section('title', 'Chỉnh sửa Flash Sale - TTP Admin')

@push('styles')
<style>
.edit-card {
    background:#fff; border-radius:16px; padding:32px;
    border:1px solid #edf2f7; box-shadow:0 2px 15px rgba(0,0,0,.04);
    max-width:620px;
}
.fs-form-group { margin-bottom:20px; }
.fs-label { display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px; }
.fs-input {
    width:100%; height:46px; padding:0 16px;
    border:1.5px solid #e2e8f0; border-radius:10px;
    font-size:14px; color:#1e293b; background:#fff;
    transition:all .2s; font-family:inherit; appearance:none;
}
.fs-input:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,.1); outline:none; }
.btn-save-edit {
    background:#ef4444; color:#fff;
    padding:12px 30px; border-radius:50px;
    font-weight:700; border:none; cursor:pointer;
    display:inline-flex; align-items:center; gap:8px;
    font-family:inherit; font-size:14px; transition:all .2s;
}
.btn-save-edit:hover { background:#dc2626; box-shadow:0 4px 12px rgba(239,68,68,.3); }
.btn-back-link {
    background:#fff; color:#64748b;
    padding:12px 22px; border-radius:50px;
    font-weight:600; border:1.5px solid #e2e8f0;
    cursor:pointer; font-family:inherit; font-size:14px;
    text-decoration:none; display:inline-flex; align-items:center; gap:8px;
    transition:all .2s;
}
.btn-back-link:hover { background:#f8fafc; color:#1e293b; }
</style>
@endpush

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title" style="font-size:22px; font-weight:800; color:#1e293b; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-pen" style="color:#ef4444;"></i> Chỉnh sửa Flash Sale
        </h1>
        <div class="text-muted" style="font-size:13px; margin-top:4px;">ID: #{{ $flashSale->id }}</div>
    </div>
    <a href="{{ route('admin.flash-sales.index') }}" class="btn-back-link">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="edit-card">
    @if($errors->any())
    <div style="background:#fef2f2; color:#ef4444; border:1px solid #fecaca; border-radius:10px; padding:14px 18px; margin-bottom:20px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.flash-sales.update', $flashSale->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="fs-form-group">
            <label class="fs-label">Sản phẩm</label>
            <select name="product_id" class="fs-input" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ $flashSale->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} ({{ number_format($product->price) }}đ)
                </option>
                @endforeach
            </select>
        </div>

        <div class="fs-form-group">
            <label class="fs-label">Giá Flash Sale (VNĐ)</label>
            <input type="number" name="sale_price" class="fs-input"
                   value="{{ old('sale_price', (int)$flashSale->sale_price) }}"
                   min="1" required placeholder="Vd: 99000">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="fs-form-group">
                <label class="fs-label">Bắt đầu</label>
                <input type="datetime-local" name="starts_at" class="fs-input"
                       value="{{ old('starts_at', $flashSale->starts_at->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="fs-form-group">
                <label class="fs-label">Kết thúc</label>
                <input type="datetime-local" name="ends_at" class="fs-input"
                       value="{{ old('ends_at', $flashSale->ends_at->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin-top:8px;">
            <button type="submit" class="btn-save-edit">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.flash-sales.index') }}" class="btn-back-link">Hủy</a>
        </div>
    </form>
</div>
@endsection
