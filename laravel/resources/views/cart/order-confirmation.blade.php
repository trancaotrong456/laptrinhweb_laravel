@extends('layout')

@section('title', 'Xác nhận đơn hàng - Siêu thị trực tuyến')

@section('content')

<div class="container py-5">
    <div style="max-width:800px;margin:0 auto;">

        {{-- SUCCESS HEADER --}}
        <div style="text-align:center;margin-bottom:30px;">
            <i class="fas fa-check-circle" style="font-size:64px;color:#2e7d32;margin-bottom:16px;"></i>
            <h2 style="font-size:24px;font-weight:800;color:#212121;margin-bottom:8px;">Đặt hàng thành công!</h2>
            <p style="color:#757575;font-size:14px;">Cảm ơn bạn đã mua sắm tại Siêu thị trực tuyến. Đơn hàng của bạn
                đang được xử lý.</p>
        </div>

        {{-- ORDER CARD --}}
        <div class="card-white" style="padding:0;overflow:hidden;">
            <div style="background:#e8f5e9;padding:16px 24px;border-bottom:1px solid #c8e6c9;">
                <h5 style="margin:0;font-size:16px;font-weight:800;color:#2e7d32;">
                    <i class="fas fa-receipt me-2"></i> Chi tiết đơn hàng #{{ $order->order_number }}
                </h5>
            </div>

            <div style="padding:24px;">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div style="font-size:12.5px;color:#9e9e9e;font-weight:600;margin-bottom:4px;">Ngày đặt</div>
                        <div style="font-size:14px;font-weight:600;color:#424242;margin-bottom:16px;">
                            {{ $order->created_at->format('d/m/Y H:i') }}</div>

                        <div style="font-size:12.5px;color:#9e9e9e;font-weight:600;margin-bottom:4px;">Trạng thái</div>
                        <div style="margin-bottom:16px;">
                            @switch($order->status)
                            @case('pending') <span
                                style="background:#fff3e0;color:#ef6c00;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Chờ
                                xử lý</span> @break
                            @case('processing') <span
                                style="background:#e3f2fd;color:#1565c0;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Đang
                                xử lý</span> @break
                            @case('shipped') <span
                                style="background:#e8eaf6;color:#283593;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Đang
                                giao</span> @break
                            @case('delivered') <span
                                style="background:#e8f5e9;color:#2e7d32;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Đã
                                giao</span> @break
                            @default <span
                                style="background:#f5f5f5;color:#616161;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">Không
                                xác định</span>
                            @endswitch
                        </div>

                        <div style="font-size:12.5px;color:#9e9e9e;font-weight:600;margin-bottom:4px;">Phương thức thanh
                            toán</div>
                        <div>
                            @switch($order->payment_method)
                            @case('cod') <span style="color:#212121;font-weight:600;font-size:14px;"><i
                                    class="fas fa-money-bill-wave" style="color:#4caf50;"></i> Tiền mặt (COD)</span>
                            @break
                            @case('bank') <span style="color:#212121;font-weight:600;font-size:14px;"><i
                                    class="fas fa-university" style="color:#1976d2;"></i> Chuyển khoản</span> @break
                            @case('wallet') <span style="color:#212121;font-weight:600;font-size:14px;"><i
                                    class="fas fa-wallet" style="color:#e91e63;"></i> Ví điện tử</span> @break
                            @default <span style="color:#212121;font-weight:600;font-size:14px;">Khác</span>
                            @endswitch
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div style="font-size:14px;font-weight:800;color:#212121;margin-bottom:12px;">
                            <i class="fas fa-map-marker-alt" style="color:#e53935;"></i> Thông tin nhận hàng
                        </div>
                        <div
                            style="background:#f5f5f5;border-radius:8px;padding:12px 16px;font-size:13.5px;color:#424242;line-height:1.6;">
                            <strong>{{ $order->shipping_name }}</strong><br>
                            {{ $order->shipping_email }}<br>
                            {{ $order->shipping_address }}
                            @if($order->shipping_notes)
                            <div style="margin-top:8px;padding-top:8px;border-top:1px dashed #e0e0e0;">
                                <strong>Ghi chú:</strong> {{ $order->shipping_notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr style="border-color:#eeeeee;margin:24px 0;">

                {{-- ITEMS --}}
                <h6 style="font-size:14px;font-weight:800;color:#212121;margin-bottom:16px;">Sản phẩm đã đặt</h6>
                <div style="background:#fafafa;border-radius:10px;padding:16px;margin-bottom:24px;">
                    @foreach($order->items as $item)
                    <div
                        style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:{{ $loop->last ? 'none' : '1px dashed #e0e0e0' }};">
                        <div style="font-size:13.5px;font-weight:600;color:#424242;flex:1;padding-right:16px;">
                            {{ $item->product_name }} <span
                                style="color:#9e9e9e;font-weight:400;">x{{ $item->quantity }}</span>
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#212121;">
                            {{ number_format($item->product_price * $item->quantity) }}đ
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- TOTAL --}}
                <div style="max-width:300px;margin-left:auto;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13.5px;">
                        <span style="color:#757575;">Tạm tính:</span>
                        <span style="font-weight:600;">{{ number_format($order->subtotal) }}đ</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13.5px;">
                        <span style="color:#757575;">Giảm giá:</span>
                        <span
                            style="color:#e53935;font-weight:600;">-{{ number_format($order->discount_amount) }}đ</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;margin-bottom:16px;font-size:13.5px;">
                        <span style="color:#757575;">Vận chuyển:</span>
                        <span style="font-weight:600;">{{ number_format($order->shipping_fee) }}đ</span>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid #eeeeee;">
                        <span style="font-size:15px;font-weight:800;">Tổng cộng:</span>
                        <span
                            style="font-size:20px;font-weight:800;color:#e53935;">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div style="display:flex;gap:12px;justify-content:center;margin-top:24px;flex-wrap:wrap;">
            <a href="{{ route('home') }}" class="btn-green">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
            <a href="{{ route('orders.user') }}" class="btn-green-outline">
                <i class="fas fa-box"></i> Đơn hàng của tôi
            </a>
        </div>

    </div>
</div>

@endsection