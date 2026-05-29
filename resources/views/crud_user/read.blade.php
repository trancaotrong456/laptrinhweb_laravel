@extends('layout')

@section('title', 'Chi tiết người dùng - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 fw-bold">
                <i class="fas fa-user-circle me-2"></i>
                Chi tiết người dùng
            </h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('user.listUser') }}" class="btn btn-secondary btn-lg rounded-4">
                <i class="fas fa-arrow-left me-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin cá nhân
                    </h5>
                </div>

                {{-- BODY --}}
                <div class="card-body p-5">

                    {{-- ID --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            ID
                        </label>
                        <p class="h6">{{ $user->id }}</p>
                    </div>

                    {{-- HỌ TÊN --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Họ và tên
                        </label>
                        <p class="h6">{{ $user->name }}</p>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Email
                        </label>
                        <p class="h6">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </p>
                    </div>

                    {{-- SỐ ĐIỆN THOẠI --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Số điện thoại
                        </label>
                        <p class="h6">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                    </div>

                    {{-- ĐỊA CHỈ --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Địa chỉ
                        </label>
                        <p class="h6">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                    </div>

                    {{-- NGÀY SINH --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Ngày sinh
                        </label>
                        <p class="h6">
                            @if ($user->dob)
                                {{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}
                            @else
                                Chưa cập nhật
                            @endif
                        </p>
                    </div>

                    {{-- GIỚI TÍNH --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Giới tính
                        </label>
                        <p class="h6">{{ $user->gender ?? 'Chưa cập nhật' }}</p>
                    </div>

                    {{-- NGÀY TẠO --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">
                            Ngày tạo tài khoản
                        </label>
                        <p class="h6">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    {{-- NGÀY CẬP NHẬT --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-muted">
                            Ngày cập nhật gần nhất
                        </label>
                        <p class="h6">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="d-flex gap-3 mt-4">
                <a href="{{ route('user.updateUser', $user->id) }}" class="btn btn-warning btn-lg rounded-4 flex-grow-1">
                    <i class="fas fa-edit me-2"></i>
                    Chỉnh sửa
                </a>
                <form action="{{ route('user.deleteUser', $user->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg rounded-4 w-100"
                        onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">
                        <i class="fas fa-trash me-2"></i>
                        Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
