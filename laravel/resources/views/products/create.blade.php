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

        <form method="POST" enctype="multipart/form-data" action="{{ route('products.store') }}">
            @csrf

            <div class="form-group">
                <label>Tên sản phẩm</label>
                <input type="text" name="name">
            </div>

            <div class="form-group">
                <label>Giá</label>
                <input type="text" name="price">
            </div>

            <div class="form-group">
                <label>Số lượng</label>
                <input type="text" name="quantity">
            </div>

            <div class="form-group">
                <label>Ảnh</label>
                <input type="file" name="image">
            </div>
            <div class="form-group">
                <label>Danh mục sản phẩm</label>

                <select name="category_id" class="custom-select">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $cate)
                    <option value="{{ $cate->id }}">{{ $cate-> name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn-submit">Thêm sản phẩm</button>
        </form>

    </div>

</body>

</html>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">➕ THÊM SẢN PHẨM MỚI</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="VD: Coca Cola, Gạo ST25, Nồi cơm điện...">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    @if($category->type == 'do_uong') 🥤 
                                    @elseif($category->type == 'thuc_pham') 🍚 
                                    @else 🏠 @endif
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price') }}" required placeholder="VD: 15000">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity') }}" required placeholder="VD: 100">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> 🔙 Quay lại
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> 💾 Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection