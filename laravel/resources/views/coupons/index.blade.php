@extends('layout')
@section('title', 'Coupons Management')

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

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Coupons Management</h3>
        <a href="{{ route('coupons.create') }}" class="btn btn-primary">Create Coupon</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Min Order</th>
                            <th>Status</th>
                            <th>Usage</th>
                            <th style="width: 160px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
                            <td><strong>{{ $coupon->code }}</strong></td>
                            <td>{{ $coupon->type }}</td>
                            <td>
                                @if($coupon->type === 'percent')
                                {{ rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') }}%
                                @else
                                {{ number_format($coupon->value) }} đ
                                @endif
                            </td>
                            <td>{{ $coupon->min_order_value ? number_format($coupon->min_order_value) . ' đ' : '-' }}
                            </td>
                            <td>
                                @if($coupon->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                {{ $coupon->used_count }}
                                @if(!is_null($coupon->usage_limit))
                                / {{ $coupon->usage_limit }}
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">

                                    {{-- 1. NÚT SỬA (EDIT) --}}
                                    <a href="{{ route('coupons.edit', $coupon) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px;"
                                        title="Chỉnh sửa mã">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @php
                                    $isSaved = in_array($coupon->id, $savedCouponIds);
                                    @endphp

                                    {{-- 2. NÚT LƯU / BỎ LƯU VOUCHER --}}
                                    @auth
                                    @if($isSaved)
                                    <form action="{{ route('coupons.unsave', $coupon) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px;"
                                            title="Bỏ lưu mã này">
                                            <i class="fas fa-bookmark"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('coupons.save', $coupon) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px;"
                                            title="Lưu mã vào ví">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @endauth

                                    {{-- 3. NÚT XÓA (DELETE) --}}
                                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="m-0"
                                        onsubmit="return confirm('Delete this coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px;"
                                            title="Xóa mã giảm giá">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No coupons found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $coupons->links() }}
    </div>
</div>
@endsection