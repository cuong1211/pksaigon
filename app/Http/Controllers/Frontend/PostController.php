<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Hiển thị danh sách bài viết
     */
    public function index()
    {
        // Lấy bài viết hiện (status = true) với phân trang
        $posts = Post::visible() // Dùng scope visible() thay vì published()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(9);
        // dd($posts);
        // Lấy bài viết nổi bật (3 bài đầu)
        $featuredPosts = Post::visible() // Dùng scope visible()
            ->featured() // Scope featured()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('frontend.views.post.posts', compact('posts', 'featuredPosts'));
    }

    /**
     * Hiển thị chi tiết bài viết
     */
    public function show($slug)
    {
        // Tìm bài viết theo slug (chỉ bài viết hiện)
        $post = Post::where('slug', $slug)
            ->visible() // Dùng scope visible()
            ->with('author')
            ->firstOrFail();

        // Tăng view count
        $post->incrementViews();

        // Lấy bài viết liên quan (4 bài ngẫu nhiên, không bao gồm bài hiện tại)
        $relatedPosts = Post::visible() // Dùng scope visible()
            ->where('id', '!=', $post->id)
            ->with('author')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('frontend.views.post.post_detail', compact('post', 'slug', 'relatedPosts'));
    }
}
