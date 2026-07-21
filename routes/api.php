<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientBookingController;
use App\Http\Controllers\Api\ClientEventController;
use App\Http\Controllers\Api\ClientNotificationController;
use App\Http\Controllers\Api\ClientPaymentController;
use App\Http\Controllers\Api\ClientTableController;
use App\Http\Controllers\Api\QrCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/auth/login-otp', [AuthController::class, 'loginOtp']);
Route::post('/auth/google', [AuthController::class, 'googleAuth']);
Route::post('/auth/test-token', [AuthController::class, 'testToken']);

Route::get('/events', [ClientEventController::class, 'index']);
Route::get('/events/{id}', [ClientEventController::class, 'show']);

Route::get('/tables/available', [ClientTableController::class, 'checkAvailability']);
Route::get('/qrcode/{code}', [QrCodeController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Client Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Bookings
    Route::get('/bookings', [ClientBookingController::class, 'index']);
    Route::get('/bookings/{id}', [ClientBookingController::class, 'show']);
    Route::post('/bookings', [ClientBookingController::class, 'store']);
    Route::post('/bookings/{id}/cancel', [ClientBookingController::class, 'cancel']);

    // Tables
    Route::get('/tables/floors', [ClientTableController::class, 'index']);

    // Payments
    Route::post('/payments/pay', [ClientPaymentController::class, 'pay']);

    // Notifications
    Route::get('/notifications', [ClientNotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [ClientNotificationController::class, 'markAsRead']);
    Route::post('/notifications/tokens', [ClientNotificationController::class, 'storeToken']);

    // QR checkin
    Route::post('/qrcode/validate', [QrCodeController::class, 'validateCode']);
});
