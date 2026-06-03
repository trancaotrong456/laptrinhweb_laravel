@extends('layout')

@section('title', 'Quản trị - Dashboard')

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Admin Dashboard</span>
    </div>
</div>

<div class="container py-4">
    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 style="font-size:22px;font-weight:800;margin:0;">
            <i class="fas fa-chart-line" style="color:#2e7d32;"></i> Bảng Điều Khiển
        </h2>
    </div>

    <div class="row g-3">
        {{-- USERS --}}
        <div class="col-md-3 col-sm-6">
            <div class="card-white" style="text-align:center;padding:24px;">
                <div style="width:50px;height:50px;background:#e3f2fd;color:#1565c0;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px;">
                    <i class="fas fa-users"></i>
                </div>
                <div style="font-size:13px;color:#757575;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Người dùng</div>
                <div style="font-size:24px;font-weight:800;color:#212121;">{{ $totalUsers ?? 0 }}</div>
            </div>
        </div>

        {{-- PRODUCTS --}}
        <div class="col-md-3 col-sm-6">
            <div class="card-white" style="text-align:center;padding:24px;">
                <div style="width:50px;height:50px;background:#e8f5e9;color:#2e7d32;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px;">
                    <i class="fas fa-box-open"></i>
                </div>
                <div style="font-size:13px;color:#757575;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Sản phẩm</div>
                <div style="font-size:24px;font-weight:800;color:#212121;">{{ $totalProducts ?? 0 }}</div>
            </div>
        </div>

        {{-- CATEGORIES --}}
        <div class="col-md-3 col-sm-6">
            <div class="card-white" style="text-align:center;padding:24px;">
                <div style="width:50px;height:50px;background:#fff3e0;color:#ef6c00;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div style="font-size:13px;color:#757575;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Danh mục</div>
                <div style="font-size:24px;font-weight:800;color:#212121;">{{ $totalCategories ?? 0 }}</div>
            </div>
        </div>

        {{-- POSTS --}}
        <div class="col-md-3 col-sm-6">
            <div class="card-white" style="text-align:center;padding:24px;">
                <div style="width:50px;height:50px;background:#fce4ec;color:#c2185b;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px;">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div style="font-size:13px;color:#757575;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Khuyến mãi</div>
                <div style="font-size:24px;font-weight:800;color:#212121;">{{ $totalPosts ?? 0 }}</div>
            </div>
        </div>

        {{-- COUPONS --}}
        <div class="col-md-3 col-sm-6">
            <div class="card-white" style="text-align:center;padding:24px;">
                <div style="width:50px;height:50px;background:#f3e5f5;color:#7b1fa2;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px;">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div style="font-size:13px;color:#757575;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Voucher</div>
                <div style="font-size:24px;font-weight:800;color:#212121;">{{ $totalCoupons ?? 0 }}</div>
            </div>
        </div>
    </div>

    {{-- QUICK ACCESS --}}
    <div class="card-white mt-4">
        <h5 style="font-size:16px;font-weight:800;color:#212121;margin-bottom:16px;">
            <i class="fas fa-bolt" style="color:#fbc02d;"></i> Truy cập nhanh
        </h5>
        <div style="display:flex;flex-wrap:wrap;gap:12px;">
            <a href="{{ route('products.index') }}" class="btn-green-outline" style="background:#e8f5e9;color:#2e7d32;border-color:#c8e6c9;">
                <i class="fas fa-box-open"></i> Quản lý Sản phẩm
            </a>
            <a href="{{ route('categories.index') }}" class="btn-green-outline" style="background:#fff3e0;color:#ef6c00;border-color:#ffe0b2;">
                <i class="fas fa-layer-group"></i> Quản lý Danh mục
            </a>
            <a href="{{ route('posts.index') }}" class="btn-green-outline" style="background:#fce4ec;color:#c2185b;border-color:#f8bbd0;">
                <i class="fas fa-tags"></i> Quản lý Khuyến mãi
            </a>
            <a href="{{ route('coupons.index') }}" class="btn-green-outline" style="background:#f3e5f5;color:#7b1fa2;border-color:#e1bee7;">
                <i class="fas fa-percent"></i> Quản lý Voucher
            </a>
            <a href="{{ route('user.listUser') }}" class="btn-green-outline" style="background:#e3f2fd;color:#1565c0;border-color:#bbdefb;">
                <i class="fas fa-users"></i> Quản lý Người dùng
            </a>
        </div>
    </div>
</div>

@endsection