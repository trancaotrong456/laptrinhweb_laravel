@extends('layouts.admin')
@section('title', 'Chỉnh sửa người dùng - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-user-edit" style="color:#2563eb;"></i> Chỉnh sửa người dùng</h1>
    <a href="{{ route('user.listUser') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 700px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form action="{{ route('user.postUpdateUser', ['id' => $user->id]) }}" method="POST">
            @csrf
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Họ và tên <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="form-ctrl" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-ctrl">Email</label>
                    <input type="email" class="form-ctrl" value="{{ $user->email }}" disabled style="background:#f8fafc;cursor:not-allowed;">
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Email không thể thay đổi</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Số điện thoại</label>
                    <input type="text" name="phone" class="form-ctrl" value="{{ old('phone', $user->phone) }}">
                </div>
                <div>
                    <label class="form-label-ctrl">Giới tính</label>
                    <select name="gender" class="form-select-ctrl">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khác" {{ old('gender', $user->gender) == 'Khác' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Ngày sinh</label>
                    <input type="date" name="dob" class="form-ctrl" value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}">
                </div>
                <div>
                    <label class="form-label-ctrl">Vai trò (Role)</label>
                    <select name="role" class="form-select-ctrl">
                        <option value="0" {{ old('role', $user->role) == 0 ? 'selected' : '' }}>USER (Khách hàng)</option>
                        <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>ADMIN (Quản trị viên)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-ctrl">Địa chỉ</label>
                <textarea name="address" class="form-ctrl" rows="3">{{ old('address', $user->address) }}</textarea>
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Cập nhật thông tin</button>
            </div>
        </form>
    </div>
</div>
@endsection
