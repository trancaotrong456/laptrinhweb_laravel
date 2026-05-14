<!DOCTYPE html>
<html>

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
    <footer style="background-color: #0000FF; color: white; padding: 10px; position: fixed; bottom: 0; width: 100%;">
        <p style="text-align: center; margin: 0;">&copy; Trần Cao Trọng - 24211TT1101</p>
    </footer>

</body>

</html>