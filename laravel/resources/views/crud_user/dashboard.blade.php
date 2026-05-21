@extends('layout')
@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Users</h6>
                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Products</h6>
                    <h3 class="mb-0">{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Categories</h6>
                    <h3 class="mb-0">{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Posts</h6>
                    <h3 class="mb-0">{{ $totalPosts }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Coupons</h6>
                    <h3 class="mb-0">{{ $totalCoupons }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="mb-3">Truy cap nhanh</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Quan ly Products</a>
                <a href="{{ route('categories.index') }}" class="btn btn-success">Quan ly Categories</a>
                <a href="{{ route('posts.index') }}" class="btn btn-warning">Quan ly Posts</a>
                <a href="{{ route('coupons.index') }}" class="btn btn-info text-white">Quan ly Coupons</a>
                <a href="{{ route('user.listUser') }}" class="btn btn-dark">Quan ly Users</a>
            </div>
        </div>
    </div>
</div>
@endsection
