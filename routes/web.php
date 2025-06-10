<?php

use App\Http\Controllers\Api\VietQRController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\MedicineImportController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ExaminationController;
use App\Http\Controllers\Admin\AppointmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\MedicineController as FrontendMedicineController;
use App\Http\Controllers\Frontend\AppointmentController as FrontendAppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes - FRONTEND (có cache và gzip)
|--------------------------------------------------------------------------
*/

// Group các route frontend với SEO middleware
Route::middleware(['gzip', 'html.cache'])->group(function () {
    // Frontend routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');

    // Services Routes
    Route::get('/services', [FrontendServiceController::class, 'index'])->name('frontend.services');
    Route::get('/services/type/{type}', [FrontendServiceController::class, 'indexByType'])->name('frontend.services.type');
    Route::get('/services/{slug}', [FrontendServiceController::class, 'show'])->name('frontend.services.show');

    // Contact Routes - chỉ GET
    Route::get('/contact', [FrontendContactController::class, 'index'])->name('contact');

    // Posts/Blog Routes
    Route::get('/posts', [FrontendPostController::class, 'index'])->name('frontend.posts');
    Route::get('/posts/{slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');

    // Medicines Routes
    Route::get('/medicines', [FrontendMedicineController::class, 'index'])->name('frontend.medicines');
    Route::get('/medicines/{slug}', [FrontendMedicineController::class, 'show'])->name('frontend.medicines.show');

    // Appointment - chỉ GET
    Route::get('/appointment', [FrontendAppointmentController::class, 'index'])->name('frontend.appointment');

    // SEO Routes
    Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
    Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
    Route::get('/sitemap-news.xml', [App\Http\Controllers\SitemapController::class, 'newsSitemap'])->name('sitemap.news');
    Route::get('/sitemap-images.xml', [App\Http\Controllers\SitemapController::class, 'imageSitemap'])->name('sitemap.images');
});

/*
|--------------------------------------------------------------------------
| Web Routes - KHÔNG CACHE (POST, admin, auth)
|--------------------------------------------------------------------------
*/

// Routes cần session nhưng không cache
Route::group([], function () {
    // POST routes cho frontend
    Route::post('/contact', [FrontendContactController::class, 'store'])->name('contact.store');
    Route::post('/appointment', [FrontendAppointmentController::class, 'store'])->name('frontend.appointment.store');

    // API Routes cho Frontend (không cần auth)
    Route::get('/api/services/type/{type}', [FrontendServiceController::class, 'getServicesByType'])->name('frontend.services.by-type');
    Route::get('/api/services', [FrontendAppointmentController::class, 'getServices'])->name('frontend.services.api');

    // Routes cho authentication
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('backend.postLogin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - KHÔNG BAO GIỜ CACHE
|--------------------------------------------------------------------------
*/

// Group routes cho admin (yêu cầu đăng nhập) - KHÔNG CACHE
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/', function () {
            return view('backend.pages.main');
        })->name('admin');

        // Posts Management
        Route::resource('posts', PostController::class);
        Route::get('posts/get-data/{id}', [PostController::class, 'getData'])->name('posts.getData');
        Route::patch('posts/{id}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');

        // Services Management
        Route::resource('service', ServiceController::class);
        Route::get('service/get-data/{id}', [ServiceController::class, 'getData'])->name('service.getData');

        // Medicine Management
        Route::resource('medicine', MedicineController::class);
        Route::get('medicine/get-data/{id}', [MedicineController::class, 'getData'])->name('medicine.getData');

        // Medicine Import Management  
        Route::resource('medicine-import', MedicineImportController::class);
        Route::get('medicine-import/get-data/{id}', [MedicineImportController::class, 'getData'])->name('medicine-import.getData');

        // Patient Management
        Route::resource('patient', PatientController::class);
        Route::get('patient/{id}/history', [PatientController::class, 'getExaminationHistory'])->name('patient.history');

        // Examination Management
        Route::resource('examination', ExaminationController::class);
        Route::post('examination/{id}/generate-qr', [ExaminationController::class, 'generatePaymentQR'])->name('examination.generatePaymentQR');
        Route::get('examination/{id}/check-payment', [ExaminationController::class, 'checkPaymentStatus'])->name('examination.checkPaymentStatus');

        Route::resource('appointment', AppointmentController::class);
        Route::post('appointment/{id}/confirm', [AppointmentController::class, 'confirm'])->name('appointment.confirm');
        Route::post('appointment/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointment.cancel');
        Route::post('appointment/{id}/complete', [AppointmentController::class, 'complete'])->name('appointment.complete');

        Route::prefix('medicine-statistics')->name('medicine-statistics.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'index'])->name('index');
            Route::get('/overview', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'getOverviewStats'])->name('overview');
            Route::get('/import-trends', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'getImportTrends'])->name('import-trends');
            Route::get('/top-medicines', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'getTopImportedMedicines'])->name('top-medicines');
            Route::get('/expiry-report', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'getExpiryReport'])->name('expiry-report');
            Route::get('/type-statistics', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'getTypeStatistics'])->name('type-statistics');
            Route::get('/export', [App\Http\Controllers\Admin\MedicineStatisticsController::class, 'exportReport'])->name('export');
        });

        // Route tạo slug cho service
        Route::get('create-slug', function (Request $request) {
            $name = $request->get('name');
            $modelType = $request->get('modelType');

            $slug = Str::slug($name);

            // Kiểm tra slug có tồn tại không
            if ($modelType == 'service') {
                $exists = App\Models\Service::where('slug', $slug)->exists();
                if ($exists) {
                    $slug = $slug . '-' . time();
                }
            }

            return response()->json(['slug' => $slug]);
        })->name('slug');
    });
});

