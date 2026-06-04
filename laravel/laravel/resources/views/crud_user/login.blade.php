@extends('layout')

@section('title', 'Đăng nhập - Siêu thị trực tuyến')

@section('content')
<div style="min-height:calc(100vh - 200px);background:linear-gradient(135deg,#e8f5e9,#f1f8e9);
            display:flex;align-items:center;justify-content:center;padding:40px 16px;">

    <div style="width:100%;max-width:440px;">

        {{-- LOGO --}}
        <div style="text-align:center;margin-bottom:28px;">
            <div style="display:inline-flex;align-items:center;gap:12px;font-size:24px;font-weight:800;color:#2e7d32;">
                <div style="width:50px;height:50px;background:#2e7d32;border-radius:14px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-leaf" style="color:white;font-size:22px;"></i>
                </div>
                Siêu thị trực tuyến
            </div>
        </div>

        {{-- CARD --}}
        <div style="background:white;border-radius:20px;box-shadow:0 8px 32px rgba(0,0,0,.1);padding:36px 32px;">

            <h2 style="font-size:20px;font-weight:800;text-align:center;margin-bottom:6px;color:#212121;">
                Chào mừng trở lại!
            </h2>
            <p style="text-align:center;font-size:13.5px;color:#9e9e9e;margin-bottom:24px;">
                Đăng nhập để tiếp tục mua sắm
            </p>

            {{-- ERROR --}}
            @if($errors->any())
            <div class="alert-st error" style="margin-bottom:16px;">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('user.authUser') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label style="font-size:13px;font-weight:600;color:#616161;display:block;margin-bottom:6px;">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-control-st" style="width:100%;"
                           placeholder="example@email.com">
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label style="font-size:13px;font-weight:600;color:#616161;display:block;margin-bottom:6px;">
                        Mật khẩu
                    </label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="pwdInput" required
                               class="form-control-st" style="width:100%;padding-right:44px;"
                               placeholder="Nhập mật khẩu...">
                        <button type="button" onclick="togglePwd()"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                       color:#9e9e9e;font-size:15px;background:none;border:none;">
                            <i class="far fa-eye" id="pwdEye"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin:14px 0 20px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;color:#616161;">
                        <input type="checkbox" name="remember" style="accent-color:#2e7d32;width:15px;height:15px;">
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" style="font-size:13px;color:#2e7d32;font-weight:600;text-decoration:none;">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-green" style="width:100%;padding:13px;font-size:15px;">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>

            <div style="text-align:center;margin-top:20px;font-size:13.5px;color:#9e9e9e;">
                Chưa có tài khoản?
                <a href="{{ route('user.createUser') }}" class="auth-link" style="color:#2e7d32;font-weight:700;text-decoration:none;">
                    Đăng ký ngay
                </a>
            </div>

        </div>

        <p style="text-align:center;margin-top:20px;font-size:12px;color:#bdbdbd;">
            © {{ date('Y') }} Siêu thị trực tuyến — Design by Trần Cao Trọng
        </p>
    </div>
</div>

<script>
function togglePwd(){
    const inp = document.getElementById('pwdInput');
    const eye = document.getElementById('pwdEye');
    if(inp.type==='password'){ inp.type='text'; eye.className='far fa-eye-slash'; }
    else { inp.type='password'; eye.className='far fa-eye'; }
}
</script>
@endsection