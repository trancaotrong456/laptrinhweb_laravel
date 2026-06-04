<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// Không cần use Hash ở đây nữa vì Model đã tự động mã hóa

class CrudUserController extends Controller
{
    // 1. Hiển thị trang đăng nhập
    public function login()
    {
        return view('crud_user.login'); 
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
            if ((int) Auth::user()->role === 1) { 
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
        return view('crud_user.create'); 
    }

    // 4. Xử lý logic Đăng ký (Đã tối ưu mã hóa và thêm sđt, địa chỉ)
    public function postUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Truyền chuỗi thường, Model sẽ tự Hash
            'phone' => $request->phone,
            'address' => $request->address,
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
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->password; // Truyền chuỗi thường, Model sẽ tự Hash
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