/*
|--------------------------------------------------------------------------
| Utility Routes
|--------------------------------------------------------------------------
*/

// Route storage link (cho development)
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});

// Test routes và webhook (không cache)
Route::post('examination/{id}/test-callback-simulation', [ExaminationController::class, 'testCallbackSimulation'])
    ->name('examination.testCallbackSimulation');

Route::withoutMiddleware(['web'])->group(function () {
    Route::post('/bank/api/transaction-sync', [VietQRController::class, 'transactionSync'])
        ->middleware('vietqr.auth');
    Route::post('examination/{id}/test-vietqr-real-data', [ExaminationController::class, 'testVietQRWithRealData'])
        ->name('examination.triggerVietQRCallback');
});

// Thêm vào cuối file routes/web.php

// SEO Routes cho Phòng Khám Phụ Sản Thu Hiền
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])
    ->name('robots');

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])
    ->name('sitemap');

// Additional sitemaps
Route::get('/sitemap-news.xml', [App\Http\Controllers\SitemapController::class, 'newsSitemap'])
    ->name('sitemap.news');

Route::get('/sitemap-images.xml', [App\Http\Controllers\SitemapController::class, 'imageSitemap'])
    ->name('sitemap.images');

Route::get('/sitemap-videos.xml', [App\Http\Controllers\SitemapController::class, 'videoSitemap'])
    ->name('sitemap.videos');

// Landing pages cho SEO (có thể thêm các trang landing chuyên biệt)
Route::get('/kham-thai-quan-5', function () {
    $seoHelper = app(\App\Services\SEOHelper::class);
    $seoHelper->setTitle('Khám thai tại Quận 5 - Phòng Khám Phụ Sản Thu Hiền')
        ->setDescription('Dịch vụ khám thai chuyên nghiệp tại Quận 5, TP.HCM. Đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại. Đặt lịch ngay: 0384 518 881')
        ->setKeywords('khám thai quận 5, phòng khám thai sản, bác sĩ sản khoa giỏi, siêu âm thai, khám thai định kỳ');

    return view('frontend.views.home', compact('seoHelper'));
})->name('seo.kham-thai-quan-5');

