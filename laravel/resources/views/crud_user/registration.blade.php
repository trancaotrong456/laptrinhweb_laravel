@extends('layout')
@section('title', 'Đăng ký tài khoản - Siêu thị Mini')

@section('content')
<!-- Registration Hero -->
<section class="vh-100 d-flex align-items-center"
    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <div class="card shadow-2xl border-0 rounded-5 overflow-hidden animate-slide-in-up">
                    <div class="row g-0">
                        <!-- Left image -->
                        <div
                            class="col-md-6 d-none d-md-flex align-items-center justify-content-center bg-gradient-primary position-relative overflow-hidden">
                            <div class="text-center text-white p-5">
                                <i class="fas fa-user-plus fa-4x mb-4 opacity-75"></i>
                                <h2 class="fw-bold mb-3 lh-1 display-5">Chào mừng bạn!</h2>
                                <p class="lead opacity-90 lh-lg mb-0">Tham gia ngay để săn ưu đãi cực hot!</p>
                            </div>
                        </div>

                        <!-- Right form -->
                        <div class="col-md-6 p-5">
                            <div class="text-center mb-5">
                                <h1 class="fw-bold mb-2 lh-1">Tạo tài khoản</h1>
                                <p class="text-muted mb-0">Đăng ký nhanh trong 30s</p>
                            </div>

                            @if ($errors->any())
                            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('user.postUser') }}" method="POST" class="needs-validation"
                                novalidate>
                                @csrf

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Họ tên <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control form-control-lg border-start-0 shadow-sm @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" required autofocus
                                                placeholder="Nhập họ tên">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Email <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-envelope text-muted"></i>
                                            </span>
                                            <input type="email"
                                                class="form-control form-control-lg border-start-0 shadow-sm @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required
                                                placeholder="example@email.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Điện thoại</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="tel"
                                            class="form-control form-control-lg border-start-0 shadow-sm @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone') }}" placeholder="0123456789">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Địa chỉ</label>
                                    <textarea
                                        class="form-control form-control-lg shadow-sm rounded-4 @error('address') is-invalid @enderror"
                                        name="address" rows="3"
                                        placeholder="Nhập địa chỉ nhận hàng">{{ old('address') }}</textarea>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Mật khẩu <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control form-control-lg border-start-0 shadow-sm @error('password') is-invalid @enderror"
                                                name="password" required minlength="6" placeholder="Tối thiểu 6 ký tự">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Giới tính</label>
                                        <div class="d-flex gap-4 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="male"
                                                    value="Nam" {{ old('gender', 'Nam') == 'Nam' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="male">
                                                    <i class="fas fa-mars text-primary me-1"></i>Nam
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="female"
                                                    value="Nữ" {{ old('gender') == 'Nữ' ? 'old' : '' }}>
                                                <label class="form-check-label fw-medium" for="female">
                                                    <i class="fas fa-venus text-pink me-1"></i>Nữ
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Ghi nhớ đăng nhập &amp; tôi đã đọc <a href="#"
                                                class="text-primary fw-semibold">điều khoản</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary btn-lg w-100 rounded-pill shadow-xl fw-bold py-3 mb-3">
                                    <i class="fas fa-user-plus me-2"></i>Tạo tài khoản miễn phí
                                </button>

                                <div class="text-center">
                                    <small class="text-muted">
                                        Đã có tài khoản?
                                        <a href="{{ route('login') }}"
                                            class="text-primary fw-semibold text-decoration-none">
                                            Đăng nhập ngay
                                        </a>
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.animate-slide-in-up {
    animation: slideInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.text-pink {
    color: #e91e63;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.input-group .form-control:focus {
    border-color: #667eea;
    box-shadow: none;
}

@media (max-width: 768px) {
    .card {
        margin: 1rem;
    }

    .input-group-lg .form-control {
        font-size: 1rem;
    }
}
</style>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Auto focus next input
document.querySelectorAll('input, select, textarea').forEach((el, index) => {
    el.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const next = this.parentElement.parentElement.nextElementSibling?.querySelector(
                'input, select, textarea');
            if (next) next.focus();
        }
    });
});
</script>
@endsection