@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' - TTP Admin')

@section('content')

@php
    $statusConfig = [
        'pending'   => ['label' => 'Đã đặt',      'class' => 'pending',   'icon' => 'fas fa-clock'],
        'packing'   => ['label' => 'Đóng gói',    'class' => 'packing',   'icon' => 'fas fa-box'],
        'shipping'  => ['label' => 'Đang giao',   'class' => 'shipping',  'icon' => 'fas fa-truck'],
        'delivered' => ['label' => 'Hoàn thành',  'class' => 'delivered', 'icon' => 'fas fa-check-circle'],
        'cancelled' => ['label' => 'Đã hủy',      'class' => 'cancelled', 'icon' => 'fas fa-times-circle'],
    ];
    $st = $statusConfig[$order->status] ?? $statusConfig['pending'];
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-file-invoice" style="color:#2563eb;font-size:20px;"></i>
            Đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
        </h1>
        <div class="page-sub">
            Đặt lúc {{ \Carbon\Carbon::parse($order->created_at)->format('H:i - d/m/Y') }}
            &nbsp;·&nbsp;
            <span class="badge-status {{ $st['class'] }}">
                <i class="{{ $st['icon'] }}" style="font-size:10px;"></i>
                {{ $st['label'] }}
            </span>
        </div>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <a href="{{ route('admin.orders.index') }}" class="btn-outline-admin">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        {{-- Nút cập nhật trạng thái --}}
        @if($order->status === 'pending')
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="packing">
            <button type="submit" class="btn-primary-admin"><i class="fas fa-box"></i> Xác nhận đóng gói</button>
        </form>
        @elseif($order->status === 'packing')
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="shipping">
            <button type="submit" class="btn-primary-admin"><i class="fas fa-truck"></i> Bắt đầu giao hàng</button>
        </form>
        @elseif($order->status === 'shipping')
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="delivered">
            <button type="submit" class="btn-primary-admin" style="background:#10b981;"><i class="fas fa-check"></i> Xác nhận hoàn thành</button>
        </form>
        @endif

        @if(!in_array($order->status, ['delivered','cancelled']))
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
            onsubmit="return confirm('Hủy đơn hàng này?')">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="btn-outline-admin" style="border-color:#fecaca;color:#ef4444;">
                <i class="fas fa-times"></i> Hủy đơn
            </button>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