Route::get('/phu-khoa-sai-gon', function () {
    $seoHelper = app(\App\Services\SEOHelper::class);
    $seoHelper->setTitle('Phụ khoa Sài Gòn - Chuyên khoa phụ nữ Thu Hiền')
        ->setDescription('Điều trị các bệnh phụ khoa tại Sài Gòn. Phòng Khám Thu Hiền chuyên khoa phụ nữ với đội ngũ bác sĩ chuyên nghiệp, bảo mật tuyệt đối')
        ->setKeywords('phụ khoa sài gòn, điều trị phụ khoa, viêm nhiễm phụ khoa, bác sĩ phụ khoa giỏi, khám phụ khoa uy tín');

    return view('frontend.views.home', compact('seoHelper'));
})->name('seo.phu-khoa-sai-gon');

Route::get('/sieu-am-thai-4d', function () {
    $seoHelper = app(\App\Services\SEOHelper::class);
    $seoHelper->setTitle('Siêu âm thai 4D tại Thu Hiền - Công nghệ hiện đại')
        ->setDescription('Dịch vụ siêu âm thai 4D với công nghệ hiện đại nhất tại Phòng Khám Thu Hiền. Hình ảnh rõ nét, an toàn cho mẹ và bé')
        ->setKeywords('siêu âm thai 4D, siêu âm thai, công nghệ siêu âm hiện đại, siêu âm màu, khám thai');

    return view('frontend.views.home', compact('seoHelper'));
})->name('seo.sieu-am-thai-4d');

// Health check cho SEO crawlers
Route::get('/health-seo', function () {
    $checks = [
        'database' => true,
        'cache' => true,
        'sitemap' => true,
        'services_active' => true,
    ];

    try {
        // Test database
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        $checks['database'] = false;
    }

    try {
        // Test cache
        Cache::put('health_check', 'ok', 60);
        $checks['cache'] = Cache::get('health_check') === 'ok';
    } catch (\Exception $e) {
        $checks['cache'] = false;
    }

    try {
        // Test services
        $checks['services_active'] = \App\Models\Service::where('is_active', true)->count() > 0;
    } catch (\Exception $e) {
        $checks['services_active'] = false;
    }

    $allHealthy = array_reduce($checks, function ($carry, $item) {
        return $carry && $item;
    }, true);

    return response()->json([
        'status' => $allHealthy ? 'healthy' : 'unhealthy',
        'clinic_name' => 'Phòng Khám Phụ Sản Thu Hiền',
        'checks' => $checks,
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ], $allHealthy ? 200 : 503);
})->name('health.seo');

