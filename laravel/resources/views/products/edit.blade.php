<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body>

<div class="form-container">

    <h2>Sửa sản phẩm</h2>

    <form method="POST" enctype="multipart/form-data"
          action="{{ route('products.update', $product->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" value="{{ $product->name }}">
        </div>

        <div class="form-group">
            <label>Giá</label>
            <input type="text" name="price" value="{{ $product->price }}">
        </div>

        <div class="form-group">
            <label>Số lượng</label>
            <input type="text" name="quantity" value="{{ $product->quantity }}">
        </div>

        <div class="form-group">
            <label>Ảnh mới</label>
            <input type="file" name="image">
        </div>

        @if($product->image)
            <div class="form-group">
                <label>Ảnh hiện tại</label><br>
                <img class="preview-img"
                     src="{{ asset('images/'.$product->image) }}"
                     width="100">
            </div>
        @endif

        <button class="btn-submit">Cập nhật</button>
    </form>

</div>

</body>
</html>