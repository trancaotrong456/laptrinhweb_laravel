@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - TTP Admin')

@push('styles')
<style>
/* ── ORDER STATS ─────────────────────────────────────── */
.order-stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}
@media (max-width: 1200px) { .order-stats-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 600px)  { .order-stats-grid { grid-template-columns: repeat(2, 1fr); } }

.order-stat {
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 12px; padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.order-stat-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.order-stat-info { min-width: 0; }
.order-stat-label { font-size: 11px; color: #64748b; font-weight: 600; white-space: nowrap; }
.order-stat-val { font-size: 22px; font-weight: 800; color: #1e293b; line-height: 1.2; }

/* ── REVENUE BOX ─────────────────────────────────────── */
.revenue-box {
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 12px; padding: 16px 22px;
    margin-bottom: 20px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.revenue-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg,#10b981,#059669);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff; flex-shrink: 0;
}
.revenue-val { font-size: 28px; font-weight: 800; color: #ef4444; }
.revenue-label { font-size: 12.5px; color: #64748b; }

/* ── FILTER TABS ─────────────────────────────────────── */
.order-tabs {
    display: flex; gap: 6px; flex-wrap: wrap;
    margin-bottom: 16px;
}
.order-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px;
    font-size: 13px; font-weight: 600;
    text-decoration: none; transition: all .18s;
    border: 1.5px solid #e2e8f0; background: #fff; color: #64748b;
}
.order-tab:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
.order-tab.active { background: #2563eb; color: #fff; border-color: #2563eb; }
.order-tab .tab-count {
    background: rgba(255,255,255,.25); padding: 1px 7px;
    border-radius: 50px; font-size: 11px;
}
.order-tab:not(.active) .tab-count { background: #f1f5f9; color: #94a3b8; }

/* ── SEARCH ROW ──────────────────────────────────────── */
.order-search-row {
    display: flex; gap: 10px; align-items: center;
    margin-bottom: 16px;
}
.order-search-input {
    flex: 1; height: 42px; padding: 0 16px;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 13.5px; font-family: inherit; color: #1e293b;
    background: #fff; outline: none; transition: all .2s;
}
.order-search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.order-search-btn {
    height: 42px; padding: 0 20px;
    background: #2563eb; color: #fff; border: none;
    border-radius: 9px; font-size: 13.5px; font-weight: 600;
    cursor: pointer; display: flex; align-items: center; gap: 7px;
    transition: all .2s; font-family: inherit;
}
.order-search-btn:hover { background: #1d4ed8; }
.order-clear-btn {
    height: 42px; padding: 0 16px;
    background: #fff; color: #64748b;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 13.5px; font-weight: 500; cursor: pointer;
    display: flex; align-items: center; gap: 7px; text-decoration: none;
    transition: all .2s; font-family: inherit;
}
.order-clear-btn:hover { border-color: #ef4444; color: #ef4444; }

/* ── TABLE ───────────────────────────────────────────── */
.order-table { width: 100%; border-collapse: collapse; }
.order-table thead th {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #64748b;
    padding: 12px 16px; background: #f8fafc;
    border-bottom: 1px solid #e2e8f0; white-space: nowrap;
}
.order-table tbody td {
    padding: 14px 16px; font-size: 13.5px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.order-table tbody tr:last-child td { border-bottom: none; }
.order-table tbody tr:hover td { background: #fafbff; }

.order-code { font-size: 14px; font-weight: 700; color: #1e293b; }
.order-items-count { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.order-customer-name { font-size: 14px; font-weight: 600; color: #2563eb; }
.order-customer-phone { font-size: 12px; color: #94a3b8; margin-top: 1px; }
.order-customer-points { font-size: 12px; color: #64748b; margin-top: 1px; }
.order-total { font-size: 14px; font-weight: 700; color: #ef4444; }

/* ── ACTION BUTTONS ──────────────────────────────────── */
.action-cell { display: flex; align-items: center; gap: 7px; }
.btn-action {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 7px; border: none;
    font-size: 12.5px; font-weight: 600; cursor: pointer;
    text-decoration: none; transition: all .18s; white-space: nowrap;
    font-family: inherit;
}
.btn-action.packing  { background: #dbeafe; color: #1e40af; }
.btn-action.packing:hover  { background: #bfdbfe; }
.btn-action.shipping { background: #ede9fe; color: #5b21b6; }
.btn-action.shipping:hover { background: #ddd6fe; }
.btn-action.view     { background: #f1f5f9; color: #475569; }
.btn-action.view:hover     { background: #e2e8f0; }
.btn-action.delivered { background: #d1fae5; color: #065f46; }
.btn-action.delivered:hover { background: #a7f3d0; }
.btn-action-more {
    width: 30px; height: 30px; border-radius: 7px;
    border: 1.5px solid #e2e8f0; background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #64748b; transition: all .18s;
}
.btn-action-more:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-shopping-bag" style="color:#2563eb;font-size:20px;"></i>
        Quản lý đơn hàng
    </h1>
</div>

{{-- SUCCESS --}}
@if(session('success'))
<div class="admin-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="admin-alert error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif

{{-- ORDER STATS --}}
@php
    $statusConfig = [
        'all'       => ['label' => 'Tổng đơn',   'icon' => 'fas fa-list',         'color' => '#2563eb', 'bg' => '#eff6ff'],
        'pending'   => ['label' => 'Đã đặt',      'icon' => 'fas fa-clock',         'color' => '#92400e','bg' => '#fef3c7'],
        'packing'   => ['label' => 'Đóng gói',    'icon' => 'fas fa-box',           'color' => '#1e40af','bg' => '#dbeafe'],
        'shipping'  => ['label' => 'Đang giao',   'icon' => 'fas fa-truck',         'color' => '#5b21b6','bg' => '#ede9fe'],
        'delivered' => ['label' => 'Hoàn thành',  'icon' => 'fas fa-check-circle',  'color' => '#065f46','bg' => '#d1fae5'],
        'cancelled' => ['label' => 'Đã hủy',      'icon' => 'fas fa-times-circle',  'color' => '#991b1b','bg' => '#fee2e2'],
    ];
@endphp
<div class="order-stats-grid">
    @foreach($statusConfig as $key => $cfg)
    <div class="order-stat">
        <div class="order-stat-icon" style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};">
            <i class="{{ $cfg['icon'] }}"></i>
        </div>
        <div class="order-stat-info">
            <div class="order-stat-label">{{ $cfg['label'] }}</div>
            <div class="order-stat-val">{{ $orderCounts[$key] ?? 0 }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- REVENUE BOX --}}
<div class="revenue-box">
    <div class="revenue-icon"><i class="fas fa-coins"></i></div>
    <div>
        <div class="revenue-val">{{ number_format($totalRevenue) }}đ</div>
        <div class="revenue-label">Tổng doanh thu (đơn hoàn thành)</div>
    </div>
</div>

{{-- FILTER TABS --}}
<div class="order-tabs">
    <a href="{{ route('admin.orders.index') }}"
        class="order-tab {{ !request('status') ? 'active' : '' }}">
        Tất cả <span class="tab-count">{{ $orderCounts['all'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
        class="order-tab {{ request('status') === 'pending' ? 'active' : '' }}">
        <i class="fas fa-clock" style="font-size:11px;"></i> Đã đặt
        <span class="tab-count">{{ $orderCounts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'packing']) }}"
        class="order-tab {{ request('status') === 'packing' ? 'active' : '' }}">
        <i class="fas fa-box" style="font-size:11px;"></i> Đóng gói
        <span class="tab-count">{{ $orderCounts['packing'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}"
        class="order-tab {{ request('status') === 'shipping' ? 'active' : '' }}">
        <i class="fas fa-truck" style="font-size:11px;"></i> Đang giao
        <span class="tab-count">{{ $orderCounts['shipping'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
        class="order-tab {{ request('status') === 'delivered' ? 'active' : '' }}">
        <i class="fas fa-check-circle" style="font-size:11px;"></i> Hoàn thành
        <span class="tab-count">{{ $orderCounts['delivered'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
        class="order-tab {{ request('status') === 'cancelled' ? 'active' : '' }}">
        <i class="fas fa-times-circle" style="font-size:11px;"></i> Đã hủy
        <span class="tab-count">{{ $orderCounts['cancelled'] }}</span>
    </a>
</div>

{{-- SEARCH --}}
<form action="{{ route('admin.orders.index') }}" method="GET" class="order-search-row">
    @if(request('status'))
    <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <input type="text" name="search" class="order-search-input"
        placeholder="Tìm tên, SĐT, email..." value="{{ request('search') }}">
    <button type="submit" class="order-search-btn">
        <i class="fas fa-search"></i> Tìm
    </button>
    @if(request('search') || request('status'))
    <a href="{{ route('admin.orders.index') }}" class="order-clear-btn">
        Xóa lọc
    </a>
    @endif
    <span style="font-size:13px;color:#94a3b8;margin-left:4px;flex-shrink:0;">
        Hiển thị {{ $orders->count() }} đơn
    </span>
</form>

{{-- TABLE --}}
<div class="admin-card" style="overflow:hidden;">
    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th style="text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $st = $statusConfig[$order->status] ?? $statusConfig['pending'];
                @endphp
                <tr>
                    {{-- Mã đơn --}}
                    <td>
                        <div class="order-code">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="order-items-count">
                            {{ $order->items->count() ?? 0 }} sản phẩm
                        </div>
                    </td>

                    {{-- Khách hàng --}}
                    <td>
                        <div class="order-customer-name">{{ $order->user->name ?? ($order->name ?? 'Khách') }}</div>
                        <div class="order-customer-phone">{{ $order->user->phone ?? $order->phone ?? '' }}</div>
                        <div class="order-customer-points">{{ number_format($order->total / 1000) }} uyên lẻng</div>
                    </td>

                    {{-- Ngày đặt --}}
                    <td style="font-size:13px;color:#64748b;">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}<br>
                        <span style="font-size:12px;">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</span>
                    </td>

                    {{-- Tổng tiền --}}
                    <td><span class="order-total">{{ number_format($order->total) }}đ</span></td>

                    {{-- Trạng thái --}}
                    <td>
                        <span class="badge-status {{ $order->status === 'delivered' ? 'delivered' : ($order->status === 'cancelled' ? 'cancelled' : ($order->status === 'shipping' ? 'shipping' : ($order->status === 'packing' ? 'packing' : 'pending'))) }}">
                            {{ $st['label'] }}
                        </span>
                    </td>

                    {{-- Thao tác --}}
                    <td>
                        <div class="action-cell" style="justify-content:center;">
                            @if($order->status === 'pending')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="packing">
                                <button type="submit" class="btn-action packing">
                                    <i class="fas fa-box"></i> Đóng gói
                                </button>
                            </form>
                            @elseif($order->status === 'packing')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="shipping">
                                <button type="submit" class="btn-action shipping">
                                    <i class="fas fa-truck"></i> Giao hàng
                                </button>
                            </form>
                            @elseif($order->status === 'shipping')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="btn-action delivered">
                                    <i class="fas fa-check"></i> Hoàn thành
                                </button>
                            </form>
                            @elseif($order->status === 'delivered')
                            <span class="btn-action delivered" style="cursor:default;">
                                <i class="fas fa-check-circle"></i> Đã hoàn thành
                            </span>
                            @else
                            <span class="btn-action view" style="cursor:default;">
                                <i class="fas fa-ban"></i> Đã hủy
                            </span>
                            @endif
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action-more" title="Xem chi tiết">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:60px 20px;color:#94a3b8;">
                        <i class="fas fa-shopping-bag" style="font-size:48px;display:block;margin-bottom:14px;opacity:.3;"></i>
                        <p style="font-size:14px;">Không có đơn hàng nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
@if($orders->hasPages())
<div style="margin-top:18px;display:flex;justify-content:center;">
    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endif

@endsection
