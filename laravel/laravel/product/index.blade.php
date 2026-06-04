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
        <div class="filters">
            <form method="GET" action="{{ route('products.index') }}" class="search">
                <input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="{{ $keyword ?? '' }}">
                <select name="category_id">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Tìm kiếm</button>
                @if($keyword || $category_id)
                    <a href="{{ route('products.index') }}" class="btn-clear">Xóa bộ lọc</a>
                @endif
            </form>
        </div>

        <a class="btn-add" href="{{ route('products.create') }}">+ Thêm sản phẩm</a>
    </div>

    <h2>Danh sách sản phẩm</h2>

    <table>
        <tr>
            <th>Tên</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Danh mục</th>
            <th>Ảnh</th>
            <th>Action</th>
        </tr>

        @foreach($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ number_format($p->price) }} đ</td>
            <td>{{ $p->quantity }}</td>
            <td>
                @if($p->category)
                    <span class="badge badge-primary">{{ $p->category->name }}</span>
                @else
                    <span class="badge badge-secondary">Chưa phân loại</span>
                @endif
            </td>
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