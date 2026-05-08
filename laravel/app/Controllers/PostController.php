<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    // ================= HIỂN THỊ DANH SÁCH =================
    public function index()
    {
        $sliderPosts = Post::where('type', 1)
            ->orderBy('priority', 'desc')
            ->get();

        $smallPosts = Post::where('type', 0)
            ->orderBy('priority', 'desc')
            ->get();

        return view('posts.index_post', compact('sliderPosts', 'smallPosts'));
    }

    // ================= FORM TẠO =================
    public function create()
    {
        return view('posts.create_post');
    }

    // ================= LƯU MỚI =================
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required',
            'type'     => 'required|in:0,1',
            'priority' => 'nullable|integer',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Thêm khuyến mãi thành công!');
    }

    // ================= FORM SỬA =================
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit_post', compact('post'));
    }

    // ================= CẬP NHẬT =================
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required',
            'type'     => 'required|in:0,1',
            'priority' => 'nullable|integer',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($post->image && File::exists(public_path('images/' . $post->image))) {
                File::delete(public_path('images/' . $post->image));
            }

            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    // ================= XÓA =================
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && File::exists(public_path('images/' . $post->image))) {
            File::delete(public_path('images/' . $post->image));
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Xóa khuyến mãi thành công!');
    }
}