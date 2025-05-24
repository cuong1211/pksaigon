<?php

use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\BackendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\ColorController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\ProductTypeController;
use App\Http\Controllers\backend\StatisticalController;
use App\Http\Controllers\backend\TypeController;
use App\Http\Controllers\frontend\FrontendController;
use App\Http\Controllers\frontend\OrderController as FrontendOrderController;
use App\Http\Controllers\frontend\UserController as FrontendUserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckLogin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

route::group(["namespace" => "frontend"], function () {
    Route::get('/', function () {
    return view('frontend.views.home');
});
});

Route::group(['prefix' => 'admin'], function () {
   
});
