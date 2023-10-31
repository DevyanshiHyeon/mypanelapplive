<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\trashController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
Route::get('/',[MainController::class,'index']);
Route::post('/login',[AuthController::class,'login']);
Route::get('getotp',[AuthController::class,'getotp']);
Route::post('verifyOtp',[AuthController::class,'verifyOtp']);
Route::get('resend-otp',[AuthController::class,'resend_otp']);
    Route::get('/logout',[AuthController::class,'logout']);
    Route::get('dashboard',[MainController::class,'dashboard']);

    Route::get('apps',[ApplicationController::class,'index']);
    Route::get('apps/create',[ApplicationController::class,'create']);
    Route::post('apps',[ApplicationController::class,'store']);
    Route::get('app-trash/{app_id}',[ApplicationController::class,'app_trash']);
    Route::get('app-delete/{app_id}',[ApplicationController::class,'destroy']);
    Route::get('publish-app/{app_id}',[ApplicationController::class,'publish_app']);
    Route::post('app-check',[ApplicationController::class,'app_check']);
    Route::get('application/{app_id}',[ApplicationController::class,'edit']);

    Route::get('trash',[trashController::class,'index']);
    Route::get('restore/{app_id}',[trashController::class,'restore']);

    Route::get('/app-details', [ApplicationController::class,'getAppDetails']);
    Route::get('/check-app-publication', [ApplicationController::class, 'checkPublication']);
    
Route::get('command', function () {
    \Artisan::call('optimize:clear');
    return ("Done");
});