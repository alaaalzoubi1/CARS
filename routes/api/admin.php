<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ReservationController;
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
    Route::post('add-image',[CarController::class,'addImage']);
    Route::post('add-link', [LinkController::class, 'add']);
    Route::get('show-links', [LinkController::class, 'show']);
    Route::delete('delete-link/{id}', [LinkController::class, 'delete']);
    Route::post('add-information', [InformationController::class, 'addOrUpdate']);
    Route::get('show-information', [InformationController::class, 'show']);
    Route::delete('delete-information', [InformationController::class, 'delete']);
    Route::get('reservations', [ReservationController::class, 'getAll']);
    Route::get('reservations/pending', [ReservationController::class, 'getPending']);
    Route::get('reservations/canceled', [ReservationController::class, 'getCanceled']);
    Route::get('reservations/approved', [ReservationController::class, 'getApproved']);
    Route::post('reservations/changeStatus', [ReservationController::class, 'updateStatus']);
    Route::get('hide-unhide/{id}',[CarController::class,'hide_unhide_car']);
    Route::delete('delete/{id}',[CarController::class,'delete']);
    Route::get('/cars/search', [CarController::class, 'search']);
});
