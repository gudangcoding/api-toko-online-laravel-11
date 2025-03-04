<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/profile/{id}', [AuthController::class, 'profile']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/order/{orderId}/refund', [OrderController::class, 'refundOrder']);
    Route::get('/order/payment-callback', [OrderController::class, 'paymentCallback']);
    Route::post('/order/create-order', [OrderController::class, 'createOrder']);
    Route::post('logout', [AuthController::class, 'logout']);
});
