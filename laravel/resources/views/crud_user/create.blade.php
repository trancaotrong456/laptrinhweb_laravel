@extends('layout')

@section('title', 'Đăng ký thành viên - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">

            <div class="card border-0 shadow-xl rounded-5 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <i class="fas fa-user-plus fa-3x mb-3 opacity-90"></i>

                    <h1 class="h3 fw-bold mb-1">
                        Đăng ký tài khoản
                    </h1>

                    <p class="mb-0 opacity-90">
                        Trở thành thành viên để nhận ưu đãi đặc biệt
                    </p>
                </div>

                {{-- BODY --}}
                <div class="card-body p-5">

                    {{-- ERROR --}}
                    @if ($errors->any())
                    <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Có lỗi xảy ra:
                        </h6>

                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- FORM --}}
                    <form action="{{ route('user.postUser') }}" method="POST" class="row g-4">
                        @csrf

                        {{-- HỌ TÊN --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Họ và tên
                            </label>

                            <input type="text" name="name" class="form-control form-control-lg"
                                value="{{ old('name') }}" required>
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input type="email" name="email" class="form-control form-control-lg"
                                value="{{ old('email') }}" required>
                        </div>

                        {{-- NGÀY SINH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Ngày sinh
                            </label>

                            <input type="date" name="dob" class="form-control form-control-lg" value="{{ old('dob') }}">
                        </div>

                        {{-- GIỚI TÍNH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Giới tính
                            </label>

                            <div class="mt-2">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Nam" checked>

                                    <label class="form-check-label">
                                        Nam
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Nữ">

                                    <label class="form-check-label">
                                        Nữ
                                    </label>
                                </div>

                            </div>
                        </div>

                        {{-- NGHỀ NGHIỆP --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Nghề nghiệp
                            </label>

                            <select name="job" class="form-select form-select-lg">

                                <option value="Giáo viên">
                                    Giáo viên
                                </option>

                                <option value="Sinh viên">
                                    Sinh viên
                                </option>

                                <option value="Khác">
                                    Khác
                                </option>

                            </select>
                        </div>

                        {{-- SỞ THÍCH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Sở thích
                            </label>

                            <input type="text" name="like" class="form-control form-control-lg"
                                placeholder="Nhập sở thích, cách nhau dấu phẩy" value="{{ old('like') }}">
                        </div>

                        {{-- PHONE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Số điện thoại
                            </label>

                            <input type="tel" name="phone" class="form-control form-control-lg"
                                value="{{ old('phone') }}" placeholder="0123456789">
                        </div>

                        {{-- ADDRESS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Địa chỉ
                            </label>

                            <input type="text" name="address" class="form-control form-control-lg"
                                value="{{ old('address') }}" placeholder="Nhập địa chỉ">
                        </div>

                        {{-- PASSWORD --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Mật khẩu
                            </label>

                            <input type="password" name="password" class="form-control form-control-lg" required>

                            <div class="form-text">
                                Tối thiểu 6 ký tự
                            </div>
                        </div>

                        {{-- TERMS --}}
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required>

                                <label class="form-check-label">
                                    Tôi đồng ý với điều khoản dịch vụ
                                </label>
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary btn-lg w-100 rounded-pill shadow-lg py-3 fw-bold">

                                🚀 Tạo tài khoản ngay
                            </button>
                        </div>

                        {{-- LOGIN --}}
                        <div class="col-12 text-center">
                            <small class="text-muted">
                                Đã có tài khoản?

                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">

                                    Đăng nhập
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
.card {
    border: 1px solid rgba(0, 0, 0, .05);
    transition: .3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, .15);
}

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

.form-control:focus,
.form-select:focus {
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