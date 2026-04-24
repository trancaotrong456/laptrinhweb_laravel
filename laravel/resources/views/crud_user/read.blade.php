@extends('dashboard')

@section('content')
<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Like</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->like }}</td>
                        <td>{{ $user->address }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <a href="{{ route('user.listUser') }}" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </div>
    </div>
</main>
@endsection