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
                    <option value="1">Điện thoại</option>
                    <option value="2">Laptop</option>
                    <option value="3">Phụ kiện</option>
                </select>
            </div>

            <button class="btn-submit">Thêm sản phẩm</button>
        </form>

    </div>

</body>

</html>