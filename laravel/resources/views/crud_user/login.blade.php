@extends('layout')
@section('title', 'Đăng nhập - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">

            <div class="card border-0 shadow-xl rounded-5 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <i class="fas fa-user-lock fa-3x mb-3 opacity-90"></i>
                    <h1 class="h3 fw-bold mb-1">Đăng nhập</h1>
                    <p class="mb-0 opacity-90">Đăng nhập để tiếp tục mua sắm</p>
                </div>

                {{-- BODY --}}
                <div class="card-body p-5">

                    {{-- ERROR --}}
                    @if ($errors->any())
                    <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- FORM --}}
                    <form action="{{ route('user.authUser') }}" method="POST" class="row g-4">
                        @csrf

                        <div class="col-12">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="form-control form-control-lg">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Mật khẩu</label>
                            <input type="password" name="password" required class="form-control form-control-lg">
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary btn-lg w-100 rounded-pill shadow-lg py-3 fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>

                        <div class="col-12 text-center">
                            <small class="text-muted">
                                Chưa có tài khoản?
                                <a href="{{ route('user.createUser') }}" class="text-decoration-none fw-semibold">
                                    Đăng ký
                                </a>
                            </small>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-primary {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

.form-control:focus {
    border-color: #4facfe;
    box-shadow: 0 0 0 .25rem rgba(79, 172, 254, .15);
}

.shadow-xl {
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, .1),
        0 10px 10px -5px rgba(0, 0, 0, .04);
}
</style>
@endsection