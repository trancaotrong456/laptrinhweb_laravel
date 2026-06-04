@extends('layouts.admin')

@section('title', 'Quản lý Voucher - TTP Admin')

@push('styles')
<style>
/* ── COUPON TABLE HEADER ───────────────────────────────── */
.cpn-table-header {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.cpn-col-id     { width: 50px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cpn-col-code   { width: 150px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cpn-col-val    { width: 140px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cpn-col-min    { flex: 1; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cpn-col-status { width: 110px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cpn-col-usage  { width: 100px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }
.cpn-col-action { width: 140px; flex-shrink: 0; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; }

/* ── COUPON ROW ────────────────────────────────────────── */
.cpn-row {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
    transition: background .15s;
}
.cpn-row:last-child { border-bottom: none; }
.cpn-row:hover { background: #f8faff; }

.cpn-row-id { width: 50px; flex-shrink: 0; font-size: 13.5px; font-weight: 600; color: #94a3b8; }
.cpn-row-code { width: 150px; flex-shrink: 0; font-size: 14px; font-weight: 700; color: #1e293b; letter-spacing: 0.5px; }
.cpn-row-code span { background: #f1f5f9; padding: 4px 10px; border-radius: 6px; border: 1.5px dashed #cbd5e1; }
.cpn-row-val { width: 140px; flex-shrink: 0; font-size: 14px; font-weight: 700; color: #ef4444; }
.cpn-row-min { flex: 1; min-width: 0; font-size: 13px; color: #64748b; }
.cpn-row-status { width: 110px; flex-shrink: 0; }
.cpn-row-usage { width: 100px; flex-shrink: 0; font-size: 13.5px; font-weight: 600; color: #475569; }
.cpn-row-action { width: 140px; flex-shrink: 0; display: flex; align-items: center; gap: 7px; justify-content: flex-end; }

/* ── EMPTY ─────────────────────────────────────────────── */
.empty-box {
    text-align: center; padding: 60px 20px; color: #94a3b8;
}
.empty-box i { font-size: 48px; margin-bottom: 14px; display: block; opacity: .5; }
.empty-box p { font-size: 14px; }
</style>
@endpush

@section('content')
@php
$savedCouponIds = [];
if (auth()->check()) {
    $savedCouponIds = \App\Models\UserSavedCoupon::where('user_id', auth()->id())
        ->pluck('coupon_id')
        ->map(fn ($id) => (int) $id)
        ->all();
}
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-ticket-alt" style="color:#2563eb;font-size:20px;"></i>
            Quản lý Voucher
        </h1>
        <div class="page-sub">Tổng cộng: <strong>{{ $coupons->total() ?? 0 }}</strong> voucher</div>
    </div>
    @if(auth()->check() && auth()->user()->role === 1)
    <a href="{{ route('coupons.create') }}" class="btn-primary-admin">
        <i class="fas fa-plus"></i> Thêm Voucher mới
    </a>
    @endif
</div>

{{-- SUCCESS --}}
@if(session('success'))
<div class="admin-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- TABLE --}}
<div class="admin-card" style="overflow:hidden;">
    {{-- Header --}}
    <div class="cpn-table-header">
        <div class="cpn-col-id">#</div>
        <div class="cpn-col-code">Mã Voucher</div>
        <div class="cpn-col-val">Giá trị</div>
        <div class="cpn-col-min">Đơn tối thiểu</div>
        <div class="cpn-col-status">Trạng thái</div>
        <div class="cpn-col-usage">Đã dùng</div>
        <div class="cpn-col-action">Thao tác</div>
    </div>

    {{-- Rows --}}
    @if($coupons->count() > 0)
    @foreach($coupons as $coupon)
    <div class="cpn-row">
        <div class="cpn-row-id">{{ $coupon->id }}</div>
        <div class="cpn-row-code">
            <span>{{ $coupon->code }}</span>
        </div>
        <div class="cpn-row-val">
            @if($coupon->type === 'percent')
            {{ rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') }}%
            @else
            {{ number_format($coupon->value) }}đ
            @endif
        </div>
        <div class="cpn-row-min">
            {{ $coupon->min_order_value ? number_format($coupon->min_order_value) . 'đ' : 'Không có' }}
        </div>
        <div class="cpn-row-status">
            @if($coupon->is_active)
            <span class="badge-status active"><i class="fas fa-check-circle" style="font-size:10px;"></i> Kích hoạt</span>
            @else
            <span class="badge-status error"><i class="fas fa-times-circle" style="font-size:10px;"></i> Vô hiệu</span>
            @endif
        </div>
        <div class="cpn-row-usage">
            {{ $coupon->used_count }}
            @if(!is_null($coupon->usage_limit))
            <span style="color:#94a3b8;font-weight:400;">/ {{ $coupon->usage_limit }}</span>
            @endif
        </div>
        <div class="cpn-row-action">
            @if(auth()->check() && auth()->user()->role === 1)
            <a href="{{ route('coupons.edit', $coupon) }}" class="icon-btn edit" title="Chỉnh sửa">
                <i class="fas fa-pen"></i>
            </a>
            @endif

            @php
            $isSaved = in_array($coupon->id, $savedCouponIds);
            @endphp

            @auth
            @if($isSaved)
            <form action="{{ route('coupons.unsave', $coupon) }}" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn" style="background:#fef3c7;color:#d97706;border-color:#fde68a;" title="Bỏ lưu mã này">
                    <i class="fas fa-bookmark"></i>
                </button>
            </form>
            @else
            <form action="{{ route('coupons.save', $coupon) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="icon-btn" style="background:#f1f5f9;color:#64748b;" title="Lưu mã vào ví">
                    <i class="far fa-bookmark"></i>
                </button>
            </form>
            @endif
            @endauth

            @if(auth()->check() && auth()->user()->role === 1)
            <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" style="margin:0;"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này?')">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn delete" title="Xóa voucher">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
    @else
    <div class="empty-box">
        <i class="fas fa-ticket-alt"></i>
        <p>Không có mã giảm giá nào</p>
    </div>
    @endif
</div>

{{-- PAGINATION --}}
@if(isset($coupons) && $coupons->hasPages())
<div style="margin-top:18px;display:flex;justify-content:center;">
    {{ $coupons->links('pagination::bootstrap-5') }}
</div>
@endif

@endsection