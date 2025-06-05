<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\SEOHelper;

class PostController extends Controller
{
    /**
     * Hiển thị danh sách bài viết
     */
    public function index()
    {
        $posts = Post::visible()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $seoHelper = new SEOHelper();
        $seoHelper->setTitle('Tin tức & Blog')
            ->setDescription('Cập nhật những thông tin y khoa và sức khỏe mới nhất từ Phòng Khám Sài Gòn')
            ->setKeywords('tin tức y tế, blog sức khỏe, thông tin y khoa, sản phụ khoa');

        return view('frontend.views.post.posts', compact('posts', 'seoHelper'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Tăng lượt xem
        $post->incrementViews();

        // Lấy bài viết liên quan
        $relatedPosts = Post::visible()
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        // SEO
        $seoHelper = new SEOHelper();
        $seoHelper->setTitle($post->title)
            ->setDescription($post->excerpt)
            ->setImage($post->featured_image_url)
            ->setType('article')
            ->setKeywords('tin tức y tế, ' . $post->title);

        return view('frontend.views.post.post_detail', compact('post', 'relatedPosts', 'seoHelper'));
    }
}
