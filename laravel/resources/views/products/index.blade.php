@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">📦 DANH SÁCH SẢN PHẨM</h3>
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> ➕ Thêm sản phẩm
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

<<<<<<< HEAD
<div class="container">
    <h2>Danh sách sản phẩm</h2>
    <div class="top-bar">
        <form method="GET" action="{{ route('products.index') }}" class="search">
            <input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="{{ $keyword ?? '' }}">
            <select name="category">
                <option value="">-- Danh mục --</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->name }}">{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="sort">

                <option value="">
                    -- Sắp xếp --
                </option>
            
                <option value="price_asc">
                    Giá tăng dần
                </option>
            
                <option value="price_desc">
                    Giá giảm dần
                </option>
            
                <option value="latest">
                    Mới nhất
                </option>
            
            </select>
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
                Tìm kiếm
            </button>
        </form>

        <a class="btn-add" href="{{ route('products.create') }}">+ Thêm sản phẩm</a>
    </div>
    @if(session('success'))
        <div class='alert-success'>{{ session('success') }}</div>
    @endif
    @if(isset($message))
        <div class='alert-search'>{{ $message }}</div>
    @endif
    <table>
        <tr>
            <th>Tên</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Ảnh</th>
            <th>Trạng thái</th>
            <th>Action</th>
        </tr>

        @foreach($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ number_format($p->price) }} đ</td>
            <td>{{ $p->quantity }}</td>
            <td>
                @if($p->image)
                    <img src="{{ asset('storage/'.$p->image) }}" width="70">
                @endif
            </td>
            <td>
                @if($p->status == 'Còn hàng')
                <span class="status-instock">Còn hàng</span>
                @else
                <span class="status-outstock">Hết hàng</span>
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
                <a class="btn-detail" href="{{ route('products.show', $p->id) }}">Chi tiết</a>
            </td>
        </tr>
        @endforeach
    </table>
    <div class="pagination-wrapper">
        {{ $products->links('pagination::bootstrap-5') }}
=======
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Tên sản phẩm</th>
                            <th width="15%">Danh mục</th>
                            <th width="15%">Giá</th>
                            <th width="10%">Số lượng</th>
                            <th width="25%">Mô tả</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>
                                @if($item->category)
                                @if($item->category->type == 'do_uong')
                                <span class="badge bg-primary">🥤 {{ $item->category->name }}</span>
                                @elseif($item->category->type == 'thuc_pham')
                                <span class="badge bg-success">🍚 {{ $item->category->name }}</span>
                                @else
                                <span class="badge bg-info">🏠 {{ $item->category->name }}</span>
                                @endif
                                @else
                                <span class="badge bg-secondary">Chưa có</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($item->price) }} VNĐ</strong></td>
                            <td>
                                @if($item->quantity < 10) <span class="text-danger fw-bold">{{ $item->quantity }}</span>
                                    @else
                                    <span class="text-success">{{ $item->quantity }}</span>
                                    @endif
                            </td>
                            <td>{{ Str::limit($item->description, 50) ?? 'Chưa có mô tả' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('products.destroy', $item->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa sản phẩm {{ $item->name }}?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    📭 Chưa có sản phẩm nào.
                                    <a href="{{ route('products.create') }}" class="alert-link">Thêm sản phẩm mới</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($products, 'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
            @endif
        </div>
>>>>>>> master
    </div>
</div>
@endsection