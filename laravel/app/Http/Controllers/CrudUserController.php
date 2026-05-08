<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Lưu ý: Nếu bạn đang dùng bảng sv_users cho việc login thì gọi Model SvUser nhé
use App\Models\Product;
use App\Models\Category;
use App\Models\Post;

class CrudUserController extends Controller
{
    public function login() { return view('crud_user.login'); }

   public function authUser(Request $request) {
    // 1. Kiểm tra dữ liệu gửi lên
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    // 2. Tìm trong database xem có ai dùng email này không
    $user = User::where('email', $request->email)->first();

    // 3. Nếu tìm thấy user VÀ mật khẩu gõ vào khớp Y CHANG mật khẩu trong database
    if ($user && $user->password === $request->password) {
        
        // Ép Laravel cho phép đăng nhập luôn không cần hỏi nhiều!
        Auth::login($user);
        
        // Đăng nhập xong thì đá về Dashboard
        return redirect()->route('dashboard'); 
    }

    // 4. Nếu sai email hoặc mật khẩu
    return redirect("login")->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
}

    public function createUser() { return view('crud_user.create'); }

    public function postUser(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $likesString = $request->like ?? '';

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Thêm các trường mới từ giao diện EXE1:
            'dob' => $request->dob,
            'gender' => $request->gender,
            'job' => $request->job,
            'like' => $likesString, 
            
            // Giữ lại nếu database bạn vẫn còn 2 cột này, nếu xóa rồi thì bỏ đi nhé
            'phone' => $request->phone, 
            'address' => $request->address, 
        ]);

        // withSuccess sẽ gửi kèm một thông báo màu xanh lên trang list
        return redirect()->route('user.listUser')->withSuccess('Đăng ký người dùng thành công!');
    }

    public function listUser(Request $request) {
        // Khởi tạo truy vấn
        $query = User::query();

        // Kiểm tra xem có từ khóa tìm kiếm (search) được gửi lên không
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            // Lọc ra những User có tên hoặc email chứa từ khóa tìm kiếm
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        // Dùng paginate(5) để phân trang (5 người/trang) thay vì get() toàn bộ
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

        $user = User::find($id);

        $likesString = $request->like ?? '';

        // Cập nhật thông tin
        $user->name = $request->name;
        $user->email = $request->email;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->job = $request->job;
        $user->like = $likesString;

        // Nếu database bạn còn 2 cột này:
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Chỉ cập nhật mật khẩu nếu người dùng có nhập mật khẩu mới
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
    /*
    |--------------------------------------------------------------------------
    | CHỨC NĂNG CỦA TRƯỞNG NHÓM: THỐNG KÊ TỔNG QUAN
    |--------------------------------------------------------------------------
    */
    public function dashboard() {
        // Đếm tổng số lượng từ các bảng (Model) của các thành viên khác
        // Chú ý: Các bạn kia phải tạo Model xong thì lệnh này mới chạy không lỗi
        $totalUsers = \App\Models\User::count();
        
        // Dùng try-catch hoặc kiểm tra class_exists để tránh lỗi khi các bạn kia chưa kịp tạo Model
        $totalProducts = class_exists('\App\Models\Product') ? \App\Models\Product::count() : 0;
        $totalCategories = class_exists('\App\Models\Category') ? \App\Models\Category::count() : 0;
        $totalPosts = class_exists('\App\Models\Post') ? \App\Models\Post::count() : 0;

        return view('crud_user.dashboard', compact(
            'totalUsers', 
            'totalProducts', 
            'totalCategories', 
            'totalPosts'
        ));
    }
}