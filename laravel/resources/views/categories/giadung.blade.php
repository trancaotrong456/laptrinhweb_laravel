@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">🏠 QUẢN LÝ GIA DỤNG</h3>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addGiaDungModal">
                ➕ Thêm gia dụng
            </button>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Tên gia dụng</th>
                            <th width="45%">Mô tả</th>
                            <th width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($giaDungs as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->description ?? 'Chưa có mô tả' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editItem({{ $item->id }}, '{{ $item->name }}', '{{ $item->description }}')">
                                    ✏️ Sửa
                                </button>
                                <form action="{{ route('categories.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa gia dụng {{ $item->name }}?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="alert alert-info mb-0">
                                    📭 Chưa có gia dụng nào. Hãy thêm mới!
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm gia dụng -->
<div class="modal fade" id="addGiaDungModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">🏠 Thêm gia dụng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="gia_dung">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên gia dụng</label>
                        <input type="text" name="name" class="form-control" required placeholder="VD: Nồi cơm điện, Chảo, Máy xay...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả về gia dụng..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-info">💾 Lưu gia dụng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa gia dụng -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">✏️ Sửa gia dụng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên gia dụng</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning">🔄 Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editItem(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('editForm').action = '/categories/' + id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endsection