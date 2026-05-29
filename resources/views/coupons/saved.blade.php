@extends('layout')

@section('title', 'Danh sách mã giảm giá đã lưu - Siêu thị Mini')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            <i class="fas fa-ticket-alt me-2 text-primary"></i>
            Danh sách mã giảm giá đã lưu
        </h3>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="fas fa-store me-2"></i>
            Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @forelse($coupons as $coupon)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0"><strong>{{ $coupon->code }}</strong></h5>
                        @if($coupon->type === 'percent')
                        <span class="badge bg-danger">
                            {{ rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') }}%
                        </span>
                        @else
                        <span class="badge bg-danger">-{{ number_format($coupon->value) }} đ</span>
                        @endif
                    </div>

                    <p class="text-muted small mb-2">
                        Đơn tối thiểu:
                        <strong>
                            {{ $coupon->min_order_value ? number_format($coupon->min_order_value) . ' đ' : 'Không yêu cầu' }}
                        </strong>
                    </p>

                    @if($coupon->ends_at)
                    <p class="text-muted small mb-3">Hết hạn: {{ $coupon->ends_at->format('d/m/Y H:i') }}</p>
                    @else
                    <p class="text-muted small mb-3">Không giới hạn thời gian</p>
                    @endif

                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('coupons.unsave', $coupon) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i> Bỏ lưu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-inbox fa-5x text-muted mb-3"></i>
            <h4 class="text-muted mb-3">Bạn chưa lưu mã giảm giá nào</h4>
            <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                <i class="fas fa-tags me-2"></i>
                Xem khuyến mãi
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</div>
@endsection