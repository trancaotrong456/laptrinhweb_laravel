@extends('layout')
@section('title', 'Xac nhan don hang - Sieu thi Mini')

@section('content')
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2><i class="fas fa-check-circle text-success me-2"></i>Xac nhan don hang</h2>
        <p class="text-muted mt-2">Cam on ban da mua hang!</p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Thanh cong!</strong> Don hang cua ban da duoc tao.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="card mb-4 shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Chi tiet don hang</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Khach hang:</strong></p>
                            <p>{{ auth()->user()->name }}</p>
                            <p>{{ auth()->user()->email }}</p>
                            <p>{{ auth()->user()->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngay dat:</strong></p>
                            <p>{{ \Illuminate\Support\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</p>
                            <p><strong>Phuong thuc thanh toan:</strong></p>
                            <p>
                                @switch($order['payment_method'])
                                    @case('cod')
                                        <span class="badge bg-info">Thanh toan khi nhan hang</span>
                                        @break
                                    @case('bank')
                                        <span class="badge bg-warning">Chuyen khoan ngan hang</span>
                                        @break
                                    @case('wallet')
                                        <span class="badge bg-success">Vi dien tu</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3"><i class="fas fa-shopping-bag me-2"></i>San pham da dat</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>San pham</th>
                                    <th class="text-center">Gia</th>
                                    <th class="text-center">So luong</th>
                                    <th class="text-end">Thanh tien</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['cart'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="text-center">{{ number_format($item['price'] ?? 0) }} �</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }} �</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Tam tinh:</strong>
                                <span>{{ number_format($order['subtotal'] ?? $order['total']) }} �</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Giam gia:</strong>
                                <span class="text-success">-{{ number_format($order['discount'] ?? 0) }} �</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Phi van chuyen:</strong>
                                <span>{{ number_format($order['shipping_fee'] ?? 0) }} �</span>
                            </div>
                            @if(!empty($order['coupon_code']))
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Ma giam gia:</strong>
                                <span class="badge bg-success">{{ $order['coupon_code'] }}</span>
                            </div>
                            @endif
                            <div class="border-top pt-2">
                                <div class="d-flex justify-content-between">
                                    <h5>Tong cong:</h5>
                                    <h5 class="text-success">{{ number_format($order['total']) }} �</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($order['notes']))
                    <hr>
                    <div>
                        <h6>Ghi chu:</h6>
                        <p class="text-muted">{{ $order['notes'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Tiep tuc mua sam
                </a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i>Ve gio hang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
