<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>
<body>

<div class="form-container">

    <h2>Thêm sản phẩm</h2>

    {{-- HIỂN THỊ LỖI VALIDATION --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ route('products.store') }}">
        @csrf

        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Giá (VNĐ)</label>
            <input type="number" name="price" value="{{ old('price') }}" required>
            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Số lượng</label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" required>
            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Ảnh</label>
            <input type="file" name="image">
            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Danh mục sản phẩm</label>
            <select name="category_id" class="custom-select" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $cate)
                    <option value="{{ $cate->id }}" {{ old('category_id') == $cate->id ? 'selected' : '' }}>
                        {{ $cate->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn-submit">Thêm sản phẩm</button>
    </form>

</div>

</body>
</html>