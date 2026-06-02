@extends('layout')

@section('title', 'Thêm danh mục')

@push('styles')
<style>
.category-form-page {
    max-width: 920px;
    margin: 0 auto;
}

.category-form-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 16px;
}

.category-form-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.category-form-title h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: var(--text);
}

.category-form-title .title-icon {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: var(--green-pale);
    color: var(--green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.category-form-card {
    padding: 0;
    overflow: hidden;
}

.category-form-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
    background: #fafafa;
    font-weight: 800;
    color: var(--text);
}

.category-form-card-header i {
    color: var(--green);
}

.category-form-body {
    padding: 20px;
}

.category-help-box {
    height: 100%;
    background: var(--green-pale2);
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    padding: 18px;
    color: var(--text-soft);
}

.category-help-box h6 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    color: var(--green);
    font-weight: 800;
}

.category-help-box ul {
    margin: 0;
    padding-left: 18px;
}

.category-help-box li + li {
    margin-top: 8px;
}

.category-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 6px;
}

@media (max-width: 575px) {
    .category-form-head,
    .category-form-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .category-form-actions a,
    .category-form-actions button {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="category-form-page">
        <div class="category-form-head">
            <div class="category-form-title">
                <span class="title-icon">
                    <i class="fas fa-layer-group"></i>
                </span>
                <div>
                    <h2>Thêm danh mục mới</h2>
                    <div class="text-muted" style="font-size:13px;">Quản lý nhóm sản phẩm trong siêu thị</div>
                </div>
            </div>

            <a href="{{ route('categories.index') }}" class="btn-green-outline">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
        </div>

        @if($errors->any())
        <div class="alert-st error">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="card-white category-form-card">
            @csrf

            <div class="category-form-card-header">
                <i class="fas fa-plus-circle"></i>
                Thông tin danh mục
            </div>

            <div class="category-form-body">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="name">Tên danh mục <span class="text-red">*</span></label>
                            <input type="text"
                                   class="form-control-st @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Ví dụ: Rau củ tươi"
                                   required>
                            @error('name')
                            <div class="text-red mt-1" style="font-size:12.5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Loại danh mục</label>
                            <select id="type"
                                    name="type"
                                    class="form-control-st @error('type') is-invalid @enderror">
                                <option value="do_uong" {{ old('type', 'do_uong') === 'do_uong' ? 'selected' : '' }}>Đồ uống</option>
                                <option value="thuc_pham" {{ old('type') === 'thuc_pham' ? 'selected' : '' }}>Thực phẩm</option>
                                <option value="gia_dung" {{ old('type') === 'gia_dung' ? 'selected' : '' }}>Gia dụng</option>
                            </select>
                            @error('type')
                            <div class="text-red mt-1" style="font-size:12.5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control-st @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="5"
                                      placeholder="Nhập mô tả ngắn cho danh mục...">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="text-red mt-1" style="font-size:12.5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="category-help-box">
                            <h6>
                                <i class="fas fa-circle-info"></i>
                                Gợi ý nhập liệu
                            </h6>
                            <ul>
                                <li>Tên danh mục nên ngắn gọn và dễ tìm kiếm.</li>
                                <li>Chọn đúng loại để danh mục hiển thị ở khu vực phù hợp.</li>
                                <li>Mô tả giúp khách hàng hiểu nhanh nhóm sản phẩm.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="category-form-actions mt-4">
                    <a href="{{ route('categories.index') }}" class="btn-green-outline">
                        <i class="fas fa-times"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-green">
                        <i class="fas fa-save"></i>
                        Tạo danh mục
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const form = document.querySelector('.category-form-card');

    if (nameInput) {
        nameInput.focus();
    }

    if (form && nameInput) {
        form.addEventListener('submit', function(event) {
            if (!nameInput.value.trim()) {
                event.preventDefault();
                nameInput.focus();
            }
        });
    }
});
</script>
@endpush
@endsection
