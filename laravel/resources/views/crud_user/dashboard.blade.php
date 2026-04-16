@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div
    style="max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #333;">HỆ THỐNG QUẢN TRỊ</h2>
    <hr>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
        <div style="background: #e1f5fe; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; color: #0288d1;">{{ $totalUsers }}</h3>
            <p style="margin: 5px 0 0;">Người dùng</p>
        </div>
        <div style="background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; color: #f57c00;">{{ $totalProducts }}</h3>
            <p style="margin: 5px 0 0;">Sản phẩm</p>
        </div>
        <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; color: #388e3c;">{{ $totalCategories }}</h3>
            <p style="margin: 5px 0 0;">Danh mục</p>
        </div>
        <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; color: #7b1fa2;">{{ $totalPosts }}</h3>
            <p style="margin: 5px 0 0;">Bài viết</p>
        </div>
    </div>
</div>
@endsection