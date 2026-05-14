<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body>

    <div class="form-container">

        <h2>Chi tiết sản phẩm</h2>

        <img src="{{ asset('images/'.$product->image) }}"width="250">

        <p><strong>Tên:</strong>{{ $product->name }}</p>
        <p><strong>Giá:</strong>{{ number_format($product->price) }} đ</p>
        <p><strong>Số lượng:</strong>{{ $product->quantity }}</p>
        <p><strong>Trạng thái:</strong>{{ $product->status }}</p>
        <a class="btn-edit" href="{{ route('products.index') }}">Quay lại</a>

    </div>

</body>
</html>