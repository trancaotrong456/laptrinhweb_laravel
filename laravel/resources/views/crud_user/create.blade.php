@extends('layout')
@section('title', 'Đăng ký thành viên - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-xl rounded-5 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <i class="fas fa-user-plus fa-3x mb-3 opacity-90"></i>
                    <h1 class="h3 fw-bold mb-1">Đăng ký tài khoản</h1>
                    <p class="mb-0 opacity-90">Trở thành thành viên để nhận ưu đãi đặc biệt</p>
                </div>

                <div class="card-body p-5">
                    @if ($errors->any())
                    <div class="alert alert-danger rounded-4 shadow-sm mb-4 animate-pulse">
                        <h6 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra:</h6>
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                            <li class="small"><i class="fas fa-times-circle me-1 text-danger"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('user.postUser') }}" method="POST" class="row g-4">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngày sinh</label>
                            <input type="date" class="form-control form-control-lg @error('dob') is-invalid @enderror"
                                name="dob" value="{{ old('dob') }}" max="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                name="phone" value="{{ old('phone') }}" placeholder="0123456789">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Địa chỉ nhận hàng</label>
                            <textarea class="form-control form-control-lg @error('address') is-invalid @enderror"
                                name="address" rows="3"
                                placeholder="Số nhà, đường, phường, quận...">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                name="password" required minlength="6" placeholder="Nhập mật khẩu">
                            <div class="form-text small">Tối thiểu 6 ký tự</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Giới tính</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male2" value="Nam"
                                        {{ old('gender', 'Nam') == 'Nam' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="male2">Nam</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female2" value="Nữ"
                                        {{ old('gender') == 'Nữ' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="female2">Nữ</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" name="remember" required>
                                <label class="form-check-label small" for="terms">
                                    Đồng ý với <a href="#" class="text-decoration-none fw-semibold">Điều khoản dịch
                                        vụ</a> &
                                    <a href="#" class="text-decoration-none fw-semibold">Chính sách bảo mật</a>
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary btn-lg w-100 rounded-pill shadow-lg py-3 fw-bold position-relative overflow-hidden">
                                <span class="btn-text">🚀 Tạo tài khoản ngay</span>
                                <span class="btn-wave"></span>
                            </button>
                        </div>

                        <div class="col-12 text-center pt-3">
                            <small class="text-muted">
                                Đã có tài khoản?
                                <a href="{{ route('login') }}" class="text-primary fw-semibold">Đăng nhập tại đây</a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid rgba(0, 0, 0, .05);
    transition: all .3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, .15);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.btn-primary {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(79, 172, 254, 0.4);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .3), transparent);
    transition: left .5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.form-control:focus {
    border-color: #4facfe;
    box-shadow: 0 0 0 .25rem rgba(79, 172, 254, .15);
}

.form-check-input:checked {
    background-color: #4facfe;
    border-color: #4facfe;
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, .1), 0 10px 10px -5px rgba(0, 0, 0, .04);
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
}

@media (max-width: 576px) {
    .card-body {
        padding: 2rem 1.5rem !btn;
    }

    .btn-lg {
        padding: 1rem;
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.querySelector('.btn-text').textContent;
        submitBtn.querySelector('.btn-text').textContent = 'Đang tạo...';
        submitBtn.disabled = true;

        setTimeout(() => {
            submitBtn.querySelector('.btn-text').textContent = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
});
</script>
@endsection