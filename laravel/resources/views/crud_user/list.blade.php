@extends('layouts.admin')

@section('title', 'Quản lý người dùng - TTP Admin')

@push('styles')
<style>
/* ── SEARCH BAR ─────────────────────────────────────────── */
.user-search-bar {
    display: flex; gap: 12px; align-items: center;
    margin-bottom: 20px;
}
.user-search-input {
    flex: 1; height: 44px; padding: 0 18px;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 14px; font-family: inherit; color: #1e293b;
    background: #fff; outline: none; transition: all .2s;
}
.user-search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.user-search-btn {
    height: 44px; padding: 0 22px;
    background: green; color: #fff; border: none;
    border-radius: 10px; font-size: 13.5px; font-weight: 600;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all .2s; font-family: inherit;
}
.user-search-btn:hover { background:green; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }

/* ── TABLE HEADER ──────────────────────────────────────── */
.user-table-header {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.user-table-col-avatar { width: 52px; flex-shrink: 0; }
.user-table-col-name   { flex: 1; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #64748b; }
.user-table-col-role   { width: 110px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #64748b; }
.user-table-col-status { width: 110px; flex-shrink: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #64748b; }
.user-table-col-action { width: 100px; flex-shrink: 0; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #64748b; }

/* ── USER ROW ──────────────────────────────────────────── */
.user-row {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
    transition: background .15s;
}
.user-row:last-child { border-bottom: none; }
.user-row:hover { background: #f8faff; }

.user-row-avatar {
    width: 40px; height: 40px; border-radius: 10px;
    font-size: 14px; font-weight: 700; color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; text-transform: uppercase;
}
.user-row-info { flex: 1; min-width: 0; }
.user-row-name { font-size: 14px; font-weight: 600; color: #1e293b; }
.user-row-email { font-size: 12px; color: #94a3b8; margin-top: 1px; }
.user-row-role { width: 110px; flex-shrink: 0; }
.user-row-status { width: 110px; flex-shrink: 0; }
.user-row-action { width: 100px; flex-shrink: 0; display: flex; align-items: center; gap: 7px; justify-content: flex-end; }

/* ── EMPTY ─────────────────────────────────────────────── */
.empty-box {
    text-align: center; padding: 60px 20px; color: #94a3b8;
}
.empty-box i { font-size: 48px; margin-bottom: 14px; display: block; opacity: .5; }
.empty-box p { font-size: 14px; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-users" style="color:green;font-size:20px;"></i>
            Danh sách thành viên
        </h1>
        <div class="page-sub">Tổng cộng: <strong>{{ $users->total() }}</strong> tài khoản</div>
    </div>
    <a href="{{ route('user.createUser') }}" class="btn btn-success">
        <i class="fas fa-user-plus"></i> Thêm người dùng
    </a>
</div>

{{-- SUCCESS --}}
@if(session('success'))
<div class="admin-alert success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- SEARCH --}}
<form action="{{ route('user.listUser') }}" method="GET" class="user-search-bar">
    <input type="text" name="search" class="user-search-input"
        placeholder="Tìm theo tên, email hoặc số điện thoại..."
        value="{{ request('search') }}" id="userSearchInput">
    <button type="submit" class="user-search-btn">
        <i class="fas fa-search"></i> Tìm kiếm
    </button>
    @if(request('search'))
    <a href="{{ route('user.listUser') }}" class="btn-outline-admin">
        <i class="fas fa-times"></i> Xóa
    </a>
    @endif
</form>

{{-- TABLE --}}
<div class="admin-card" style="overflow:hidden;">
    {{-- Header --}}
    <div class="user-table-header">
        <div class="user-table-col-avatar"></div>
        <div class="user-table-col-name">Thành viên</div>
        <div class="user-table-col-role">Vai trò</div>
        <div class="user-table-col-status">Trạng thái</div>
        <div class="user-table-col-action">Thao tác</div>
    </div>

    {{-- Rows --}}
    @php
        $avatarColors = ['#2563eb','#7c3aed','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#84cc16','#14b8a6','#6366f1'];
    @endphp

    @forelse($users as $i => $user)
    @php
        $initials = strtoupper(mb_substr($user->name, 0, 1));
        $secondWord = explode(' ', $user->name);
        if (count($secondWord) > 1) $initials .= strtoupper(mb_substr(end($secondWord), 0, 1));
        $color = $avatarColors[$user->id % count($avatarColors)];
        $isAdmin = $user->role === 'admin' || (int)$user->role === 1;
    @endphp
    <div class="user-row">
        {{-- Avatar --}}
        <div class="user-table-col-avatar">
            <div class="user-row-avatar" style="background:{{ $color }};">{{ $initials }}</div>
        </div>
        {{-- Info --}}
        <div class="user-row-info">
            <div class="user-row-name">{{ $user->name }}</div>
            <div class="user-row-email">{{ $user->email }}</div>
        </div>
        {{-- Role --}}
        <div class="user-row-role">
            @if($isAdmin)
            <span class="badge-status admin">ADMIN</span>
            @else
            <span class="badge-status user">USER</span>
            @endif
        </div>
        {{-- Status --}}
        <div class="user-row-status">
            <span class="badge-status active">
                <i class="fas fa-circle" style="font-size:7px;"></i> Hoạt động
            </span>
        </div>
        {{-- Action --}}
        <div class="user-row-action">
            @if(!$isAdmin)
            <form action="{{ route('user.promoteUser', $user->id) }}" method="POST"
                onsubmit="return confirm('Cấp quyền Admin cho {{ $user->name }}?')">
                @csrf @method('PATCH')
                <button type="submit" class="icon-btn promote" title="Cấp quyền Admin">
                    <i class="fas fa-user-shield"></i>
                </button>
            </form>
            @else
            <button class="icon-btn" title="Đây là Admin" style="cursor:default;opacity:.5;">
                <i class="fas fa-user-shield"></i>
            </button>
            @endif
            <a href="{{ route('user.updateUser', $user->id) }}" class="icon-btn edit" title="Chỉnh sửa">
                <i class="fas fa-pen"></i>
            </a>
            <form action="{{ route('user.deleteUser', $user->id) }}" method="POST"
                onsubmit="return confirm('Xóa người dùng {{ $user->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn delete" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-box">
        <i class="fas fa-users-slash"></i>
        <p>Không tìm thấy người dùng nào</p>
    </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if($users->hasPages())
<div style="margin-top:18px;display:flex;justify-content:center;">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endif

@endsection
