@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            Thêm sản phẩm mới
        </h2>

        <p class="text-secondary">
            Vui lòng điền đầy đủ thông tin để cập nhật vào kho hàng.
        </p>

    </div>

    {{-- Card --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            {{-- Error --}}
            @if ($errors->any())

                <div class="alert alert-danger rounded-4">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('products.store') }}"
            >

                @csrf

                {{-- Product Name --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Tên sản phẩm
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control form-control-lg rounded-4 bg-light border-0"
                        placeholder="Nhập tên sản phẩm..."
                    >

                </div>

                {{-- Price + Quantity --}}
                <div class="row">

                    {{-- Price --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">
                            Giá bán (VNĐ)
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light border-0 rounded-start-4">
                                💰
                            </span>

                            <input
                                type="text"
                                name="price"
                                class="form-control form-control-lg bg-light border-0 rounded-end-4"
                                placeholder="0.000"
                            >

                        </div>

                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">
                            Số lượng tồn kho
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light border-0 rounded-start-4">
                                📦
                            </span>

                            <input
                                type="text"
                                name="quantity"
                                class="form-control form-control-lg bg-light border-0 rounded-end-4"
                                placeholder="0"
                            >

                        </div>

                    </div>

                </div>

                {{-- Category --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Danh mục sản phẩm
                    </label>

                    <select
                        name="category_id"
                        class="form-select form-select-lg rounded-4 bg-light border-0"
                    >

                        <option value="">
                            Chọn danh mục...
                        </option>

                        @foreach ($categories as $cate)

                            <option value="{{ $cate->id }}">

                                {{ $cate->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Description --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Mô tả sản phẩm
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control rounded-4 bg-light border-0"
                        placeholder="Viết vài dòng mô tả về sản phẩm của bạn..."
                    ></textarea>

                </div>

                {{-- Upload --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Hình ảnh sản phẩm
                    </label>

                    <div class="row g-4 align-items-center">

                        {{-- Upload Box --}}
                        <div class="col-md-8">

                            <label
                                for="imageUpload"
                                class="border border-2 border-dashed rounded-4 p-5 text-center w-100 bg-light cursor-pointer"
                                style="cursor:pointer;"
                            >

                                <div class="fs-1 mb-2">
                                    ☁️
                                </div>

                                <h6 class="fw-bold">
                                    Nhấn để tải lên hoặc kéo thả
                                </h6>

                                <small class="text-secondary">
                                    PNG, JPG, JPEG tối đa 2MB
                                </small>

                                <input
                                    type="file"
                                    id="imageUpload"
                                    name="image"
                                    class="d-none"
                                    accept=".png,.jpg,.jpeg"
                                >

                            </label>

                        </div>

                        {{-- Preview --}}
                        <div class="col-md-4">

                            <img
                                id="previewImage"
                                src="https://via.placeholder.com/250x180?text=Preview"
                                class="img-fluid rounded-4 shadow-sm border"
                            >

                        </div>

                    </div>

                </div>

                {{-- Footer Buttons --}}
                <div class="d-flex justify-content-end gap-3 border-top pt-4">

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-light px-4 py-2 rounded-4"
                    >
                        Hủy
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary px-4 py-2 rounded-4 shadow-sm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                            <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                        </svg>
                        Lưu sản phẩm
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Preview Image --}}
<script>

    const imageUpload =
        document.getElementById('imageUpload');

    const previewImage =
        document.getElementById('previewImage');

    imageUpload.addEventListener('change', function(e){

        const file = e.target.files[0];

        if(file){

            previewImage.src =
                URL.createObjectURL(file);

        }

    });

</script>


@endsection

