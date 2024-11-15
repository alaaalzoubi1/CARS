<?php
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'admin.role'])->group(function () {
    Route::post('add-category', [CategoryController::class, 'store']);
    Route::get('show-categories', [CategoryController::class,'show']);
    Route::get('delete-category/{id}',[CategoryController::class,'delete']);
    Route::post('update-category', [CategoryController::class, 'update']);
});
