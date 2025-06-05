<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\SEOHelper;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 3 bài viết nổi bật để hiển thị trên trang chủ
        $featuredPosts = Post::visible()
            ->featured()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Lấy dịch vụ nổi bật (nếu cần hiển thị trên trang chủ)
        $featuredServices = Service::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Thống kê cơ bản (nếu cần)
        $stats = [
            'total_posts' => Post::visible()->count(),
            'total_services' => Service::where('is_active', true)->count(),
        ];
        $seoHelper = new SEOHelper();
        $seoHelper->setTitle('Trang chủ')
            ->setDescription('Phòng Khám Sài Gòn - Chăm sóc sức khỏe sản phụ khoa chuyên nghiệp với đội ngũ bác sĩ giàu kinh nghiệm và trang thiết bị hiện đại tại TP.HCM')
            ->setKeywords('phòng khám sài gòn, sản phụ khoa, khám thai, điều trị phụ khoa, bác sĩ giàu kinh nghiệm, tp.hcm');
        return view('frontend.views.home', compact(
            'featuredPosts',
            'featuredServices',
            'stats'
        ));
    }
}
