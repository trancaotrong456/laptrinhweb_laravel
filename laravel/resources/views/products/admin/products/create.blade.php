@extends('layout')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Thêm sản phẩm</h4>
        </div>

        <div class="card-body">

            <form
                action="{{ route('products.store') }}"
                method="POST"
                enctype="multipart/form-data" novalidate>

                @csrf

                {{-- Tên sản phẩm --}}
                <div class="mb-3">
                    <label class="form-label">
                        Tên sản phẩm <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Nhập tên sản phẩm">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Giá --}}
                <div class="mb-3">
                    <label class="form-label">
                        Giá sản phẩm <span class="text-danger">*</span>
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}"
                        min="1">
                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Số lượng --}}
                <div class="mb-3">
                    <label class="form-label">
                        Số lượng <span class="text-danger">*</span>
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity') }}"
                        min="1">
                    @error('quantity')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Danh mục --}}
                <div class="mb-3">
                    <label class="form-label">
                        Danh mục <span class="text-danger">*</span>
                    </label>

                    <select
                        name="category_id"
                        class="form-select @error ('category_id') is-invalid @enderror">

                        <option value="">
                            -- Chọn danh mục --
                        </option>

                        @foreach($categories as $cate)
                            <option
                                value="{{ $cate->id }}"
                                {{ old('category_id') == $cate->id ? 'selected' : '' }}>
                                {{ $cate->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Ảnh --}}
                <div class="mb-3">
                    <label class="form-label">
                        Hình ảnh
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control @error('image') is-invalid @enderror"
                        id="imageInput"
                        accept=".png,.jpg,.jpeg,.webp">
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    <img
                        id="previewImage"
                        class="mt-3 rounded border"
                        style="max-width:200px; display:none;">
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Nhập mô tả sản phẩm">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                </div>

                <div class="d-flex gap-2">
                    <button
                        type="submit"
                        class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Thêm sản phẩm
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-secondary">
                        Quay lại
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
document
    .getElementById('imageInput')
    .addEventListener('change', function(e){

        const file = e.target.files[0];

        if(file){

            const reader = new FileReader();

            reader.onload = function(event){

                const img =
                    document.getElementById('previewImage');

                img.src = event.target.result;
                img.style.display = 'block';
            }

            reader.readAsDataURL(file);
        }
});
</script>

@endsection