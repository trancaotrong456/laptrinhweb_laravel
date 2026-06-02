<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CrudUserController extends Controller
{
    // 1. Hiển thị trang đăng nhập
    public function login()
    {
        return view('crud_user.login'); // Hãy đổi thành đường dẫn view đăng nhập thực tế của bạn
    }

    // 2. Xử lý logic Đăng nhập
    public function authUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Nếu là admin thì chuyển hướng vào dashboard, ngược lại về trang chủ
            if (Auth::user()->role === 'admin') { 
                return redirect()->intended('/dashboard');
            }
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    // 3. Hiển thị trang đăng ký
    public function createUser()
    {
        return view('crud_user.register'); 
    }

    // 4. Xử lý logic Đăng ký (Đã bảo mật mã hóa Bcrypt tránh lỗi password)
    public function postUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // 👈 Luôn dùng Hash::make
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // 5. Đăng xuất
    public function signOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /*
    |--------------------------------------------------------------------------
    | CÁC HÀM QUẢN LÝ USER DÀNH CHO ADMIN
    |--------------------------------------------------------------------------
    */
    public function listUser(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        return view('crud_user.list', compact('users'));
    }

    public function readUser($id)
    {
        $user = User::findOrFail($id);
        return view('crud_user.read', compact('user'));
    }

    public function updateUser($id)
    {
        $user = User::findOrFail($id);
        return view('crud_user.update', compact('user'));
    }

    public function postUpdateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('user.listUser')->with('success', 'Cập nhật thành công!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.listUser')->with('success', 'Xóa thành công!');
    }
}
