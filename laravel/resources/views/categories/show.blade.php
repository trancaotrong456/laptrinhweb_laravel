@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Thông tin danh mục -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-tag me-2"></i>{{ $category->name }}
                                </h4>
                                <small class="text-muted">ID: {{ $category->id }}</small>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>Chỉnh sửa
                            </a>
                            @if($category->products_count == 0)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-2">Mô tả</h6>
                            <p class="mb-0">
                                {{ $category->description ?: 'Chưa có mô tả cho danh mục này.' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-primary text-white text-center">
                                        <div class="card-body py-3">
                                            <h3 class="mb-0">{{ $category->products_count }}</h3>
                                            <small>Sản phẩm</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white text-center">
                                        <div class="card-body py-3">
                                            <h6 class="mb-0">{{ $category->created_at->format('d/m/Y') }}</h6>
                                            <small>Ngày tạo</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Sản phẩm trong danh mục
                        <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
                    </h5>
                </div>

                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Ảnh</th>
                                        <th width="25%">Tên sản phẩm</th>
                                        <th width="15%">Giá</th>
                                        <th width="10%">Số lượng</th>
                                        <th width="20%">Mô tả</th>
                                        <th width="15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $products->firstItem() + $loop->index }}</td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px; border-radius: 5px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">
                                                {{ number_format($product->price) }} VNĐ
                                            </span>
                                        </td>
                                        <td>
                                            @if($product->quantity > 10)
                                                <span class="badge bg-success">{{ $product->quantity }}</span>
                                            @elseif($product->quantity > 0)
                                                <span class="badge bg-warning">{{ $product->quantity }}</span>
                                            @else
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->description ? Str::limit($product->description, 30) : 'Chưa có mô tả' }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.show', $product) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có sản phẩm nào trong danh mục này</h5>
                            <p class="text-muted">Hãy thêm sản phẩm đầu tiên vào danh mục "{{ $category->name }}"</p>
                            <a href="{{ route('products.create') }}?category_id={{ $category->id }}"
                               class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Thêm sản phẩm
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection