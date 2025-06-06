<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Service;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "\n";
        $robots .= "# Disallow admin areas\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /storage/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /login\n";
        $robots .= "Disallow: /logout\n";
        $robots .= "\n";
        $robots .= "# Disallow search and filters\n";
        $robots .= "Disallow: /*?search=*\n";
        $robots .= "Disallow: /*?filter=*\n";
        $robots .= "Disallow: /*?page=*\n";
        $robots .= "\n";
        $robots .= "# Allow important static files\n";
        $robots .= "Allow: /css/\n";
        $robots .= "Allow: /js/\n";
        $robots .= "Allow: /images/\n";
        $robots .= "Allow: /frontend/\n";
        $robots .= "\n";
        $robots .= "# Crawl delay\n";
        $robots .= "Crawl-delay: 1\n";
        $robots .= "\n";
        $robots .= "# Sitemap location\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($robots, 200, [
            'Content-Type' => 'text/plain; charset=utf-8'
        ]);
    }

    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Trang chủ - Priority cao nhất
        $sitemap .= $this->addUrl(route('home'), now()->toDateString(), 'daily', '1.0');

        // Trang tĩnh quan trọng
        $sitemap .= $this->addUrl(route('about'), now()->toDateString(), 'monthly', '0.9');
        $sitemap .= $this->addUrl(route('contact'), now()->toDateString(), 'monthly', '0.8');
        $sitemap .= $this->addUrl(route('frontend.appointment'), now()->toDateString(), 'weekly', '0.9');

        // Trang danh mục dịch vụ
        $sitemap .= $this->addUrl(route('frontend.services'), now()->toDateString(), 'weekly', '0.9');

        // Dịch vụ theo loại - Tập trung vào sản phụ khoa
        $sitemap .= $this->addUrl(route('frontend.services.type', 'procedure'), now()->toDateString(), 'weekly', '0.9');
        $sitemap .= $this->addUrl(route('frontend.services.type', 'laboratory'), now()->toDateString(), 'weekly', '0.8');
        $sitemap .= $this->addUrl(route('frontend.services.type', 'other'), now()->toDateString(), 'weekly', '0.7');

        // Từng dịch vụ cụ thể - Priority cao hơn cho dịch vụ chính
        $services = Service::where('is_active', true)->get();
        foreach ($services as $service) {
            // Dịch vụ chính của phụ sản có priority cao hơn
            $priority = $this->getServicePriority($service);
            $sitemap .= $this->addUrl(
                route('frontend.services.show', $service->slug),
                $service->updated_at->toDateString(),
                'monthly',
                $priority
            );
        }

        // Trang sản phẩm - thực phẩm chức năng
        $sitemap .= $this->addUrl(route('frontend.medicines'), now()->toDateString(), 'weekly', '0.7');

        // Từng sản phẩm
        $medicines = Medicine::where('is_active', true)->get();
        foreach ($medicines as $medicine) {
            if ($medicine->slug) {
                $sitemap .= $this->addUrl(
                    route('frontend.medicines.show', $medicine->slug),
                    $medicine->updated_at->toDateString(),
                    'monthly',
                    '0.5'
                );
            }
        }

        // Trang tin tức - ưu tiên tin tức sức khỏe phụ nữ
        $sitemap .= $this->addUrl(route('frontend.posts'), now()->toDateString(), 'daily', '0.8');

        // Từng bài viết
        $posts = Post::where('status', true)->get();
        foreach ($posts as $post) {
            $priority = $post->is_featured ? '0.8' : '0.6';
            $sitemap .= $this->addUrl(
                route('frontend.posts.show', $post->slug),
                $post->updated_at->toDateString(),
                'weekly',
                $priority
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    private function addUrl($loc, $lastmod, $changefreq, $priority)
    {
        $url = "  <url>\n";
        $url .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        $url .= "    <lastmod>{$lastmod}</lastmod>\n";
        $url .= "    <changefreq>{$changefreq}</changefreq>\n";
        $url .= "    <priority>{$priority}</priority>\n";
        $url .= "  </url>\n";

        return $url;
    }

    /**
     * Xác định priority cho dịch vụ dựa trên chuyên khoa
     */
    private function getServicePriority($service)
    {
        // Keywords cho dịch vụ chính của phụ sản
        $primaryKeywords = [
            'khám thai',
            'siêu âm',
            'phụ khoa',
            'sản khoa',
            'sinh đẻ',
            'thai sản',
            'viêm nhiễm',
            'kinh nguyệt',
            'buồng trứng'
        ];

        $serviceName = strtolower($service->name);
        $serviceDesc = strtolower($service->description ?? '');

        foreach ($primaryKeywords as $keyword) {
            if (strpos($serviceName, $keyword) !== false || strpos($serviceDesc, $keyword) !== false) {
                return '0.9'; // Priority cao cho dịch vụ chính
            }
        }

        return '0.7'; // Priority thường cho dịch vụ khác
    }

    // News sitemap cho Google News
    public function newsSitemap()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        // Chỉ lấy bài viết trong 2 ngày gần nhất cho Google News
        $recentPosts = Post::where('status', true)
            ->where('published_at', '>=', now()->subDays(2))
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($recentPosts as $post) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . route('frontend.posts.show', $post->slug) . "</loc>\n";
            $sitemap .= "    <news:news>\n";
            $sitemap .= "      <news:publication>\n";
            $sitemap .= "        <news:name>Phòng Khám Phụ Sản Thu Hiền</news:name>\n";
            $sitemap .= "        <news:language>vi</news:language>\n";
            $sitemap .= "      </news:publication>\n";
            $sitemap .= "      <news:publication_date>" . $post->published_at->toISOString() . "</news:publication_date>\n";
            $sitemap .= "      <news:title><![CDATA[" . $post->title . "]]></news:title>\n";
            $sitemap .= "      <news:keywords><![CDATA[sức khỏe phụ nữ, phụ sản, thu hiền]]></news:keywords>\n";
            $sitemap .= "    </news:news>\n";
            $sitemap .= "  </url>\n";
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    // Image sitemap
    public function imageSitemap()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Services với hình ảnh
        $services = Service::where('is_active', true)->whereNotNull('image')->get();
        foreach ($services as $service) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . route('frontend.services.show', $service->slug) . "</loc>\n";
            $sitemap .= "    <image:image>\n";
            $sitemap .= "      <image:loc>" . $service->image_url . "</image:loc>\n";
            $sitemap .= "      <image:title><![CDATA[" . $service->name . " - Phòng Khám Phụ Sản Thu Hiền]]></image:title>\n";
            $sitemap .= "      <image:caption><![CDATA[Dịch vụ " . $service->name . " chuyên nghiệp tại Phòng Khám Phụ Sản Thu Hiền, Quận 5, TP.HCM]]></image:caption>\n";
            $sitemap .= "      <image:geo_location>Quận 5, TP. Hồ Chí Minh</image:geo_location>\n";
            $sitemap .= "    </image:image>\n";
            $sitemap .= "  </url>\n";
        }

        // Posts với featured image
        $posts = Post::where('status', true)->whereNotNull('featured_image')->get();
        foreach ($posts as $post) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . route('frontend.posts.show', $post->slug) . "</loc>\n";
            $sitemap .= "    <image:image>\n";
            $sitemap .= "      <image:loc>" . $post->featured_image_url . "</image:loc>\n";
            $sitemap .= "      <image:title><![CDATA[" . $post->title . "]]></image:title>\n";
            $sitemap .= "      <image:caption><![CDATA[Bài viết về sức khỏe phụ nữ từ Phòng Khám Phụ Sản Thu Hiền]]></image:caption>\n";
            $sitemap .= "    </image:image>\n";
            $sitemap .= "  </url>\n";
        }

        // Medicines với hình ảnh
        $medicines = Medicine::where('is_active', true)->whereNotNull('image')->get();
        foreach ($medicines as $medicine) {
            if ($medicine->slug) {
                $sitemap .= "  <url>\n";
                $sitemap .= "    <loc>" . route('frontend.medicines.show', $medicine->slug) . "</loc>\n";
                $sitemap .= "    <image:image>\n";
                $sitemap .= "      <image:loc>" . $medicine->image_url . "</image:loc>\n";
                $sitemap .= "      <image:title><![CDATA[" . $medicine->name . "]]></image:title>\n";
                $sitemap .= "      <image:caption><![CDATA[" . $medicine->name . " - Thực phẩm chức năng chất lượng tại Phòng Khám Thu Hiền]]></image:caption>\n";
                $sitemap .= "    </image:image>\n";
                $sitemap .= "  </url>\n";
            }
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    /**
     * Video sitemap (nếu có video)
     */
    public function videoSitemap()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";

        // Ví dụ nếu có video giới thiệu dịch vụ
        $sitemap .= "  <url>\n";
        $sitemap .= "    <loc>" . route('about') . "</loc>\n";
        $sitemap .= "    <video:video>\n";
        $sitemap .= "      <video:thumbnail_loc>" . asset('frontend/images/video-thumbnail.jpg') . "</video:thumbnail_loc>\n";
        $sitemap .= "      <video:title><![CDATA[Giới thiệu Phòng Khám Phụ Sản Thu Hiền]]></video:title>\n";
        $sitemap .= "      <video:description><![CDATA[Video giới thiệu về dịch vụ chăm sóc sức khỏe phụ nữ chuyên nghiệp tại Phòng Khám Phụ Sản Thu Hiền]]></video:description>\n";
        $sitemap .= "      <video:duration>120</video:duration>\n";
        $sitemap .= "      <video:publication_date>" . now()->toISOString() . "</video:publication_date>\n";
        $sitemap .= "      <video:family_friendly>yes</video:family_friendly>\n";
        $sitemap .= "      <video:tag>phụ sản</video:tag>\n";
        $sitemap .= "      <video:tag>sức khỏe phụ nữ</video:tag>\n";
        $sitemap .= "      <video:tag>thu hiền</video:tag>\n";
        $sitemap .= "      <video:category>Healthcare</video:category>\n";
        $sitemap .= "    </video:video>\n";
        $sitemap .= "  </url>\n";

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }
}