// SEO Test routes (chỉ cho development và staging)
if (app()->environment(['local', 'staging'])) {
    Route::get('/seo-test', function () {
        $seoHelper = app(\App\Services\SEOHelper::class);
        $seoHelper->setTitle('Test SEO Page - Thu Hiền Clinic')
            ->setDescription('Testing SEO implementation for Phòng Khám Phụ Sản Thu Hiền')
            ->setKeywords('test, seo, laravel, phòng khám phụ sản thu hiền');

        return view('frontend.views.home', compact('seoHelper'));
    })->name('seo.test');

    Route::get('/seo-debug', function () {
        return response()->json([
            'clinic_info' => [
                'name' => 'Phòng Khám Phụ Sản Thu Hiền',
                'address' => '65 Hùng Vương, Phường 4, Quận 5, TP.HCM',
                'phones' => ['0384518881', '0988669292'],
                'email' => 'info@phongkhamthuhien.com'
            ],
            'app_url' => config('app.url'),
            'current_url' => request()->url(),
            'domain' => request()->getHost(),
            'scheme' => request()->getScheme(),
            'user_agent' => request()->userAgent(),
            'routes' => [
                'robots' => route('robots'),
                'sitemap' => route('sitemap'),
                'sitemap_news' => route('sitemap.news'),
                'sitemap_images' => route('sitemap.images'),
                'home' => route('home'),
                'services' => route('frontend.services'),
                'appointment' => route('frontend.appointment'),
            ],
            'seo_pages' => [
                'kham_thai_quan_5' => route('seo.kham-thai-quan-5'),
                'phu_khoa_sai_gon' => route('seo.phu-khoa-sai-gon'),
                'sieu_am_thai_4d' => route('seo.sieu-am-thai-4d'),
            ],
            'middleware' => [
                'gzip_enabled' => function_exists('gzencode'),
                'cache_store' => config('cache.default'),
                'environment' => app()->environment(),
            ],
            'database' => [
                'services_count' => \App\Models\Service::count(),
                'active_services' => \App\Models\Service::where('is_active', true)->count(),
                'posts_count' => \App\Models\Post::count(),
                'published_posts' => \App\Models\Post::where('status', true)->count(),
                'medicines_count' => \App\Models\Medicine::count(),
            ]
        ]);
    })->name('seo.debug');

    Route::get('/test-schema', function () {
        $seoHelper = app(\App\Services\SEOHelper::class);

        // Test Organization Schema
        $organizationSchema = $seoHelper->generateSchema('Organization');

        // Test Service Schema
        $serviceSchema = $seoHelper->generateSchema('Service', [
            'name' => 'Khám thai định kỳ',
            'description' => 'Dịch vụ khám thai định kỳ chuyên nghiệp',
            'price' => 200000
        ]);

        // Test Article Schema
        $articleSchema = $seoHelper->generateSchema('Article', [
            'title' => 'Chăm sóc sức khỏe phụ nữ',
            'description' => 'Hướng dẫn chăm sóc sức khỏe phụ nữ',
            'published_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ]);

        // Test FAQ Schema
        $faqSchema = $seoHelper->generateSchema('FAQ', [
            'questions' => [
                [
                    '@type' => 'Question',
                    'name' => 'Phòng khám có khám ngoài giờ không?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Phòng khám mở cửa từ 7:00 - 19:00 hàng ngày. Trường hợp khẩn cấp, vui lòng gọi hotline 0384 518 881'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Có cần đặt lịch trước không?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Để được phục vụ tốt nhất, khách hàng nên đặt lịch trước qua hotline hoặc website'
                    ]
                ]
            ]
        ]);

        return view('test-schema', compact('organizationSchema', 'serviceSchema', 'articleSchema', 'faqSchema'));
    })->name('test.schema');
}

// Redirect old URLs to new structure (nếu có)
Route::get('/old-page', function () {
    return redirect()->route('home', [], 301);
});

// AMP pages (nếu muốn hỗ trợ AMP)
Route::get('/amp/{slug}', function ($slug) {
    $post = \App\Models\Post::where('slug', $slug)->where('status', true)->firstOrFail();

    $seoHelper = app(\App\Services\SEOHelper::class);
    $seoHelper->setTitle($post->title . ' - Phòng Khám Phụ Sản Thu Hiền')
        ->setDescription($post->excerpt ?: 'Tin tức sức khỏe phụ nữ từ Phòng Khám Thu Hiền')
        ->setKeywords('tin tức sức khỏe, phụ nữ, thai sản, phụ khoa');

    return view('frontend.amp.post', compact('post', 'seoHelper'));
})->name('amp.post');

// Progressive Web App manifest
Route::get('/manifest.json', function () {
    return response()->json([
        'name' => 'Phòng Khám Phụ Sản Thu Hiền',
        'short_name' => 'Thu Hiền Clinic',
        'description' => 'Phòng khám chuyên khoa sản phụ khoa tại TP.HCM',
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => '#1e85b4',
        'icons' => [
            [
                'src' => asset('frontend/images/favicon.jpg'),
                'sizes' => '192x192',
                'type' => 'image/jpeg'
            ],
            [
                'src' => asset('frontend/images/favicon.jpg'),
                'sizes' => '512x512',
                'type' => 'image/jpeg'
            ]
        ]
    ], 200, [
        'Content-Type' => 'application/manifest+json'
    ]);
})->name('manifest');

// Service Worker cho PWA
Route::get('/sw.js', function () {
    $content = "
const CACHE_NAME = 'thu-hien-clinic-v1';
const urlsToCache = [
  '/',
  '/services',
  '/appointment',
  '/contact',
  '/css/bootstrap.min.css',
  '/css/custom.css',
  '/js/jquery-3.7.1.min.js',
  '/js/bootstrap.min.js'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        if (response) {
          return response;
        }
        return fetch(event.request);
      }
    )
  );
});
";

    return response($content, 200, [
        'Content-Type' => 'application/javascript'
    ]);
})->name('service-worker');
