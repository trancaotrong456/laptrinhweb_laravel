@extends('layout')

@section('title', 'Quản lý danh mục - Siêu thị trực tuyến')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card-white"
                style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.04); padding: 30px; background: #ffffff;">

                {{-- Tiêu đề & Nút thêm mới --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 pb-3 border-bottom"
                    style="border-color: #f1f1f1 !important;">
                    <div>
                        <h4 style="font-size: 22px; font-weight: 800; color: #212121; margin: 0;">
                            <i class="fas fa-layer-group me-2" style="color: #2e7d32;"></i>Quản lý danh mục
                        </h4>
                        <p class="text-muted mb-0 mt-1" style="font-size: 13px;">Xem, tìm kiếm, sắp xếp và quản lý các
                            phân loại sản phẩm trong hệ thống</p>
                    </div>
                    <div>
                        <a href="{{ route('categories.create') }}" class="btn-green-action"
                            style="text-decoration: none;">
                            <i class="fas fa-plus me-1"></i> Thêm danh mục mới
                        </a>
                    </div>
                </div>

                {{-- Khối Tìm kiếm & Sắp xếp dữ liệu --}}
                <div class="mb-4">
                    <form action="{{ url style="max-width: 100%;" }}" method="GET" class="row g-3">
                        <div class="col-12 col-md-6 col-lg-7">
                            <div class="position-relative">
                                <input type="text" name="search" value="{{ $search ?? '' }}"
                                    class="form-control-custom ps-5"
                                    placeholder="Tìm kiếm theo tên hoặc mô tả danh mục...">
                                <i class="fas fa-search position-absolute top-50 translate-middle-y text-muted"
                                    style="left: 18px;"></i>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                            <select name="sort" class="form-control-custom"
                                style="background-position: right 14px center;">
                                <option value="newest" {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>Mới nhất
                                </option>
                                <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Cũ nhất
                                </option>
                                <option value="quantity" {{ ($sort ?? '') == 'quantity' ? 'selected' : '' }}>Số lượng
                                    sản phẩm giảm dần</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2 d-grid">
                            <button type="submit" class="btn-search-submit">
                                <i class="fas fa-filter me-1"></i> Lọc dữ liệu
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Bảng danh sách dữ liệu thực tế --}}
                <div class="table-responsive" style="border-radius: 8px; border: 1px solid #eef2f5;">
                    <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="text-center"
                                    style="width: 70px; color: #495057; font-weight: 700; padding: 15px 10px;">STT</th>
                                <th style="color: #495057; font-weight: 700; padding: 15px 10px;">Tên danh mục</th>
                                <th style="color: #495057; font-weight: 700; padding: 15px 10px;">Mô tả</th>
                                <th class="text-center"
                                    style="width: 150px; color: #495057; font-weight: 700; padding: 15px 10px;">Số sản
                                    phẩm</th>
                                <th class="text-center"
                                    style="width: 150px; color: #495057; font-weight: 700; padding: 15px 10px;">Hành
                                    động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $category)
                            <tr>
                                <td class="text-center text-muted" style="padding: 15px 10px;">
                                    {{ ($categories->currentPage() - 1) * $categories->perPage() + $key + 1 }}
                                </td>
                                <td style="padding: 15px 10px;">
                                    <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $category->name }}
                                    </div>
                                    @if($category->slug)
                                    <small class="text-muted"
                                        style="font-size: 11px; font-family: monospace; display: block; margin-top: 2px;">
                                        slug: {{ $category->slug }}
                                    </small>
                                    @endif
                                </td>
                                <td class="text-muted"
                                    style="padding: 15px 10px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $category->description ?: 'Không có mô tả chi tiết' }}
                                </td>
                                <td class="text-center" style="padding: 15px 10px;">
                                    <span class="badge"
                                        style="background-color: #e8f5e9; color: #2e7d32; font-size: 13px; font-weight: 600; padding: 6px 12px; border-radius: 6px;">
                                        {{ $category->products_count ?? 0 }} sản phẩm
                                    </span>
                                </td>
                                <td class="text-center" style="padding: 15px 10px;">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('categories.show', $category->id) }}"
                                            class="btn-action-icon btn-view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="btn-action-icon btn-edit" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục &ldquo;{{ $category->name }}&rdquo; không?');"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')