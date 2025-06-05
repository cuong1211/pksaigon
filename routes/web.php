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
use App\Http\Controllers\Api\VietQRWebhookController;
use App\Http\Controllers\Api\VietQRCallbackController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\MedicineController as FrontendMedicineController;
use App\Http\Controllers\Frontend\AppointmentController as FrontendAppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route cho trang chủ frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Services Routes
Route::get('/services', [FrontendServiceController::class, 'index'])->name('frontend.services');
Route::get('/services/type/{type}', [FrontendServiceController::class, 'indexByType'])->name('frontend.services.type');
Route::get('/services/{slug}', [FrontendServiceController::class, 'show'])->name('frontend.services.show');
// API route để lấy dịch vụ theo loại (cho AJAX nếu cần)
Route::get('/api/services/type/{type}', [FrontendServiceController::class, 'getServicesByType'])->name('frontend.services.by-type');

// Contact Routes
Route::get('/contact', [FrontendContactController::class, 'index'])->name('contact');
Route::post('/contact', [FrontendContactController::class, 'store'])->name('contact.store');

// Posts/Blog Routes
Route::get('/posts', [FrontendPostController::class, 'index'])->name('frontend.posts');
Route::get('/posts/{slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');

// Medicines Routes
Route::get('/medicines', [FrontendMedicineController::class, 'index'])->name('frontend.medicines');
Route::get('/medicines/{slug}', [FrontendMedicineController::class, 'show'])->name('frontend.medicines.show');

// Appointment Routes

Route::get('/appointment', [FrontendAppointmentController::class, 'index'])->name('frontend.appointment');
Route::post('/appointment', [FrontendAppointmentController::class, 'store'])->name('frontend.appointment.store');

// API Routes cho Frontend (không cần auth)
Route::get('/api/services', [FrontendAppointmentController::class, 'getServices'])->name('frontend.services.api');

// Routes cho authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('backend.postLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Group routes cho admin (yêu cầu đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/', function () {
            return view('backend.pages.main');
        })->name('admin');

        // Posts Management
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


// Route storage link (cho development)
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});

// Hoặc nếu VietQR yêu cầu path cụ thể:
Route::post('examination/{id}/test-callback-simulation', [ExaminationController::class, 'testCallbackSimulation'])
    ->name('examination.testCallbackSimulation');

// Test VietQR API với data thật


Route::withoutMiddleware(['web'])->group(function () {
    Route::post('/bank/api/transaction-sync', [VietQRController::class, 'transactionSync'])
        ->middleware('vietqr.auth');
    Route::post('examination/{id}/test-vietqr-real-data', [ExaminationController::class, 'testVietQRWithRealData'])
        ->name('examination.triggerVietQRCallback');
});


// SEO Routes
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])
    ->name('robots');

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])
    ->name('sitemap');

// Additional sitemaps
Route::get('/sitemap-news.xml', [App\Http\Controllers\SitemapController::class, 'newsSitemap'])
    ->name('sitemap.news');

Route::get('/sitemap-images.xml', [App\Http\Controllers\SitemapController::class, 'imageSitemap'])
    ->name('sitemap.images');

// SEO Test routes (chỉ cho development)
if (app()->environment('local')) {
    Route::get('/seo-test', function() {
        $seoHelper = new \App\Services\SEOHelper();
        $seoHelper->setTitle('Test SEO Page')
            ->setDescription('Testing SEO implementation on local environment')
            ->setKeywords('test, seo, laravel, phòng khám');
        
        return view('frontend.views.home', compact('seoHelper'));
    })->name('seo.test');
    
    Route::get('/seo-debug', function() {
        return response()->json([
            'app_url' => config('app.url'),
            'current_url' => request()->url(),
            'domain' => request()->getHost(),
            'scheme' => request()->getScheme(),
            'user_agent' => request()->userAgent(),
            'routes' => [
                'robots' => route('robots'),
                'sitemap' => route('sitemap'),
                'home' => route('home'),
            ],
            'middleware' => [
                'gzip_enabled' => function_exists('gzencode'),
                'cache_store' => config('cache.default'),
            ]
        ]);
    })->name('seo.debug');
}

// Health check cho SEO crawlers
Route::get('/health-seo', function() {
    $checks = [
        'database' => true,
        'cache' => true,
        'sitemap' => true,
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
    
    $allHealthy = array_reduce($checks, function($carry, $item) {
        return $carry && $item;
    }, true);
    
    return response()->json([
        'status' => $allHealthy ? 'healthy' : 'unhealthy',
        'checks' => $checks,
        'timestamp' => now()->toISOString()
    ], $allHealthy ? 200 : 503);
})->name('health.seo');