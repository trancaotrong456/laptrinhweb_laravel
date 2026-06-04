@extends('layout')

@section('content')
<div class="product-detail-container">
    <div class="product-card">
        <div class="product-image">
            @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
            <div class="no-img"><i class="fas fa-image"></i></div>
            @endif
        </div>

        <div class="product-info">
            <h2>{{ $product->name }}</h2>

            <div class="info-item">
                <span class="label">Danh muc:</span>
                <span class="value">{{ $product->category->name ?? 'Chua phan loai' }}</span>
            </div>

            <div class="info-item">
                <span class="label">Gia:</span>
                <span class="value price">{{ number_format($product->price) }} d</span>
            </div>

            <div class="info-item">
                <span class="label">So luong:</span>
                <span class="value">{{ $product->quantity }}</span>
            </div>

            <div class="info-item">
                <span class="label">Trang thai:</span>
                <span class="status-badge {{ (int) $product->quantity > 0 ? 'in-stock' : 'out-stock' }}">
                    {{ (int) $product->quantity > 0 ? 'Con hang' : 'Het hang' }}
                </span>
            </div>

            @if($product->description)
            <div class="info-item">
                <span class="label">Mo ta:</span>
                <span class="value">{{ $product->description }}</span>
            </div>
            @endif

            <div class="button-group">
                <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">Sua san pham</a>
                <a href="{{ route('products.index') }}" class="btn-back">Quay lai</a>
            </div>
        </div>
    </div>
</div>
@endsection
