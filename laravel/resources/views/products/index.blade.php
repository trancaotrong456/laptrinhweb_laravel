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
                                @if($item->quantity < 10)
                                    <span class="text-danger fw-bold">{{ $item->quantity }}</span>
                                @else
                                    <span class="text-success">{{ $item->quantity }}</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($item->description, 50) ?? 'Chưa có mô tả' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('products.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm {{ $item->name }}?')">
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
    </div>
</div>
@endsection