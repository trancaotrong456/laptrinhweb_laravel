@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0">🔍 CHI TIẾT SẢN PHẨM</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="30%">ID:</th><td>{{ $product->id }}</td></tr>
                        <tr><th>Tên sản phẩm:</th><td><strong>{{ $product->name }}</strong></td></tr>
                        <tr><th>Danh mục:</th><td>{{ $product->category->name ?? 'Chưa có' }}</td></tr>
                        <tr><th>Giá:</th><td class="text-danger fw-bold">{{ number_format($product->price) }} VNĐ</td></tr>
                        <tr><th>Số lượng:</th><td>{{ $product->quantity }}</td></tr>
                        <tr><th>Ngày tạo:</th><td>{{ $product->created_at->format('d/m/Y H:i') }}</td></tr>
                        <tr><th>Cập nhật:</th><td>{{ $product->updated_at->format('d/m/Y H:i') }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">Mô tả</div>
                        <div class="card-body">
                            {{ $product->description ?? 'Chưa có mô tả cho sản phẩm này.' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">🔙 Quay lại</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">✏️ Sửa</a>
            </div>
        </div>
    </div>
</div>
@endsection