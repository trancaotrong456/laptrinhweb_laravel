@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">🥤 QUẢN LÝ ĐỒ UỐNG</h3>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addDoUongModal">
                ➕ Thêm đồ uống
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
                            <th width="30%">Tên đồ uống</th>
                            <th width="45%">Mô tả</th>
                            <th width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doUongs as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->description ?? 'Chưa có mô tả' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editCategory({{ $item->id }}, '{{ $item->name }}', '{{ $item->description }}')">
                                    ✏️ Sửa
                                </button>
                                <form action="{{ route('categories.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa đồ uống {{ $item->name }}?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="alert alert-info mb-0">
                                    📭 Chưa có đồ uống nào. Hãy thêm mới!
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

<!-- Modal Thêm đồ uống -->
<div class="modal fade" id="addDoUongModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">🥤 Thêm đồ uống mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="do_uong">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên đồ uống</label>
                        <input type="text" name="name" class="form-control" required placeholder="VD: Coca Cola, Pepsi, Trà xanh...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả về đồ uống..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">💾 Lưu đồ uống</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa đồ uống -->
<div class="modal fade" id="editDoUongModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">✏️ Sửa đồ uống</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên đồ uống</label>
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
function editCategory(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('editForm').action = '/categories/' + id;
    new bootstrap.Modal(document.getElementById('editDoUongModal')).show();
}
</script>
@endsection