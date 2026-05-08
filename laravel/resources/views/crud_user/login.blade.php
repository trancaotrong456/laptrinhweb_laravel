@extends('dashboard')

@section('content')
<form action="{{ route('user.authUser') }}" method="POST">
    @csrf
    <h1>Màn hình đăng nhập</h1>
    
    <label for="email">Email đăng nhập:</label>
    <input type="email" id="email" name="email" required><br><br>
    @if ($errors->has('email'))
        <span style="color: red; display: block; margin-top: -15px; margin-bottom: 10px;">{{ $errors->first('email') }}</span>
    @endif

    <label for="password">Mật khẩu:</label>
    <input type="password" id="password" name="password" required><br><br>
    @if ($errors->has('password'))
        <span style="color: red; display: block; margin-top: -15px; margin-bottom: 10px;">{{ $errors->first('password') }}</span>
    @endif

    <input type="checkbox" id="remember" name="remember">Ghi nhớ đăng nhập<br><br>
    
    <input type="submit" value="Đăng nhập">
</form>
@endsection