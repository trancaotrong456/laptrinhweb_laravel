@extends('layouts.admin')

@section('title', 'Dashboard - TTP Admin')

@push('styles')
<style>
/* ── STAT CARDS ROW ─────────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}
@media (max-width: 991px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px) { .stats-grid { grid-template-columns: 1fr; } }

.stat-card-v2 {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 22px 20px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    transition: all .22s ease;
}
.stat-card-v2:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
.stat-card-icon {
    width: 54px; height: 54px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.stat-card-info { flex: 1; min-width: 0; }
.stat-card-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
.stat-card-value { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1.15; margin-top: 2px; }
.stat-card-sub { font-size: 12px; color: #94a3b8; margin-top: 2px; }

/* Colors */
.ic-blue   { background: #eff6ff; color: #2563eb; }
.ic-green  { background: #f0fdf4; color: #16a34a; }
.ic-purple { background: #faf5ff; color: #7c3aed; }
.ic-orange { background: #fff7ed; color: #ea580c; }

/* ── CHARTS ROW ─────────────────────────────────────── */
.charts-row {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 18px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) { .charts-row { grid-template-columns: 1fr; } }

/* ── BOTTOM ROW ─────────────────────────────────────── */
.bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
@media (max-width: 900px) { .bottom-row { grid-template-columns: 1fr; } }

/* Top products */
.top-product-item {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}
.top-product-item:last-child { border-bottom: none; }
.top-rank {
    width: 24px; text-align: center;
    font-size: 15px; font-weight: 800; color: #cbd5e1;
    flex-shrink: 0;
}
.top-rank.r1 { color: #f59e0b; }
.top-rank.r2 { color: #94a3b8; }
.top-rank.r3 { color: #b45309; }
.top-product-img {
    width: 44px; height: 44px; border-radius: 10px;
    object-fit: cover; border: 1px solid #e2e8f0;
    background: #f8fafc; flex-shrink: 0;
}
.top-product-info { flex: 1; min-width: 0; }
.top-product-name {
    font-size: 13.5px; font-weight: 600; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.top-product-bar-wrap {
    height: 4px; background: #f1f5f9; border-radius: 2px; margin-top: 6px;
}
.top-product-bar { height: 4px; border-radius: 2px; }
.top-product-sold { font-size: 11px; color: #94a3b8; margin-top: 2px; }
.top-product-price { font-size: 13.5px; font-weight: 700; color: #ef4444; white-space: nowrap; flex-shrink: 0; }

/* Recent orders */
.order-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 0; border-bottom: 1px solid #f1f5f9; gap: 10px;
}
.order-row:last-child { border-bottom: none; }
.order-code { font-size: 13.5px; font-weight: 700; color: #1e293b; }
.order-meta { font-size: 11.5px; color: #94a3b8; margin-top: 2px; }
.order-amount { font-size: 14px; font-weight: 700; color: #ef4444; flex-shrink: 0; }
.order-link {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    background: #f8fafc; color: #94a3b8; font-size: 12px;
    text-decoration: none; transition: all .2s;
    flex-shrink: 0; border: 1px solid #e2e8f0;
}
.order-link:hover { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }

.link-view-all {
    font-size: 13px; font-weight: 600; color: #2563eb;
    text-decoration: none;
}
.link-view-all:hover { text-decoration: underline; }

/* Pending alert */
.pending-alert {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fef3c7; color: #92400e;
    border: 1px solid #fde68a;
    padding: 8px 16px; border-radius: 50px;
    font-size: 13px; font-weight: 600;
    text-decoration: none;
}
.pending-alert:hover { background: #fde68a; color: #78350f; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt" style="color:#2563eb;font-size:20px;"></i>
            Dashboard
        </h1>
        <div class="page-sub">Hôm nay: {{ now()->format('d/m/Y') }}</div>
    </div>
    <div class="d-flex align-items-center gap-3">
        @if($pendingOrdersCount > 0)
        <a href="{{ route('admin.orders.index') }}" class="pending-alert">
            <i class="fas fa-bell"></i>
            {{ $pendingOrdersCount }} đơn chờ xử lý
        </a>
        @endif
        <a href="{{ route('home') }}" class="btn-outline-admin">
            <i class="fas fa-external-link-alt"></i> Xem trang chủ
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card-v2">
        <div class="stat-card-icon ic-blue">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-label">Đơn hôm nay</div>
            <div class="stat-card-value">{{ $ordersToday }}</div>
            <div class="stat-card-sub">đơn mới trong ngày</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-card-icon ic-green">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-label">Doanh thu tháng</div>
            <div class="stat-card-value">{{ number_format($revenueThisMonth / 1000000, 1) }}M</div>
            <div class="stat-card-sub">{{ number_format($revenueThisMonth) }}đ</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-card-icon ic-purple">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-label">Khách hàng</div>
            <div class="stat-card-value">{{ $totalUsers }}</div>
            <div class="stat-card-sub">tài khoản đã đăng ký</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-card-icon ic-orange">
            <i class="fas fa-box-open"></i>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-label">Sản phẩm</div>
            <div class="stat-card-value">{{ $totalProducts }}</div>
            <div class="stat-card-sub">sản phẩm trong kho</div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="charts-row">
    {{-- Line Chart --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h6 class="admin-card-title">
                <i class="fas fa-chart-line" style="color:#2563eb;"></i>
                Doanh thu 14 ngày (đơn hoàn thành)
            </h6>
            <span style="font-size:12px;color:#94a3b8;">triệu đ</span>
        </div>
        <div class="admin-card-body" style="padding:16px 18px 18px;">
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Donut Chart --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h6 class="admin-card-title">
                <i class="fas fa-chart-pie" style="color:#7c3aed;"></i>
                Doanh thu theo danh mục
            </h6>
        </div>
        <div class="admin-card-body" style="text-align:center;">
            <div style="position: relative; height: 260px; width: 100%; display: flex; justify-content: center;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div style="margin-top:16px;text-align:left;">
                @php
                    $catColors = ['#2563eb','#7c3aed','#10b981','#f59e0b','#ef4444','#06b6d4'];
                    $ci = 0;
                @endphp
                @foreach($categoryRevenue as $catName => $catRev)
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:7px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:10px;height:10px;border-radius:3px;background:{{ $catColors[$ci % count($catColors)] }};display:inline-block;flex-shrink:0;"></span>
                        <span style="font-size:12.5px;color:#1e293b;font-weight:500;">{{ $catName }}</span>
                    </div>
                    <span style="font-size:12.5px;color:#64748b;font-weight:600;">
                        {{ number_format($catRev/1000000, 1) }}M
                        @if($totalDeliveredRevenue > 0)
                        ({{ round($catRev / $totalDeliveredRevenue * 100) }}%)
                        @endif
                    </span>
                </div>
                @php $ci++; @endphp
                @endforeach
                @if(empty($categoryRevenue))
                <div style="text-align:center;color:#94a3b8;font-size:13px;padding:12px 0;">Chưa có dữ liệu</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- BOTTOM ROW --}}
<div class="bottom-row">
    {{-- Top Products --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h6 class="admin-card-title">
                <i class="fas fa-fire" style="color:#f59e0b;"></i>
                Top sản phẩm bán chạy
            </h6>
            <a href="{{ route('products.index') }}" class="link-view-all">Quản lý SP →</a>
        </div>
        <div class="admin-card-body">
            @php
                $barColors = ['#2563eb','#7c3aed','#10b981','#f59e0b','#ef4444'];
                $maxSold = $topProducts->max('total_sold') ?: 1;
            @endphp
            @forelse($topProducts as $i => $item)
            @php
                $imgSrc = null;
                if (!empty($item->image)) {
                    $imgSrc = str_contains($item->image, '/')
                        ? asset('storage/' . $item->image)
                        : asset('images/' . $item->image);
                }
            @endphp
            <div class="top-product-item">
                <div class="top-rank {{ $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : '')) }}">
                    {{ $i + 1 }}
                </div>
                @if($imgSrc)
                    <img class="top-product-img" src="{{ $imgSrc }}" alt="{{ $item->product_name }}"
                        onerror="this.style.display='none'">
                @else
                    <div class="top-product-img" style="display:flex;align-items:center;justify-content:center;color:#cbd5e1;">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
                <div class="top-product-info">
                    <div class="top-product-name">{{ $item->product_name }}</div>
                    <div class="top-product-bar-wrap">
                        <div class="top-product-bar"
                            style="width:{{ round($item->total_sold / $maxSold * 100) }}%;background:{{ $barColors[$i % count($barColors)] }};"></div>
                    </div>
                    <div class="top-product-sold">{{ $item->total_sold }} bán</div>
                </div>
                <div class="top-product-price">{{ number_format($item->product_price * $item->total_sold) }}đ</div>
            </div>
            @empty
            <div style="text-align:center;padding:32px 0;color:#94a3b8;">
                <i class="fas fa-box-open" style="font-size:32px;margin-bottom:10px;display:block;"></i>
                Chưa có dữ liệu bán hàng
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h6 class="admin-card-title">
                <i class="fas fa-clock" style="color:#2563eb;"></i>
                Đơn hàng gần đây
            </h6>
            <a href="{{ route('admin.orders.index') }}" class="link-view-all">Xem tất cả →</a>
        </div>
        <div class="admin-card-body">
            @php
                $statusMap = [
                    'pending'   => ['label' => 'Đã đặt',    'class' => 'pending'],
                    'packing'   => ['label' => 'Đóng gói',  'class' => 'packing'],
                    'shipping'  => ['label' => 'Đang giao', 'class' => 'shipping'],
                    'delivered' => ['label' => 'Hoàn thành','class' => 'delivered'],
                    'cancelled' => ['label' => 'Đã hủy',   'class' => 'cancelled'],
                ];
            @endphp
            @forelse($recentOrders as $order)
            @php
                $st = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'pending'];
            @endphp
            <div class="order-row">
                <div>
                    <div class="order-code">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        <span class="badge-status {{ $st['class'] }}" style="font-size:10.5px;padding:2px 8px;">
                            {{ $st['label'] }}
                        </span>
                    </div>
                    <div class="order-meta">
                        {{ $order->user->name ?? 'Khách' }} &middot;
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m H:i') }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="order-amount">{{ number_format($order->total) }}đ</div>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="order-link">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:32px 0;color:#94a3b8;">
                <i class="fas fa-shopping-bag" style="font-size:32px;margin-bottom:10px;display:block;"></i>
                Chưa có đơn hàng nào
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── LINE CHART ───────────────────────────────────────────
const labels14 = @json($labels14Days);
const revenue14 = @json(array_map(fn($v) => round($v / 1000000, 2), $revenue14Days));

const ctx1 = document.getElementById('revenueChart').getContext('2d');
const gradient = ctx1.createLinearGradient(0, 0, 0, 220);
gradient.addColorStop(0, 'rgba(37,99,235,0.18)');
gradient.addColorStop(1, 'rgba(37,99,235,0)');

new Chart(ctx1, {
    type: 'line',
    data: {
        labels: labels14,
        datasets: [{
            label: 'Doanh thu (triệu đ)',
            data: revenue14,
            borderColor: '#2563eb',
            backgroundColor: gradient,
            borderWidth: 2.5,
            pointBackgroundColor: '#2563eb',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 7,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { color: '#f1f5f9' },
                ticks: { font: { size: 11, family: "'Be Vietnam Pro'" }, color: '#94a3b8' }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: { font: { size: 11, family: "'Be Vietnam Pro'" }, color: '#94a3b8',
                    callback: v => v + 'M' }
            }
        }
    }
});

// ── DONUT CHART ──────────────────────────────────────────
@php
    $catLabels = array_keys($categoryRevenue);
    $catValues = array_values($categoryRevenue);
    $catColors = ['#2563eb','#7c3aed','#10b981','#f59e0b','#ef4444','#06b6d4'];
@endphp
const catData = @json($catValues);
const catLabels = @json($catLabels);
const catColors = @json(array_slice($catColors, 0, count($catLabels)));

if (catData.length > 0) {
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: catColors,
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => {
                            const v = ctx.raw;
                            return ' ' + new Intl.NumberFormat('vi-VN').format(v) + 'đ';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush