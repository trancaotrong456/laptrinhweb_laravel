@extends('layouts.admin')
@section('title', 'Chi tiết người dùng - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-user-circle" style="color:#2563eb;"></i> Chi tiết người dùng</h1>
    <a href="{{ route('user.listUser') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 700px; margin: 0 auto;">
    <div class="admin-card-header" style="background:#f8fafc;padding:16px 24px;">
        <h6 class="admin-card-title"><i class="fas fa-info-circle" style="color:#64748b;"></i> Thông tin cá nhân</h6>
    </div>
    <div class="admin-card-body" style="padding: 24px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">ID</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">#{{ $user->id }}</div>
            </div>
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Họ và tên</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $user->name }}</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Email</label>
                <div style="font-size:14px;font-weight:600;color:#2563eb;"><a href="mailto:{{ $user->email }}" style="color:inherit;text-decoration:none;">{{ $user->email }}</a></div>
            </div>
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Số điện thoại</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Địa chỉ</label>
            <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $user->address ?? 'Chưa cập nhật' }}</div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Ngày sinh</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">
                    @if ($user->dob)
                        {{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}
                    @else
                        Chưa cập nhật
                    @endif
                </div>
            </div>
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Giới tính</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $user->gender ?? 'Chưa cập nhật' }}</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;border-top:1px dashed #e2e8f0;padding-top:20px;">
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Ngày tạo tài khoản</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $user->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div>
                <label class="form-label-ctrl" style="color:#94a3b8;margin-bottom:4px;">Cập nhật lần cuối</label>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div style="display:flex;gap:12px;border-top:1px solid #f1f5f9;padding-top:20px;">
            <a href="{{ route('user.updateUser', $user->id) }}" class="btn-primary-admin" style="background:#f59e0b;color:#fff;flex:1;justify-content:center;">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <form action="{{ route('user.deleteUser', $user->id) }}" method="POST" style="flex:1;margin:0;" onsubmit="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-primary-admin" style="background:#ef4444;color:#fff;width:100%;justify-content:center;">
                    <i class="fas fa-trash"></i> Xóa tài khoản
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
