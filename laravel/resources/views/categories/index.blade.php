@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i class="fas fa-tags me-2"></i>Quản lý danh mục</h4>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Thêm danh mục mới</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('categories.index') }}" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc mô tả..." value="{{ $search ?? '' }}">
                                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                                @if(isset($search) && $search) <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a> @endif
                            </form>
                        </div>
                        <div class="col-md-6 text-end"><small class="text-muted">Tổng cộng: <strong>{{ $categories->total() }}</strong> danh mục</small></div>
                    </div>

                    @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="25%">Tên danh mục</th>
                                    <th width="45%">Mô tả</th>
                                    <th width="10%">Số sản phẩm</th>
                                    <th width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $categories->firstItem() + $loop->index }}</td>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td>{{ $category->description ? Str::limit($category->description, 50) : 'Chưa có mô tả' }}</td>
                                    <td><span class="badge bg-info">{{ $category->products_count ?? $category->products()->count() }}</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" {{ ($category->products_count ?? $category->products()->count()) > 0 ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">{{ $categories->links() }}</div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không tìm thấy danh mục nào</h5>
                        @if(isset($search) && $search) <p class="text-muted">Không có kết quả cho từ khóa: <strong>"{{ $search }}"</strong></p> <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">Xem tất cả danh mục</a>
                        @else <a href="{{ route('categories.create') }}" class="btn btn-primary">Thêm danh mục đầu tiên</a> @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection