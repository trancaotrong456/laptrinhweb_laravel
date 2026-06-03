@extends('layout')

@section('title', 'Chỉnh sửa người dùng - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="card border-0 shadow-xl rounded-5 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <i class="fas fa-user-edit fa-3x mb-3 opacity-90"></i>

                    <h1 class="h3 fw-bold mb-1">
                        Chỉnh sửa thông tin người dùng
                    </h1>

                    <p class="mb-0 opacity-90">
                        Cập nhật thông tin người dùng
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
                    <form action="{{ route('user.postUpdateUser', $user->id) }}" method="POST" class="row g-4">
                        @csrf

                        {{-- HỌ TÊN --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Họ và tên
                            </label>

                            <input type="text" name="name" class="form-control form-control-lg rounded-3"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input type="email" name="email" class="form-control form-control-lg rounded-3"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        {{-- NGÀY SINH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Ngày sinh
                            </label>

                            <input type="date" name="dob" class="form-control form-control-lg rounded-3"
                                value="{{ old('dob', $user->dob) }}">
                        </div>

                        {{-- GIỚI TÍNH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Giới tính
                            </label>

                            <div class="mt-2">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Nam"
                                        {{ old('gender', $user->gender) === 'Nam' ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        Nam
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" value="Nữ"
                                        {{ old('gender', $user->gender) === 'Nữ' ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        Nữ
                                    </label>
                                </div>

                            </div>
                        </div>

                        {{-- SỐ ĐIỆN THOẠI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Số điện thoại
                            </label>

                            <input type="tel" name="phone" class="form-control form-control-lg rounded-3"
                                value="{{ old('phone', $user->phone) }}" placeholder="0123456789">
                        </div>

                        {{-- ĐỊA CHỈ --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Địa chỉ
                            </label>

                            <input type="text" name="address" class="form-control form-control-lg rounded-3"
                                value="{{ old('address', $user->address) }}"
                                placeholder="Nhập địa chỉ">
                        </div>

                        {{-- MẬT KHẨU --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Mật khẩu mới (để trống nếu không thay đổi)
                            </label>

                            <input type="password" name="password" class="form-control form-control-lg rounded-3"
                                placeholder="Nhập mật khẩu mới">

                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Để trống nếu bạn không muốn thay đổi mật khẩu
                            </small>
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-12 d-flex gap-3 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 flex-grow-1">
                                <i class="fas fa-save me-2"></i>
                                Cập nhật
                            </button>

                            <a href="{{ route('user.readUser', $user->id) }}" class="btn btn-secondary btn-lg rounded-3 flex-grow-1">
                                <i class="fas fa-times me-2"></i>
                                Hủy
                            </a>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
