@extends('layout')

@section('title', 'Danh sách người dùng - Siêu thị Mini')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 fw-bold">
                <i class="fas fa-users me-2"></i>
                Danh sách người dùng
            </h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('user.createUser') }}" class="btn btn-primary btn-lg rounded-4">
                <i class="fas fa-user-plus me-2"></i>
                Thêm người dùng
            </a>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- SEARCH FORM --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('user.listUser') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control form-control-lg rounded-3"
                        placeholder="Tìm kiếm theo tên hoặc email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 w-50">
                        <i class="fas fa-search me-2"></i>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('user.listUser') }}" class="btn btn-secondary btn-lg rounded-3 w-50">
                        <i class="fas fa-redo me-2"></i>
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- USERS TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center py-3">
                            <i class="fas fa-hashtag me-2"></i>ID
                        </th>
                        <th class="py-3">
                            <i class="fas fa-user me-2"></i>Tên người dùng
                        </th>
                        <th class="py-3">
                            <i class="fas fa-envelope me-2"></i>Email
                        </th>
                        <th class="py-3">
                            <i class="fas fa-phone me-2"></i>Số điện thoại
                        </th>
                        <th class="text-center py-3">
                            <i class="fas fa-cog me-2"></i>Hành động
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="text-center align-middle py-3">
                            <span class="badge bg-primary">{{ $user->id }}</span>
                        </td>
                        <td class="align-middle py-3">
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td class="align-middle py-3">
                            {{ $user->email }}
                        </td>
                        <td class="align-middle py-3">
                            {{ $user->phone ?? 'Chưa cập nhật' }}
                        </td>
                        <td class="text-center align-middle py-3">
                            <a href="{{ route('user.readUser', $user->id) }}" 
                                class="btn btn-sm btn-info rounded-3 me-2">
                                <i class="fas fa-eye me-1"></i>Xem
                            </a>
                            <a href="{{ route('user.updateUser', $user->id) }}" 
                                class="btn btn-sm btn-warning rounded-3 me-2">
                                <i class="fas fa-edit me-1"></i>Sửa
                            </a>
                            <form action="{{ route('user.deleteUser', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-3"
                                    onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash me-1"></i>Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Không có người dùng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if ($users->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $users->appends(request()->query())->render() }}
    </div>
    @endif
</div>
@endsection
