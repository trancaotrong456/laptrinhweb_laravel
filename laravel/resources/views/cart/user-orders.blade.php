@extends('layout')

@section('title', 'Đơn hàng của tôi - Siêu thị trực tuyến')

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Đơn hàng của tôi</span>
    </div>
</div>

<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 style="font-size:22px;font-weight:800;margin:0;">
            <i class="fas fa-box" style="color:#2e7d32;"></i> Đơn hàng của tôi
        </h2>
        <a href="{{ route('products.all') }}" class="btn-green-outline">
            <i class="fas fa-store"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
    <div class="alert-st success mb-4"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert-st error mb-4"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @forelse($orders as $order)
    <div class="card-white mb-3" style="padding:20px;">
        <div class="row align-items-center g-3">
            <div class="col-6 col-md-3">
                <div style="font-size:12px;color:#9e9e9e;font-weight:600;text-transform:uppercase;">Mã đơn hàng</div>
                <div style="font-size:15px;font-weight:800;color:#212121;">{{ $order->order_number }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size:12px;color:#9e9e9e;font-weight:600;text-transform:uppercase;">Ngày đặt</div>
                <div style="font-size:14px;color:#424242;font-weight:500;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-6 col-md-2">
                <div style="font-size:12px;color:#9e9e9e;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Trạng thái</div>
                @switch($order->status)
                    @case('pending')
                        <span style="background:#fff3e0;color:#ef6c00;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Chờ xử lý</span>
                        @break
                    @case('processing')
                        <span style="background:#e3f2fd;color:#1565c0;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Đang xử lý</span>
                        @break
                    @case('shipped')
                        <span style="background:#e8eaf6;color:#283593;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Đang giao</span>
                        @break
                    @case('delivered')
                        <span style="background:#e8f5e9;color:#2e7d32;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Đã giao</span>
                        @break
                    @default
                        <span style="background:#f5f5f5;color:#616161;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Không xác định</span>
                @endswitch
            </div>
            <div class="col-6 col-md-2">
                <div style="font-size:12px;color:#9e9e9e;font-weight:600;text-transform:uppercase;">Tổng tiền</div>
                <div style="font-size:15px;font-weight:800;color:#e53935;">{{ number_format($order->total) }}đ</div>
            </div>
            <div class="col-12 col-md-2 text-md-end">
                <a href="{{ route('order.detail', ['orderNumber' => $order->order_number]) }}" class="btn-green-outline" style="padding:8px 16px;font-size:13px;width:100%;">
                    Chi tiết <i class="fas fa-chevron-right" style="font-size:10px;margin-left:4px;"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h5>Chưa có đơn hàng nào</h5>
        <p style="font-size:13.5px;">Bạn chưa thực hiện đơn hàng nào trên hệ thống.</p>
        <a href="{{ route('products.all') }}" class="btn-green mt-2">
            Mua sắm ngay
        </a>
    </div>
    @endforelse

    @if($orders->hasPages())
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endif

</div>

@endsection