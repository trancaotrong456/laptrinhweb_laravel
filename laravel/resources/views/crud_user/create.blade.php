@extends('dashboard')
@section('content')
<form action="{{ route('user.postUser') }}" method="POST">
    @csrf
    <h1>Màn hình đăng ký</h1>

    <label>Họ tên:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Ngày sinh:</label>
    <input type="date" name="dob">

    <label>Giới tính:</label>
    <div style="margin-bottom: 15px;">
        <input type="radio" name="gender" value="Nam" checked> Nam
        <input type="radio" name="gender" value="Nữ"> Nữ
    </div>

    <label>Nghề nghiệp:</label>
    <select name="job" style="width: 100%; padding: 8px; margin-bottom: 15px;">
        <option value="Giáo viên">Giáo viên</option>
        <option value="Sinh viên">Sinh viên</option>
        <option value="Khác">Khác</option>
    </select>

    <label>Sở thích:</label>
    <input type="text" name="like" placeholder="Nhập sở thích mình muốn (cách nhau ,)" required
        style="width: 100%; padding: 8px; margin-bottom: 15px;">

    <label>Mật khẩu:</label>
    <input type="password" name="password" required>

    <div class="form-actions">
        <input type="submit" value="Đăng ký">
    </div>
</form>
@endsection