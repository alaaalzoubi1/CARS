<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'admin.role'])->group(function () {
    Route::post('add-category', [CategoryController::class, 'store']);
    Route::get('show-categories', [CategoryController::class,'show']);
    Route::get('delete-category/{id}',[CategoryController::class,'delete']);
    Route::post('update-category', [CategoryController::class, 'update']);
    Route::post('add-car',[CarController::class,'store']);
    Route::get('showCarByCategory/{category_id}',[CarController::class,'showByCategory']);
    Route::get('car-details/{id}',[CarController::class,'details']);
    Route::post('update-car/{id}',[CarController::class,'update']);
    Route::post('updateCarFeatures/{id}',[CarController::class,'updateDetails']);
    Route::get('delete-image/{id}',[CarController::class,'deleteImage']);
    Route::post('add-image',[CarController::class,'addImages']);
});
