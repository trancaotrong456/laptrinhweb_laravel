<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'type', 'status', 'sort']);
        $isAdmin = Auth::check() && (int) Auth::user()->role === 1;

        $query = Post::query();

        if (!$isAdmin) {
            $query->where('status', Post::STATUS_PUBLISHED)
                ->where(function ($query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                });
        }

        if (!empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
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

        $sliderPosts = (clone $query)->where('type', 1)->take(6)->get();
        $smallPosts  = (clone $query)->where('type', 0)->paginate(6)->withQueryString();

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
        $data = $this->validatedPostData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Thêm khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('posts.edit_post', [
            'post'     => $post,
            'statuses' => Post::statuses(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $this->validatedPostData($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($post);
            $data['image'] = $this->storeImage($request);
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($post);
            $data['image'] = null;
        }

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $this->deleteImage($post);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Xóa khuyến mãi thành công!');
    }

    private function validatedPostData(Request $request): array
    {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'type'         => 'required|in:0,1',
            'priority'     => 'nullable|integer|min:0',
            'status'       => ['required', Rule::in(array_keys(Post::statuses()))],
            'published_at' => 'nullable|date',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function storeImage(Request $request): string
    {
        $image     = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images', $imageName);

        return $imageName;
    }

    private function deleteImage(Post $post): void
    {
        if ($post->image) {
            Storage::delete('public/images/' . $post->image);
        }
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