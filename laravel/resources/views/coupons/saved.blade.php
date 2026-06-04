@extends('layout')

@section('title', 'Mã giảm giá đã lưu - Siêu thị trực tuyến')

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Mã giảm giá của tôi</span>
    </div>
</div>

<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 style="font-size:22px;font-weight:800;margin:0;">
            <i class="fas fa-ticket-alt" style="color:#2e7d32;"></i> Voucher của tôi
        </h2>
        <a href="{{ route('posts.index') }}" class="btn-green-outline">
            Săn thêm mã
        </a>
    </div>

    @if(session('success'))
    <div class="alert-st success mb-4"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    
    @if(session('error'))
    <div class="alert-st error mb-4"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="row g-3">
        @forelse($coupons as $coupon)
        <div class="col-md-6 col-lg-4">
            <div class="card-white" style="padding:0;overflow:hidden;display:flex;height:100%;">
                <div style="background:linear-gradient(135deg,#2e7d32,#43a047);width:100px;display:flex;flex-direction:column;
                            align-items:center;justify-content:center;color:white;padding:16px;text-align:center;">
                    <i class="fas fa-gift" style="font-size:28px;margin-bottom:8px;"></i>
                    <div style="font-size:12px;font-weight:700;opacity:.9;">MÃ GIẢM</div>
                </div>
                <div style="padding:16px;flex:1;display:flex;flex-direction:column;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                        <h5 style="margin:0;font-size:18px;font-weight:800;color:#212121;letter-spacing:1px;">{{ $coupon->code }}</h5>
                        @if($coupon->type === 'percent')
                        <span style="background:#ffebee;color:#c62828;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:800;">
                            -{{ rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') }}%
                        </span>
                        @else
                        <span style="background:#ffebee;color:#c62828;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:800;">
                            -{{ number_format($coupon->value) }}đ
                        </span>
                        @endif
                    </div>

                    <p style="font-size:12.5px;color:#757575;margin:0 0 6px;">
                        Đơn tối thiểu: <strong style="color:#424242;">{{ $coupon->min_order_value ? number_format($coupon->min_order_value) . 'đ' : '0đ' }}</strong>
                    </p>

                    @if($coupon->ends_at)
                    <p style="font-size:12.5px;color:#757575;margin:0 0 12px;">HSD: {{ $coupon->ends_at->format('d/m/Y H:i') }}</p>
                    @else
                    <p style="font-size:12.5px;color:#757575;margin:0 0 12px;">HSD: Không giới hạn</p>
                    @endif

                    <div style="margin-top:auto;">
                        <form method="POST" action="{{ route('coupons.unsave', $coupon) }}">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#9e9e9e;font-size:12px;font-weight:600;padding:0;text-decoration:underline;">
                                Bỏ lưu mã này
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-ticket-alt"></i>
                <h5>Kho voucher trống</h5>
                <p style="font-size:13.5px;">Bạn chưa lưu mã giảm giá nào. Hãy lấy thêm mã để mua sắm tiết kiệm hơn.</p>
                <a href="{{ route('posts.index') }}" class="btn-green mt-2">Săn voucher ngay</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>

</div>

@endsection