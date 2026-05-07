@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">➕ THÊM DANH MỤC MỚI</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Loại danh mục <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                        <option value="">-- Chọn loại --</option>
                        <option value="do_uong" {{ old('type') == 'do_uong' ? 'selected' : '' }}>🥤 Đồ uống</option>
                        <option value="thuc_pham" {{ old('type') == 'thuc_pham' ? 'selected' : '' }}>🍚 Thực phẩm</option>
                        <option value="gia_dung" {{ old('type') == 'gia_dung' ? 'selected' : '' }}>🏠 Gia dụng</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">🔙 Quay lại</a>
                    <button type="submit" class="btn btn-success">💾 Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection