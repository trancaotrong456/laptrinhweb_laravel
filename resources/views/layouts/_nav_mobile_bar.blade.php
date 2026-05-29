{{-- Thanh nav nhỏ bên dưới (option 2 - không sticky) --}}
<div class="container my-3">
    <div class="card shadow-sm border-0">
        <div class="card-body p-2">
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}">
                    <i class="fas fa-home me-1"></i>Trang chủ
                </a>

                @auth
                <a class="btn btn-outline-primary btn-sm" href="{{ route('posts.index') }}">
                    <i class="fas fa-tags me-1"></i>Khuyến mãi
                </a>

                <a class="btn btn-outline-danger btn-sm" href="{{ route('coupons.saved') }}">
                    <i class="fas fa-ticket-alt me-1"></i>Mã giảm giá
                </a>

                <a class="btn btn-outline-dark btn-sm" href="{{ route('products.all') }}">
                    <i class="fas fa-box-open me-1"></i>Tất cả sản phẩm
                </a>
                @else
                <a class="btn btn-outline-primary btn-sm" href="{{ route('posts.index') }}">
                    <i class="fas fa-tags me-1"></i>Khuyến mãi
                </a>
                @endauth

                @if(Auth::check() && (int) Auth::user()->role === 1)
                <a class="btn btn-outline-warning btn-sm" href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line me-1"></i>Dashboard
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('products.index') }}">
                    <i class="fas fa-box me-1"></i>Quản lý sản phẩm
                </a>
                <a class="btn btn-outline-info btn-sm" href="{{ route('categories.index') }}">
                    <i class="fas fa-list me-1"></i>Danh mục
                </a>
                <a class="btn btn-outline-success btn-sm" href="{{ route('coupons.index') }}">
                    <i class="fas fa-ticket-alt me-1"></i>Coupons
                </a>
                <a class="btn btn-outline-dark btn-sm" href="{{ route('user.listUser') }}">
                    <i class="fas fa-users me-1"></i>Users
                </a>
                @endif
            </div>
        </div>
    </div>
</div>