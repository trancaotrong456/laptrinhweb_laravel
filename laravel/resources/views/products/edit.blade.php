@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">Chỉnh sửa sản phẩm</h2>
            <p class="text-muted mb-0">
                Cập nhật thông tin chi tiết sản phẩm
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}"
               class="btn btn-light border px-4">
                Hủy bỏ
            </a>

            <button form="editProductForm"
                    class="btn btn-primary px-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                      </svg>
                Cập nhật sản phẩm
            </button>
        </div>

    </div>

    <form id="editProductForm"
          method="POST"
          enctype="multipart/form-data"
          action="{{ route('products.update', $product->id) }}">

        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Thông tin cơ bản --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Thông tin cơ bản
                        </h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Tên sản phẩm
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg"
                                   value="{{ $product->name }}">
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Giá bán
                                </label>

                                <input type="text"
                                       name="price"
                                       class="form-control"
                                       value="{{ $product->price }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Số lượng
                                </label>

                                <input type="text"
                                       name="quantity"
                                       class="form-control"
                                       value="{{ $product->quantity }}">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Danh mục sản phẩm
                            </label>

                            <select name="category_id"
                                    class="form-select">

                                <option value="">
                                    -- Chọn danh mục --
                                </option>

                                @foreach($categories as $cate)

                                <option value="{{ $cate->id }}"
                                    {{ $product->category_id == $cate->id ? 'selected' : '' }}>

                                    {{ $cate->name }}

                                </option>

                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">
                                Mô tả sản phẩm
                            </label>

                            <textarea rows="5"
                                      class="form-control"
                                      name="description"
                                      placeholder="Nhập mô tả sản phẩm...">{{ $product->description }}</textarea>
                        </div>

                    </div>

                </div>

                {{-- Giá & Kho --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-box-seam text-primary me-2"></i>
                            Giá & Kho hàng
                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">
                                    Giá nhập kho
                                </label>

                                <input type="text"
                                       class="form-control"
                                       placeholder="0.00">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">
                                    Đơn vị tính
                                </label>

                                <select class="form-select">
                                    <option>Kg</option>
                                    <option>Hộp</option>
                                    <option>Chai</option>
                                    <option>Cái</option>
                                </select>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                {{-- Trạng thái --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-toggle-on text-primary me-2"></i>
                            Trạng thái
                        </h5>

                        <div class="bg-light rounded-4 p-3 d-flex justify-content-between align-items-start">

                            <label class="form-check-label">
                        
                                <span class="fw-semibold d-block">
                                    Đang kinh doanh
                                </span>
                        
                                <small class="text-muted">
                                    Sản phẩm hiển thị trên website
                                </small>
                        
                            </label>
                        
                            <div class="form-check form-switch m-0">
                        
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       checked>
                        
                            </div>
                        
                        </div>

                    </div>

                </div>

                {{-- Hình ảnh --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-image text-primary me-2"></i>
                            Hình ảnh sản phẩm
                        </h5>

                        {{-- Ảnh hiện tại --}}
                        @if($product->image)

                        <div class="mb-3">

                            <img src="{{ asset('storage/'.$product->image) }}"
                                 class="img-fluid rounded-4 border shadow-sm w-100"
                                 style="height:250px; object-fit:cover;">

                        </div>

                        @endif

                        {{-- Upload --}}
                        <div class="border rounded-4 p-4 text-center bg-light">

                            <input type="file"
                                   name="image"
                                   class="form-control"
                                   accept=".png,.jpg,.jpeg">

                            <small class="text-muted d-block mt-2">
                                Hỗ trợ PNG, JPG, JPEG tối đa 2MB
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection