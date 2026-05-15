
@extends('layouts.app')

@section('content')
<div class="product-detail-container">

    <div class="product-card">

        {{-- Hình ảnh --}}
        <div class="product-image">

            <img
                src="{{ asset('storage/'.$product->image) }}"
                alt="{{ $product->name }}"
            >

        </div>

        {{-- Thông tin --}}
        <div class="product-info">

            <h2>
                {{ $product->name }}
            </h2>

            <div class="info-item">

                <span class="label">
                    Giá:
                </span>

                <span class="value price">
                    {{ number_format($product->price) }} đ
                </span>

            </div>

            <div class="info-item">

                <span class="label">
                    Số lượng:
                </span>

                <span class="value">
                    {{ $product->quantity }}
                </span>

            </div>

            <div class="info-item">

                <span class="label">
                    Trạng thái:
                </span>

                <span
                    class="
                        status-badge
                        {{ $product->status == 'Còn hàng'
                            ? 'in-stock'
                            : 'out-stock'
                        }}
                    "
                >
                    {{ $product->status }}
                </span>

            </div>

            <div class="button-group">

                <a
                    href="{{ route('products.edit', $product->id) }}"
                    class="btn-edit"
                >
                    Sửa sản phẩm
                </a>

                <a
                    href="{{ route('products.index') }}"
                    class="btn-back"
                >
                    Quay lại
                </a>

            </div>

        </div>

    </div>

</div>

@endsection

