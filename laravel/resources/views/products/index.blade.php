<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body>

<div class="container">

    <div class="top-bar">
        <form method="GET" action="{{ route('products.index') }}" class="search">
            <input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="{{ $keyword ?? '' }}">
            <button type="submit">Tìm kiếm</button>
        </form>

        <a class="btn-add" href="{{ route('products.create') }}">+ Thêm sản phẩm</a>
    </div>

    <h2>Danh sách sản phẩm</h2>

    <table>
        <tr>
            <th>Tên</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Ảnh</th>
            <th>Action</th>
        </tr>

        @foreach($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ number_format($p->price) }} đ</td>
            <td>{{ $p->quantity }}</td>
            <td>
                @if($p->image)
                    <img src="{{ asset('images/'.$p->image) }}" width="70">
                @endif
            </td>
            <td class="actions">
                <a class="btn-edit" href="{{ route('products.edit', $p->id) }}">Sửa</a>

                <form action="{{ route('products.destroy', $p->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn-delete" onclick="return confirm('Xóa sản phẩm này?')">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

</div>

</body>
</html>