@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">📋 TẤT CẢ DANH MỤC</h3>
            <a href="{{ route('categories.create') }}" class="btn btn-light">
                ➕ Thêm danh mục mới
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
                            <th width="25%">Tên danh mục</th>
                            <th width="15%">Loại</th>
                            <th width="35%">Mô tả</th>
                            <th width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>
                                @if($item->type == 'do_uong')
                                    <span class="badge bg-primary">🥤 Đồ uống</span>
                                @elseif($item->type == 'thuc_pham')
                                    <span class="badge bg-success">🍚 Thực phẩm</span>
                                @else
                                    <span class="badge bg-info">🏠 Gia dụng</span>
                                @endif
                            </td>
                            <td>{{ $item->description ?? 'Chưa có mô tả' }}</td>
                            <td>
                                <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('categories.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa danh mục {{ $item->name }}?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info mb-0">
                                    📭 Chưa có danh mục nào. 
                                    <a href="{{ route('categories.create') }}" class="alert-link">Thêm danh mục mới</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($categories, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection