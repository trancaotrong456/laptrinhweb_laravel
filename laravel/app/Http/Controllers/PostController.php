<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    
   public function index()
{
    // Sắp xếp theo priority giảm dần (Số to đứng trước)
    $sliderPosts = Post::where('type', 1)->orderBy('priority', 'desc')->get();
    
    $smallPosts = Post::where('type', 0)->orderBy('priority', 'desc')->get();

    return view('posts.index_post', compact('sliderPosts', 'smallPosts'));
}
    public function create()
    {
        // Chỉ đúng vào thư mục 'posts' và tên file 'create_post'
        return view('posts.create_post'); 
    }

    public function store(Request $request)
    {
        // VALIDATE
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required',
            'type'     => 'required|in:0,1',
            'priority' => 'nullable|integer',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->all();

        // 2. Xử lý Upload hình ảnh (Nếu có chọn ảnh)
        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images'), $imageName);

            $data['image'] = $imageName;
        }

        // 3. Lưu vào Database
        Post::create($data);

        // 4. Chuyển hướng về trang danh sách và báo thành công
        return redirect()->route('posts.index')->with('success', 'Đăng bài viết thành công!');
    }

// 1. Hiển thị trang chỉnh sửa
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        return view('posts.edit_post', compact('post'));
    }

    // 2. Xử lý lưu dữ liệu vừa sửa
    public function update(Request $request, string $id)
{
    // 1. Tìm bài viết cần sửa
    $post = Post::findOrFail($id);

    // ================= CẬP NHẬT =================
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // VALIDATE
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required',
            'type'     => 'required|in:0,1',
            'priority' => 'nullable|integer',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->all();

        // Nếu có ảnh mới
        if ($request->hasFile('image')) {

            // Xóa ảnh cũ
            if ($post->image && File::exists(public_path('images/' . $post->image))) {
                File::delete(public_path('images/' . $post->image));
            }

            // Upload ảnh mới
            $image = $request->file('image');

            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images'), $imageName);

            $data['image'] = $imageName;
        }

        $post->update($data);
    }

    // 4. Cập nhật vào Database
    $post->update($data);

    // ================= XÓA =================
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Xóa ảnh nếu có
        if ($post->image && File::exists(public_path('images/' . $post->image))) {

            File::delete(public_path('images/' . $post->image));
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Đã xóa khuyến mãi thành công!');
    }
}