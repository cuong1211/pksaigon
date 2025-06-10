<?php

namespace App\Providers;

use App\Services\SEOHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

class SEOServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SEOHelper::class, function ($app) {
            return new SEOHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Share SEO data với tất cả views
        View::composer('*', function ($view) {
            $seoHelper = $this->app->make(SEOHelper::class);

            // Set default values cho Phòng Khám Thu Hiền
            $routeName = request()->route()?->getName();

            switch ($routeName) {
                case 'home':
                    $seoHelper->setTitle('Phòng Khám Phụ Sản Thu Hiền - Chuyên khoa sản phụ khoa TP.HCM')
                        ->setDescription('Phòng Khám Phụ Sản Thu Hiền - Chuyên khoa sản phụ khoa với đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại tại Quận 5, TP.HCM. Khám thai, điều trị phụ khoa, tư vấn sức khỏe sinh sản.')
                        ->setKeywords('phòng khám phụ sản thu hiền, sản phụ khoa sài gòn, khám thai quận 5, bác sĩ phụ sản giỏi, siêu âm thai, điều trị phụ khoa');
                    break;

                case 'about':
                    $seoHelper->setTitle('Giới thiệu Phòng Khám Phụ Sản Thu Hiền')
                        ->setDescription('Tìm hiểu về Phòng Khám Phụ Sản Thu Hiền - Đội ngũ bác sĩ chuyên khoa, trang thiết bị hiện đại, phục vụ chăm sóc sức khỏe phụ nữ tại TP.HCM')
                        ->setKeywords('giới thiệu phòng khám thu hiền, đội ngũ bác sĩ, trang thiết bị y tế, chăm sóc sức khỏe phụ nữ');
                    break;

                case 'frontend.services':
                    $seoHelper->setTitle('Dịch vụ chuyên khoa sản phụ khoa - Phòng Khám Thu Hiền')
                        ->setDescription('Các dịch vụ chuyên khoa sản phụ khoa tại Phòng Khám Thu Hiền: khám thai, siêu âm, điều trị phụ khoa, xét nghiệm, tư vấn sức khỏe sinh sản')
                        ->setKeywords('dịch vụ phụ sản, khám thai, siêu âm thai, điều trị phụ khoa, xét nghiệm phụ khoa, tư vấn sinh sản');
                    break;

                case 'frontend.appointment':
                    $seoHelper->setTitle('Đặt lịch khám online - Phòng Khám Phụ Sản Thu Hiền')
                        ->setDescription('Đặt lịch khám online tại Phòng Khám Phụ Sản Thu Hiền. Nhanh chóng, tiện lợi, chuyên nghiệp. Hotline: 0384 518 881')
                        ->setKeywords('đặt lịch khám online, đặt lịch phụ khoa, đặt lịch khám thai, phòng khám thu hiền');
                    break;

                case 'contact':
                    $seoHelper->setTitle('Liên hệ Phòng Khám Phụ Sản Thu Hiền')
                        ->setDescription('Liên hệ Phòng Khám Phụ Sản Thu Hiền - 65 Hùng Vương, Quận 5, TP.HCM. Hotline: 0384 518 881 - 0988 669 292. Tư vấn miễn phí 24/7')
                        ->setKeywords('liên hệ phòng khám thu hiền, địa chỉ phòng khám, hotline tư vấn, phụ khoa quận 5');
                    break;

                case 'frontend.medicines':
                    $seoHelper->setTitle('Thực phẩm chức năng cho phụ nữ - Phòng Khám Thu Hiền')
                        ->setDescription('Thực phẩm chức năng chất lượng cao dành cho phụ nữ tại Phòng Khám Thu Hiền. Hỗ trợ sức khỏe sinh sản, cân bằng nội tiết tố')
                        ->setKeywords('thực phẩm chức năng phụ nữ, vitamin cho bà bầu, hỗ trợ sinh sản, cân bằng nội tiết');
                    break;

                case 'frontend.posts':
                    $seoHelper->setTitle('Tin tức sức khỏe phụ nữ - Phòng Khám Thu Hiền')
                        ->setDescription('Cập nhật tin tức, kiến thức về sức khỏe phụ nữ, thai sản, phụ khoa từ đội ngũ chuyên gia Phòng Khám Thu Hiền')
                        ->setKeywords('tin tức sức khỏe phụ nữ, kiến thức thai sản, chăm sóc phụ khoa, mẹ và bé');
                    break;
            }

            $view->with('seoHelper', $seoHelper);
        });

        // Add middleware để compress HTML
        if ($this->app->environment('production')) {
            $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\GzipMiddleware::class);
            $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\HtmlCacheMiddleware::class);
        }
    }
}
