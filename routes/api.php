<?php

// Tạo file: routes/api.php

use App\Http\Controllers\Api\VietQRController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// VietQR API Routes - Không có CSRF protection
Route::post('/token_generate', [VietQRController::class, 'generateToken']);

