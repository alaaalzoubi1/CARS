<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;

Route::post('send-verification-code', [VerificationController::class, 'sendVerificationCode']);
Route::post('verify-code', [VerificationController::class, 'verifyCode']);

//Route::middleware(['web'])->group(function () {
//    Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
//    Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']); });
Route::post('register', [AuthController::class, 'register']);
Route::get('categories', [CategoryController::class, 'showCategories']);
Route::get('cars', [CarController::class, 'showCars']);
Route::get('car/{id}',[CarController::class,'details']);
Route::get('carsByCategory/{id}', [CarController::class, 'showCarsByCategory_user']);
Route::get('information', [InformationController::class, 'show_user']);
Route::post('forget-password',[NewPasswordController::class,'forgetPassword']);
Route::post('reset-password',[NewPasswordController::class,'reset']);
Route::middleware('auth:api')->group(function () {
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::get('reservations',[ReservationController::class,'myReservations']);

});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::get('refresh', [AuthController::class,'refresh']);
    Route::get('me', [AuthController::class,'me']);

});
