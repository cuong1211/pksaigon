<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\MedicineImportController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ExaminationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route cho trang chủ frontend
Route::get('/', function () {
    return view('frontend.views.home');
});

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
        Route::resource('posts', PostController::class);
        Route::patch('posts/{id}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');

        // Services Management
        Route::resource('service', ServiceController::class);

        // Medicine Management
        Route::resource('medicine', MedicineController::class);

        // Medicine Import Management
        Route::resource('medicine-import', MedicineImportController::class);

        // Patient Management
        Route::resource('patient', PatientController::class);
        Route::get('patient/{id}/history', [PatientController::class, 'getExaminationHistory'])->name('patient.history');

        // Examination Management
        Route::resource('examination', ExaminationController::class);
        Route::post('examination/{id}/generate-qr', [ExaminationController::class, 'generatePaymentQR'])->name('examination.generatePaymentQR');
        Route::get('examination/{id}/check-payment', [ExaminationController::class, 'checkPaymentStatus'])->name('examination.checkPaymentStatus');
        Route::post('examination/payment-webhook', [ExaminationController::class, 'handlePaymentWebhook'])->name('examination.paymentWebhook');

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

// Public webhook route (không cần auth)
Route::post('/webhook/payment', [ExaminationController::class, 'handlePaymentWebhook'])->name('public.paymentWebhook');

// Route storage link (cho development)
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});
