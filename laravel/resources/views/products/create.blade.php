@extends('layouts.app')

<<<<<<< HEAD
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body>
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Trang chủ</a></li>

            @guest
            <li><a href="{{ route('login') }}">Đăng nhập</a></li>
            <li><a href="{{ route('user.createUser') }}">Đăng kí</a></li>
            @else
            <li><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li><a href="{{ route('signout') }}">Đăng xuất</a></li>
            @if(Auth::user()->role == 1)
            <li><a href="{{ route('user.listUser') }}">Quản lý User</a></li>
            <li><a href="{{ route('categories.index') }}">Danh mục</a></li>
            <li><a href="{{ route('posts.index') }}">Tin tức</a></li>
            @endif
            @endguest
        </ul>
    </nav>
=======

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">➕ THÊM SẢN PHẨM MỚI</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
>>>>>>> master

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required
                            placeholder="VD: Coca Cola, Gạo ST25, Nồi cơm điện...">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id"
                            class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <input type="number" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                            required placeholder="VD: 15000">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity"
                            class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}"
                            required placeholder="VD: 100">
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" id="description"
                            class="form-control @error('description') is-invalid @enderror" rows="4"
                            placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
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
<<<<<<< HEAD
    <footer style="background-color: #0000FF; color: white; padding: 10px; position: fixed; bottom: 0; width: 100%;">
        <p style="text-align: center; margin: 0;">&copy; Trần Cao Trọng - 24211TT1101</p>
    </footer>

</body>

</html>
=======
</div>
@endsection
>>>>>>> master
