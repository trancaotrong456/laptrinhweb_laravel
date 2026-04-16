@extends('dashboard')
@section('content')
<div class="card table-card">
    <h1>Danh sách user</h1>

    <form action="{{ route('user.listUser') }}" method="GET" style="box-shadow: none; margin: 0; padding: 10px;">
        <input type="text" name="search" placeholder="Nhập tên cần tìm..." value="{{ request('search') }}" style="width: 70%; display: inline-block;">
        <input type="submit" value="Tìm" style="width: 25%; display: inline-block; padding: 8px;">
    </form>

    <table>
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Sở thích</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->like }}</td>
                <td>
                    <a href="{{ route('user.readUser', $user->id) }}">View</a> |
                    <a href="{{ route('user.updateUser', $user->id) }}">Edit</a> |
                    <a href="{{ route('user.deleteUser', $user->id) }}" onclick="return confirm('Xóa hả?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
    {{ $users->links('pagination::bootstrap-4') }}
</div>
    
    <div class="pagination">
        {{-- $users->links() --}}
    </div>
</div>
@endsection