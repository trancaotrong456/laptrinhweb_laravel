<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Jobs\SendPostNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    // ══════════════════════════════════════════════════════════
    // INDEX — Danh sách bài viết / Khuyến mãi
    // ══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'type', 'status', 'sort', 'tag']);
        $isAdmin = Auth::check() && (int) Auth::user()->role === 1;

        $query = Post::query();

        if (!$isAdmin) {
            $query->where('status', Post::STATUS_PUBLISHED)
                ->where(function ($q) {
                    $q->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                // Ẩn bài đã hết hạn
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                });
        }

        // Tìm kiếm
        if (!empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%")
                    ->orWhere('tags', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo tag
        if (!empty($filters['tag'])) {
            $query->where('tags', 'like', '%' . $filters['tag'] . '%');
        }

        if ($isAdmin && isset($filters['type']) && $filters['type'] !== '') {
            $query->where('type', (int) $filters['type']);
        }

        if ($isAdmin && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $this->applySort($query, $filters['sort'] ?? 'priority');

        $sliderPosts = (clone $query)->where('type', 1)->take(6)->get();
        $smallPosts  = (clone $query)->where('type', 0)->orderBy('is_pinned', 'desc')->paginate(6)->withQueryString();

        // Lấy tất cả tags đang có để hiện bộ lọc tag
        $allTags = Post::whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(fn($t) => array_filter(array_map('trim', explode(',', $t))))
            ->unique()
            ->sort()
            ->values();

        return view('posts.index_post', [
            'sliderPosts' => $sliderPosts,
            'smallPosts'  => $smallPosts,
            'filters'     => $filters,
            'statuses'    => Post::statuses(),
            'isAdmin'     => $isAdmin,
            'allTags'     => $allTags,
        ]);
    }

    // ══════════════════════════════════════════════════════════
    // SHOW — Chi tiết bài viết
    // ══════════════════════════════════════════════════════════
    public function show($id)
    {
        $isAdmin = Auth::check() && (int) Auth::user()->role === 1;

        $query = Post::query();

        if (!$isAdmin) {
            $query->where('status', Post::STATUS_PUBLISHED)
                ->where(function ($q) {
                    $q->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                });
        }

        $post = $query->withCount(['comments', 'likes'])->findOrFail($id);

        // ── Tăng lượt xem (tránh đếm admin) ──────────────────
        if (!$isAdmin) {
            $post->increment('views');
        }

        // ── Bài liên quan ─────────────────────────────────────
        $relatedQuery = Post::where('id', '!=', $id)->where('type', $post->type);
        if (!$isAdmin) {
            $relatedQuery->where('status', Post::STATUS_PUBLISHED)
                ->where(function ($q) {
                    $q->whereNull('published_at')->orWhere('published_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                });
        }
        $relatedPosts = $relatedQuery->orderByDesc('priority')->orderByDesc('created_at')->take(4)->get();

        // ── Comments ──────────────────────────────────────────
        $comments = $post->comments()->with('user')->paginate(10);

        // ── Trạng thái like của user hiện tại ─────────────────
        $userLiked = Auth::check()
            ? PostLike::where('post_id', $id)->where('user_id', Auth::id())->exists()
            : false;

        return view('posts.show_post', [
            'post'         => $post,
            'isAdmin'      => $isAdmin,
            'statuses'     => Post::statuses(),
            'relatedPosts' => $relatedPosts,
            'comments'     => $comments,
            'userLiked'    => $userLiked,
        ]);
    }

    // ══════════════════════════════════════════════════════════
    // CREATE / STORE
    // ══════════════════════════════════════════════════════════
    public function create()
    {
        return view('posts.create_post', ['statuses' => Post::statuses()]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedPostData($request);
        $data['tags'] = $this->normalizeTags($request->input('tags', ''));
        $data['meta_title'] = $request->input('meta_title');
        $data['meta_description'] = $request->input('meta_description');
        $data['is_pinned'] = $request->boolean('is_pinned');

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $post = Post::create($data);

        // Gửi email nếu checkbox được check và bài viết đang published
        if ($request->boolean('send_email') && $post->status === Post::STATUS_PUBLISHED) {
            SendPostNotificationJob::dispatch($post);
        }

        return redirect()->route('posts.index')->with('success', 'Thêm bài viết thành công!');
    }

    // ══════════════════════════════════════════════════════════
    // EDIT / UPDATE
    // ══════════════════════════════════════════════════════════
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit_post', ['post' => $post, 'statuses' => Post::statuses()]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $this->validatedPostData($request);
        $data['tags'] = $this->normalizeTags($request->input('tags', ''));
        $data['meta_title'] = $request->input('meta_title');
        $data['meta_description'] = $request->input('meta_description');
        $data['is_pinned'] = $request->boolean('is_pinned');

        if ($request->hasFile('image')) {
            $this->deleteImage($post);
            $data['image'] = $this->storeImage($request);
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($post);
            $data['image'] = null;
        }

        $oldStatus = $post->status;
        $post->update($data);

        // Gửi email nếu checkbox được check (và bài đang published)
        if ($request->boolean('send_email') && $post->status === Post::STATUS_PUBLISHED) {
            SendPostNotificationJob::dispatch($post);
        }

        return redirect()->route('posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    // ══════════════════════════════════════════════════════════
    // DESTROY
    // ══════════════════════════════════════════════════════════
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $this->deleteImage($post);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Xóa bài viết thành công!');
    }

    // ══════════════════════════════════════════════════════════
    // COMMENT — Thêm bình luận
    // ══════════════════════════════════════════════════════════
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = PostComment::create([
            'post_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'user_name' => Auth::user()->name,
                    'user_initial' => strtoupper(substr(Auth::user()->name, 0, 1)),
                    'content' => nl2br(e($comment->content)),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_edit' => true,
                    'update_url' => route('posts.comment.update', $comment->id),
                    'can_delete' => true,
                    'delete_url' => route('posts.comment.destroy', $comment->id)
                ]
            ]);
        }

        return back()->with('comment_success', 'Bình luận của bạn đã được gửi!');
    }

    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = PostComment::findOrFail($commentId);

        // Chỉ chủ bình luận mới được sửa
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền sửa bình luận này.');
        }

        $comment->update([
            'content' => $request->input('content')
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'content' => nl2br(e($comment->content)),
                'raw_content' => $comment->content,
                'message' => 'Đã cập nhật bình luận.'
            ]);
        }

        return back()->with('comment_success', 'Đã cập nhật bình luận.');
    }

    public function destroyComment(Request $request, $commentId)
    {
        $comment = PostComment::findOrFail($commentId);

        // Chỉ chủ bình luận hoặc admin mới được xóa
        $isAdmin = (int) Auth::user()->role === 1;
        if (!$isAdmin && $comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('comment_success', 'Đã xóa bình luận.');
    }

    // ══════════════════════════════════════════════════════════
    // LIKE — Toggle like bài viết
    // ══════════════════════════════════════════════════════════
    public function toggleLike($id)
    {
        $post = Post::findOrFail($id);

        $existing = PostLike::where('post_id', $id)->where('user_id', Auth::id())->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PostLike::create(['post_id' => $id, 'user_id' => Auth::id()]);
            $liked = true;
        }

        $likeCount = $post->likes()->count();

        if (request()->expectsJson()) {
            return response()->json(['liked' => $liked, 'count' => $likeCount]);
        }

        return back();
    }

    // ══════════════════════════════════════════════════════════
    // AJAX SEARCH (live search cho khách)
    // ══════════════════════════════════════════════════════════
    public function searchAjax(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $posts = Post::where('status', Post::STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('tags', 'like', "%{$q}%");
            })
            ->orderByDesc('priority')
            ->take(6)
            ->get(['id', 'title', 'image', 'type', 'created_at']);

        return response()->json($posts->map(fn($p) => [
            'id'    => $p->id,
            'title' => $p->title,
            'image' => $p->image ? asset('images/' . $p->image) : null,
            'url'   => route('posts.show', $p->id),
            'date'  => $p->created_at->format('d/m/Y'),
            'type'  => $p->type,
        ]));
    }

    // ══════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════
    private function validatedPostData(Request $request): array
    {
        return $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'type'       => 'nullable|integer',
            'is_pinned'  => 'nullable|boolean',
            'priority'   => 'nullable|integer|min:0',
            'status'     => ['required', Rule::in(array_keys(Post::statuses()))],
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
    }

    private function normalizeTags(?string $raw): ?string
    {
        if (empty($raw)) {
            return null;
        }
        
        // "Flash Sale , Mới Về, HOT" → "flash-sale,moi-ve,hot"
        $tags = array_filter(array_map(function ($t) {
            return strtolower(trim(preg_replace('/\s+/', '-', trim($t))));
        }, explode(',', $raw)));
        
        return empty($tags) ? null : implode(',', $tags);
    }

    private function storeImage(Request $request): string
    {
        $image     = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $targetDir = public_path('images');

        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        copy($image->getRealPath(), $targetDir . DIRECTORY_SEPARATOR . $imageName);
        @unlink($image->getRealPath());

        return $imageName;
    }

    private function deleteImage(Post $post): void
    {
        if ($post->image) {
            File::delete(public_path('images/' . $post->image));
        }
    }

    private function applySort($query, ?string $sort): void
    {
        // Luôn ưu tiên ghim lên trên cùng ở mọi kiểu sắp xếp
        $query->orderByDesc('is_pinned');

        match ($sort) {
            'latest'  => $query->orderByDesc('created_at'),
            'oldest'  => $query->orderBy('created_at'),
            'title'   => $query->orderBy('title'),
            'views'   => $query->orderByDesc('views'),
            'likes'   => $query->withCount('likes')->orderByDesc('likes_count'),
            default   => $query->orderByDesc('priority')->orderByDesc('created_at'),
        };
    }
}
