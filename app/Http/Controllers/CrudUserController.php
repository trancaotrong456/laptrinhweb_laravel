<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
use App\Models\Product;
use App\Models\Category;
use App\Models\Post;
use App\Models\Coupon;

class CrudUserController extends Controller
{
    public function login() { 
        return view('crud_user.login'); 
    }

    public function authUser(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $passwordInDb = (string) $user->password;
            $plainPasswordMatch = ($passwordInDb === $request->password);

            try {
                $hashPasswordMatch = Hash::check($request->password, $passwordInDb);
            } catch (\Throwable $e) {
                $hashPasswordMatch = false;
            }

            if ($hashPasswordMatch || $plainPasswordMatch) {
                if ($plainPasswordMatch) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                }

                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function createUser() { 
        return view('crud_user.create'); 
    }

    public function postUser(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Đã fix: Luôn hash mật khẩu
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('user.listUser')->withSuccess('Đăng ký người dùng thành công!');
    }

    public function listUser(Request $request) {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        $users = $query->paginate(5); 
        return view('crud_user.list', compact('users'));
    }

    public function readUser($id) {
        $user = User::findOrFail($id);
        return view('crud_user.read', compact('user'));
    }

    public function updateUser($id) {
        $user = User::findOrFail($id);
        return view('crud_user.update', compact('user'));
    }

    public function postUpdateUser(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->route('user.listUser')->withSuccess('Cập nhật thông tin thành công!');
    }

    public function deleteUser($id) {
        User::findOrFail($id)->delete();
        return redirect()->route('user.listUser')->withSuccess('Xóa thành công!');
    }

    public function signOut() {
        Auth::logout();
        return redirect()->route('login');
    }

    public function dashboard() {
        // Đếm tổng số lượng
        $totalUsers = User::count();
        $totalProducts = class_exists('\App\Models\Product') ? Product::count() : 0;
        $totalCategories = class_exists('\App\Models\Category') ? Category::count() : 0;
        $totalPosts = class_exists('\App\Models\Post') ? Post::count() : 0;
        $totalCoupons = class_exists('\App\Models\Coupon') ? Coupon::count() : 0;

        // Dữ liệu biểu đồ Bài viết
        $postsStatusCounts = ['draft' => 0, 'published' => 0, 'hidden' => 0];
        if (class_exists('\App\Models\Post')) {
            $postsStatusCounts = [
                'draft' => Post::where('status', 'draft')->count(),
                'published' => Post::where('status', 'published')->count(),
                'hidden' => Post::where('status', 'hidden')->count(),
            ];
        }

        // Dữ liệu biểu đồ Mã giảm giá
        $couponsActiveCounts = ['active' => 0, 'inactive' => 0];
        if (class_exists('\App\Models\Coupon')) {
            $couponsActiveCounts = [
                'active' => Coupon::where('is_active', true)->count(),
                'inactive' => Coupon::where('is_active', false)->count(),
            ];
        }

        return view('crud_user.dashboard', compact(
            'totalUsers', 'totalProducts', 'totalCategories', 
            'totalPosts', 'totalCoupons', 'postsStatusCounts', 'couponsActiveCounts'
        ));
    }
}