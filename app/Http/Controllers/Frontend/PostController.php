<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
                    ->with('author')
                    ->orderBy('published_at', 'desc')
                    ->paginate(12);
        
        $featuredPosts = Post::published()
                            ->featured()
                            ->with('author')
                            ->orderBy('published_at', 'desc')
                            ->limit(3)
                            ->get();

        return view('frontend.views.post.posts', compact('posts', 'featuredPosts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
                   ->published()
                   ->with('author')
                   ->firstOrFail();

        // Tăng view count
        $post->incrementViews();

        // Lấy bài viết liên quan
        $relatedPosts = Post::published()
                           ->where('id', '!=', $post->id)
                           ->inRandomOrder()
                           ->limit(4)
                           ->get();

        return view('frontend.views.post.post_detail', compact('post', 'slug', 'relatedPosts'));
    }
}