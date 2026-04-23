@extends('layout')
@section('title', 'Đăng nhập - Siêu thị Mini')

@section('content')
<main class="signup-form py-5" style="min-height: 80vh; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-4 text-center">
                        <h3 class="mb-0 fw-bold">Chào mừng quay lại!</h3>
                        <p class="mb-0 opacity-90 mt-1">Đăng nhập để tiếp tục mua sắm</p>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('user.authUser') }}" method="POST">
                            @csrf

                            @if ($errors->any())
                            <div class="alert alert-danger rounded-3">
                                {{ $errors->first() }}
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">📧 Email đăng nhập</label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="nhap@gmail.com">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">🔒 Mật khẩu</label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" required placeholder="Nhập mật khẩu">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label small fw-normal" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="#" class="small text-decoration-none fw-semibold text-primary">Quên mật
                                    khẩu?</a>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit"
                                    class="btn btn-primary btn-lg fw-bold py-3 rounded-pill shadow-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
                                </button>
                            </div>

                            <div class="text-center border-top pt-3">
                                <small class="text-muted me-2">Chưa có tài khoản?</small>
                                <a href="{{ route('user.createUser') }}"
                                    class="fw-bold text-primary text-decoration-none">
                                    Đăng ký miễn phí!
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="bg-light py-4 text-center">
                        <small class="text-muted">Bảo mật thông tin người dùng • Hỗ trợ 24/7</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.signup-form .card {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4);
}
</style>
@endsection