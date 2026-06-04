@extends('layout')

@section('title', 'Đăng ký thành viên - Siêu thị trực tuyến')

@section('content')

<style>
/* Tổng thể nền */
body {
    background-color: #f2f9f4;
    /* Màu nền xanh nhạt giống ảnh */
}

.auth-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 80vh;
    padding: 40px 15px;
}

/* Header Logo */
.brand-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.brand-icon {
    background-color: #2e7d32;
    /* Xanh lá đậm */
    color: white;
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.brand-text {
    color: #1b5e20;
    font-size: 24px;
    font-weight: 800;
    margin: 0;
}

/* Card Form */
.auth-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 650px;
    /* Thu gọn lại một chút cho form cân đối hơn */
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
}

.auth-title {
    font-size: 22px;
    font-weight: 800;
    color: #212121;
    text-align: center;
    margin-bottom: 8px;
}

.auth-subtitle {
    font-size: 14px;
    color: #9e9e9e;
    text-align: center;
    margin-bottom: 30px;
}

/* Inputs & Labels */
.form-label {
    font-size: 14px;
    font-weight: 700;
    color: #616161;
    margin-bottom: 6px;
}

.form-control-custom {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid #e0e0e0;
    background-color: #fafafa;
    width: 100%;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control-custom:focus {
    outline: none;
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    background-color: #ffffff;
}

/* Button */
.btn-submit {
    background-color: #2e7d32;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 14px;
    font-size: 16px;
    font-weight: 700;
    width: 100%;
    margin-top: 10px;
    transition: background-color 0.2s;
}

.btn-submit:hover {
    background-color: #1b5e20;
}

/* Radio/Checkbox custom */
.form-check-input:checked {
    background-color: #2e7d32;
    border-color: #2e7d32;
}

/* Footer text */
.auth-footer {
    text-align: center;
    margin-top: 24px;
    font-size: 14px;
    color: #9e9e9e;
}

.auth-footer a {
    color: #2e7d32;
    font-weight: 700;
    text-decoration: none;
}

.auth-footer a:hover {
    text-decoration: underline;
}
</style>

<div class="auth-wrapper">
    {{-- Header Logo --}}
    <div class="brand-header">
        <div class="brand-icon">
            <i class="fas fa-leaf"></i>
        </div>
        <h1 class="brand-text">Siêu thị trực tuyến</h1>
    </div>

    {{-- Form Container --}}
    <div class="auth-card">
        <h2 class="auth-title">Đăng ký tài khoản mới!</h2>
        <p class="auth-subtitle">Trở thành thành viên để nhận các ưu đãi đặc biệt</p>

        {{-- ERROR ALERT --}}
        @if ($errors->any())
        <div class="alert alert-danger"
            style="border-radius: 10px; font-size: 14px; background-color: #ffebee; border: none; color: #c62828;">
            <div style="font-weight: 700; margin-bottom: 5px;">
                <i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user.postUser') }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- HỌ VÀ TÊN --}}
                <div class="col-md-6">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="name" class="form-control-custom" placeholder="Nhập họ và tên..."
                        value="{{ old('name') }}" required>
                </div>

                {{-- EMAIL --}}
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control-custom" placeholder="example@email.com"
                        value="{{ old('email') }}" required>
                </div>

                {{-- SỐ ĐIỆN THOẠI --}}
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" name="phone" class="form-control-custom" placeholder="0123456789"
                        value="{{ old('phone') }}">
                </div>

                {{-- ĐỊA CHỈ --}}
                <div class="col-md-6">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control-custom" placeholder="Nhập địa chỉ của bạn..."
                        value="{{ old('address') }}">
                </div>

                {{-- MẬT KHẨU --}}
                <div class="col-md-12 mt-2">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control-custom"
                        placeholder="Nhập mật khẩu (Tối thiểu 6 ký tự)..." required>
                </div>

                {{-- ĐIỀU KHOẢN --}}
                <div class="col-md-12 mt-3 mb-2">
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" id="terms" required
                            style="cursor: pointer;">
                        <label class="form-check-label" for="terms"
                            style="font-size: 14px; color: #616161; cursor: pointer;">
                            Tôi đồng ý với các <a href="#"
                                style="color: #2e7d32; font-weight: 700; text-decoration: none;">điều khoản dịch vụ</a>
                        </label>
                    </div>
                </div>

                {{-- BUTTON SUBMIT --}}
                <div class="col-md-12">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-plus me-2"></i> Đăng ký ngay
                    </button>
                </div>
            </div>

        </form>

        {{-- LOGIN LINK --}}
        <div class="auth-footer">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
        </div>
    </div>
</div>

@endsection