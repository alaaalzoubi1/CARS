<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']); });
Route::post('register', [AuthController::class, 'register']);
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::get('refresh', [AuthController::class,'refresh']);
    Route::get('me', [AuthController::class,'me']);

});
