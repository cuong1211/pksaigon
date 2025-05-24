<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;

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
        
        // Các route admin khác sẽ được thêm vào đây
        // Route::resource('patients', PatientController::class);
        // Route::resource('medicines', MedicineController::class);
        // Route::resource('appointments', AppointmentController::class);
        // Route::resource('services', ServiceController::class);
        // Route::resource('contacts', ContactController::class);
        // Route::resource('marketing-staff', MarketingStaffController::class);
    });
});