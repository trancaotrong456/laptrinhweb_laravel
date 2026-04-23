<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Post; // Nhớ phải có dòng này ở đầu
=======
>>>>>>> feature/product
use Illuminate\Http\Request;

class PostController extends Controller
{
<<<<<<< HEAD
    
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
        // 1. Lấy toàn bộ dữ liệu từ Form
        $data = $request->all();

        // 2. Xử lý Upload hình ảnh (Nếu có chọn ảnh)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Đổi tên ảnh để không bị trùng (VD: 1691234567.jpg)
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Lưu ảnh vào thư mục public/images
            $image->move(public_path('images'), $imageName);
            // Ghi tên ảnh vào mảng data để lưu xuống DB
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
        $post = Post::findOrFail($id); // Tìm bài viết, không thấy thì báo lỗi 404
        return view('posts.edit_post', compact('post'));
    }

    // 2. Xử lý lưu dữ liệu vừa sửa
    public function update(Request $request, string $id)
{
    // 1. Tìm bài viết cần sửa
    $post = Post::findOrFail($id);

    // 2. Lấy toàn bộ dữ liệu từ Form (bao gồm title, content, type, priority)
    $data = $request->all();

    // 3. Xử lý nếu sếp có chọn ảnh mới
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        
        // Sửa lỗi dấu chấm thành dấu mũi tên ở đây luôn sếp nhé!
        $image->move(public_path('images'), $imageName);
        
        $data['image'] = $imageName;
    }

    // 4. Cập nhật vào Database
    $post->update($data);

    // 5. Quay về trang danh sách và báo tin vui
    return redirect()->route('posts.index')->with('success', 'Đã cập nhật quảng cáo thành công!');
}}
=======
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
>>>>>>> feature/product