<div class="admin-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 340px;gap:18px;align-items:start;">
    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:18px;">

        {{-- ORDER ITEMS --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="admin-card-title">
                    <i class="fas fa-shopping-cart" style="color:#2563eb;"></i>
                    Sản phẩm đã đặt
                </h6>
            </div>
            <div style="overflow:hidden;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="text-align:center;">Số lượng</th>
                            <th style="text-align:right;">Đơn giá</th>
                            <th style="text-align:right;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        @php
                            $imgUrl = null;
                            if ($item->product && $item->product->image) {
                                $imgUrl = str_contains($item->product->image, '/')
                                    ? asset('storage/' . $item->product->image)
                                    : asset('images/' . $item->product->image);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $item->product_name }}"
                                        style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;">
                                    @else
                                    <div style="width:44px;height:44px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#cbd5e1;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;color:#1e293b;font-size:13.5px;">{{ $item->product_name }}</div>
                                        @if($item->product)
                                        <div style="font-size:12px;color:#94a3b8;">ID: {{ $item->product_id }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;font-weight:600;">x{{ $item->quantity }}</td>
                            <td style="text-align:right;color:#64748b;">{{ number_format($item->product_price) }}đ</td>
                            <td style="text-align:right;font-weight:700;color:#ef4444;">{{ number_format($item->product_price * $item->quantity) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Tổng cộng --}}
                <div style="padding:16px 20px;border-top:1px solid #f1f5f9;background:#f8fafc;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:15px;font-weight:700;color:#1e293b;">Tổng cộng:</span>
                        <span style="font-size:20px;font-weight:800;color:#ef4444;">{{ number_format($order->total) }}đ</span>
                    </div>
                    @if($order->discount_amount ?? 0 > 0)
                    <div style="display:flex;justify-content:space-between;margin-top:6px;">
                        <span style="font-size:13px;color:#64748b;">Giảm giá:</span>
                        <span style="font-size:13px;font-weight:600;color:#10b981;">-{{ number_format($order->discount_amount) }}đ</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- STATUS TIMELINE --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="admin-card-title">
                    <i class="fas fa-history" style="color:#7c3aed;"></i>
                    Lịch sử trạng thái
                </h6>
            </div>
            <div class="admin-card-body">
                @php
                    $steps = ['pending','packing','shipping','delivered'];
                    $currentIdx = array_search($order->status, $steps);
                    if ($order->status === 'cancelled') $currentIdx = -1;
                @endphp
                <div style="display:flex;align-items:center;gap:0;position:relative;">
                    @foreach($steps as $idx => $step)
                    @php
                        $sc = $statusConfig[$step];
                        $isDone = $currentIdx !== -1 && $idx <= $currentIdx;
                        $isCurrent = $idx == $currentIdx;
                    @endphp
                    <div style="flex:1;text-align:center;position:relative;z-index:1;">
                        <div style="width:36px;height:36px;border-radius:50%;margin:0 auto 8px;
                            background:{{ $isDone ? '#2563eb' : '#f1f5f9' }};
                            color:{{ $isDone ? '#fff' : '#cbd5e1' }};
                            display:flex;align-items:center;justify-content:center;
                            font-size:14px;border:3px solid {{ $isCurrent ? '#2563eb' : ($isDone ? '#bfdbfe' : '#e2e8f0') }};
                            box-shadow:{{ $isCurrent ? '0 0 0 4px rgba(37,99,235,.15)' : 'none' }};">
                            <i class="{{ $sc['icon'] }}" style="font-size:13px;"></i>
                        </div>
                        <div style="font-size:11.5px;font-weight:{{ $isCurrent ? '700' : '500' }};color:{{ $isDone ? '#1e293b' : '#94a3b8' }};">
                            {{ $sc['label'] }}
                        </div>
                    </div>
                    @if(!$loop->last)
                    <div style="height:2px;width:40px;background:{{ ($currentIdx !== -1 && $idx < $currentIdx) ? '#2563eb' : '#e2e8f0' }};flex-shrink:0;z-index:0;margin-top:-18px;"></div>
                    @endif
                    @endforeach
                </div>
                @if($order->status === 'cancelled')
                <div style="text-align:center;margin-top:16px;">
                    <span class="badge-status cancelled" style="font-size:13px;padding:6px 16px;">
                        <i class="fas fa-times-circle"></i> Đơn hàng đã bị hủy
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
        {{-- Customer Info --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="admin-card-title">
                    <i class="fas fa-user" style="color:#10b981;"></i>
                    Thông tin khách hàng
                </h6>
            </div>
            <div class="admin-card-body" style="padding:16px 20px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    @php
                        $customerName = $order->user->name ?? $order->name ?? 'Khách';
                        $initials = strtoupper(mb_substr($customerName, 0, 1));
                    @endphp
                    <div style="width:44px;height:44px;border-radius:12px;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;">
                        {{ $initials }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14.5px;color:#1e293b;">{{ $customerName }}</div>
                        @if($order->user)
                        <div style="font-size:12px;color:#94a3b8;">{{ $order->user->email }}</div>
                        @endif
                    </div>
                </div>
                @if($order->phone ?? ($order->user->phone ?? null))
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;font-size:13.5px;">
                    <i class="fas fa-phone" style="color:#94a3b8;width:16px;"></i>
                    <span>{{ $order->phone ?? $order->user->phone }}</span>
                </div>
                @endif
                @if($order->address)
                <div style="display:flex;gap:8px;align-items:flex-start;font-size:13.5px;">
                    <i class="fas fa-map-marker-alt" style="color:#94a3b8;width:16px;margin-top:2px;"></i>
                    <span>{{ $order->address }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Order Info --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="admin-card-title">
                    <i class="fas fa-info-circle" style="color:#f59e0b;"></i>
                    Thông tin đơn hàng
                </h6>
            </div>
            <div class="admin-card-body" style="padding:16px 20px;">
                @php
                    $rows = [
                        ['label' => 'Mã đơn',       'value' => '#' . str_pad($order->id, 6, '0', STR_PAD_LEFT)],
                        ['label' => 'Ngày đặt',      'value' => \Carbon\Carbon::parse($order->created_at)->format('H:i - d/m/Y')],
                        ['label' => 'Phương thức TT','value' => $order->payment_method ?? 'COD'],
                        ['label' => 'Ghi chú',       'value' => $order->note ?? '—'],
                    ];
                @endphp
                @foreach($rows as $row)
                <div style="display:flex;justify-content:space-between;gap:12px;margin-bottom:10px;font-size:13.5px;">
                    <span style="color:#64748b;flex-shrink:0;">{{ $row['label'] }}</span>
                    <span style="font-weight:600;color:#1e293b;text-align:right;">{{ $row['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection