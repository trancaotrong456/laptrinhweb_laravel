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
                            <th style="width: 180px;">Actions</th>
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
                            <td>
                                <a href="{{ route('coupons.edit', $coupon) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>

                                @php
                                $isSaved = in_array($coupon->id, $savedCouponIds);
                                @endphp

                                @auth
                                @if($isSaved)
                                <form action="{{ route('coupons.unsave', $coupon) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Bỏ lưu
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('coupons.save', $coupon) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-ticket-alt me-1"></i>Lưu
                                    </button>
                                </form>
                                @endif
                                @endauth

                                <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
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