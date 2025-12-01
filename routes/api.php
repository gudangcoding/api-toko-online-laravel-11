<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Selamat Datang di api toko online react native expo dan laravel 11']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/profile/{id}', [AuthController::class, 'profile']);
    Route::put('/profile/{id}', [AuthController::class, 'updateProfile']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{id}/variants', [ProductController::class, 'getVariants']);
    Route::post('/order/{orderId}/refund', [OrderController::class, 'refundOrder']);
    Route::get('/order/payment-callback', [OrderController::class, 'paymentCallback']);
    Route::post('/order/create-order', [OrderController::class, 'createOrder']);
    // RajaOngkir: provinces & cities
    Route::get('/provinces', [OrderController::class, 'getProvinces']);
    Route::get('/cities', [OrderController::class, 'getCities']);

    // Shipping (RajaOngkir tracking)
    Route::post('/shipping/track', [ShippingController::class, 'trackShipment']);
    Route::get('/shipping/couriers', [ShippingController::class, 'getCouriers']);
    Route::get('/shipping/provinces', [ShippingController::class, 'getProvinces']);
    Route::get('/shipping/cities', [ShippingController::class, 'getCities']);
    Route::get('/shipping/districts', [ShippingController::class, 'getDistricts']);
    Route::post('/shipping/ongkir', [ShippingController::class, 'ongkir']);

    // Payments
    Route::get('/payments/pending', [PaymentController::class, 'getPendingPayments']);
    Route::post('/payments/{orderId}/resume', [PaymentController::class, 'resumePayment']);
    Route::get('/payments/{orderId}/status', [PaymentController::class, 'checkStatus']);

    // IRIS / Disbursement
    Route::post('/payments/disburse', [PaymentController::class, 'createDisbursement']);
    Route::post('/payments/disburse/approve', [PaymentController::class, 'approveDisbursement']);
    Route::get('/payments/disburse/{reference}', [PaymentController::class, 'getDisbursementStatus']);
    Route::get('/payments/iris/balance', [PaymentController::class, 'getIrisBalance']);
    Route::post('logout', [AuthController::class, 'logout']);
});
