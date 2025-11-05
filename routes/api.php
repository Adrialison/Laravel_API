<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\VariantController;

// Auth
// Rutas públicas (no requieren token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// CRUD principal
Route::apiResource('categories', CategoryController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('variants', VariantController::class);
