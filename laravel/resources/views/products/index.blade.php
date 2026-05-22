@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Danh sách sản phẩm
            </h1>

            <p class="text-secondary mb-0">
                Quản lý kho hàng và thông tin sản phẩm của hệ thống.
            </p>
        </div>

        <a
            href="{{ route('products.create') }}"
            class="btn btn-success px-4 py-3 rounded-4 shadow-sm fw-semibold"
        >
            + Thêm sản phẩm
        </a>

    </div>

    {{-- Alert --}}
    @if(session('success'))

        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>

    @endif

    {{-- Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- Top Filter --}}
        <div class="card-body border-bottom bg-white">

            <form
                method="GET"
                action="{{ route('products.index') }}"
                class="row g-3 align-items-center"
            >

                {{-- Search --}}
                <div class="col-md-5">

                    <div class="input-group">

                        <span class="input-group-text bg-light border-0 rounded-start-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                              </svg>
                        </span>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control border-0 bg-light rounded-end-4 py-3"
                            placeholder="Tìm kiếm sản phẩm..."
                            value="{{ request('keyword') }}"
                        >

                    </div>

                </div>

                {{-- Category --}}
                <div class="col-md-3">

                    <select
                        name="category"
                        class="form-select bg-light border-0 rounded-4 py-3"
                    >

                        <option value="">
                            Tất cả danh mục
                        </option>

                        @foreach($categories as $c)

                            <option
                                value="{{ $c->name }}"
                                {{ request('category') == $c->name ? 'selected' : '' }}
                            >
                                {{ $c->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Sort --}}
                <div class="col-md-3">

                    <select
                        name="sort"
                        class="form-select bg-light border-0 rounded-4 py-3"
                    >

                        <option value="latest">
                            Mới nhất
                        </option>

                        <option
                            value="price_asc"
                            {{ request('sort') == 'price_asc' ? 'selected' : '' }}
                        >
                            Giá tăng dần
                        </option>

                        <option
                            value="price_desc"
                            {{ request('sort') == 'price_desc' ? 'selected' : '' }}
                        >
                            Giá giảm dần
                        </option>

                    </select>

                </div>

                {{-- Button --}}
                <div class="col-md-1">

                    <button
                        type="submit"
                        class="btn btn-dark w-100 rounded-4 py-3"
                    >
                        Lọc
                    </button>

                </div>

            </form>

        </div>

        {{-- Table --}}
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="bg-light">

                    <tr>

                        <th class="py-3 ps-4">
                            Ảnh
                        </th>

                        <th>
                            Tên sản phẩm
                        </th>

                        <th>
                            Danh mục
                        </th>

                        <th>
                            Giá
                        </th>

                        <th>
                            Trạng thái
                        </th>

                        <th class="text-center">
                            Thao tác
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($products as $p)

                    <tr>

                        {{-- Image --}}
                        <td class="ps-4">

                            <img
                                src="{{ asset('storage/'.$p->image) }}"
                                width="65"
                                height="65"
                                class="rounded-4 object-fit-cover border"
                            >

                        </td>

                        {{-- Product --}}
                        <td>

                            <h5 class="fw-bold mb-1">
                                {{ $p->name }}
                            </h5>

                            <small class="text-secondary">
                                SKU: #{{ $p->id }}
                            </small>

                        </td>

                        {{-- Category --}}
                        <td>

                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">

                                {{ $p->category->name ?? 'Chưa có' }}

                            </span>

                        </td>

                        {{-- Price --}}
                        <td>

                            <span class="fw-bold text-primary fs-5">

                                {{ number_format($p->price) }}đ

                            </span>

                        </td>

                        {{-- Status --}}
                        <td>

                            @if($p->status == 'Còn hàng')

                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                    ● Còn hàng
                                </span>

                            @else

                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                    ● Hết hàng
                                </span>

                            @endif

                        </td>

                        {{-- Actions --}}
                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-2">

                                {{-- Detail --}}
                                <a
                                    href="{{ route('products.show', $p->id) }}"
                                    class="btn btn-light rounded-circle border"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                  </svg>
                                </a>

                                {{-- Edit --}}
                                <a
                                    href="{{ route('products.edit', $p->id) }}"
                                    class="btn btn-light rounded-circle border"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                  </svg>
                                </a>

                                {{-- Delete --}}
                                <form
                                    action="{{ route('products.destroy', $p->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-light rounded-circle border text-danger"
                                        onclick="return confirm('Xóa sản phẩm này?')"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                      </svg>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- Footer --}}
        <div class="d-flex justify-content-between align-items-center p-4">

            <div class="text-secondary">

                Hiển thị
                {{ $products->firstItem() }}
                -
                {{ $products->lastItem() }}

                của
                {{ $products->total() }}
                sản phẩm

            </div>

            <div>

                {{ $products->withQueryString()->links() }}

            </div>

        </div>

    </div>

</div>
@endsection