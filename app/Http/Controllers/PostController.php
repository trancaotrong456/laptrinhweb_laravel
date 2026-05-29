<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Autocomplete giống trang categories/index:
        // /khuyen-mai?q=...&suggest=1  => return JSON suggestions
        if ($request->boolean('suggest')) {
            $q = trim((string) $request->input('q', ''));

            if (strlen($q) < 1) {
                return response()->json([]);
            }

            $suggestions = Post::query()
                ->select(['title', 'content'])
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                })
                ->orderByDesc('priority')
                ->take(10)
                ->get()
                ->map(function (Post $post) {
                    return [
                        'title' => $post->title,
                        'content_short' => Str::limit($post->content ?? '', 60),
                    ];
                })
                ->values();

            return response()->json($suggestions);
        }

        $filters = $request->only(['q', 'type', 'status', 'sort']);
        $isAdmin = Auth::check() && (int) Auth::user()->role === 1;

        $query = Post::query();

        // User thường chỉ thấy published (và chỉ khi đến thời gian hiển thị nếu có published_at)
        if (!$isAdmin) {
            $query->where('status', Post::STATUS_PUBLISHED)
                ->where(function ($q) {
                    $q->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                });
        }

        if (!empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        if ($isAdmin && isset($filters['type']) && $filters['type'] !== '') {
            $query->where('type', (int) $filters['type']);
        }

        if ($isAdmin && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $this->applySort($query, $filters['sort'] ?? 'priority');

        // Giữ nguyên behavior cũ: lấy sliderPosts và smallPosts.
        // Nhưng lọc theo query hiện tại.
        $sliderPosts = (clone $query)
            ->where('type', 1)
            ->orderByDesc('priority')
            ->take(4)
            ->get();

        $smallPosts = (clone $query)
            ->where('type', 0)
            ->orderByDesc('priority')
            ->take(12)
            ->get();

        // Bổ sung thêm biến cho view (view hiện tại không cần vẫn chạy OK)
        return view('posts.index_post', [
            'sliderPosts' => $sliderPosts,
            'smallPosts'  => $smallPosts,
            'filters'     => $filters,
            'statuses'    => Post::statuses(),
            'isAdmin'     => $isAdmin,
        ]);
    }

    public function create()
    {
        return view('posts.create_post', [
            'statuses' => Post::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required',
            'type'         => 'required|in:0,1',
            'priority'     => 'nullable|integer|min:0',
            'status'       => ['required', Rule::in(array_keys(Post::statuses()))],
            'published_at' => 'nullable|date',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'title',
            'content',
            'type',
            'priority',
            'status',
            'published_at',
        ]);

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        Post::create($data);
        return redirect()->route('posts.index')->with('success', 'Thêm thành công!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit_post', [
            'post' => $post,
            'statuses' => Post::statuses(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required',
            'type'         => 'required|in:0,1',
            'priority'     => 'nullable|integer|min:0',
            'status'       => ['required', Rule::in(array_keys(Post::statuses()))],
            'published_at' => 'nullable|date',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'title',
            'content',
            'type',
            'priority',
            'status',
            'published_at',
        ]);

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
        return redirect()->route('posts.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && File::exists(public_path('images/' . $post->image))) {
            File::delete(public_path('images/' . $post->image));
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Xóa thành công!');
    }

    private function applySort($query, ?string $sort): void
    {
        match ($sort) {
            'latest'  => $query->orderByDesc('created_at'),
            'oldest'  => $query->orderBy('created_at'),
            'title'   => $query->orderBy('title'),
            default   => $query->orderByDesc('priority')->orderByDesc('created_at'),
        };
    }
}