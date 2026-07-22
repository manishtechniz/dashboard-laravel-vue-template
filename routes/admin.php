<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminTestController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\Facades\Route;

/**
 * Without Authentication, the following routes will be accessible to everyone. 
 */
Route::withoutMiddleware(['auth:admin'])->group(function () {
    Route::get('auth/login', [AdminAuthController::class, 'index'])->name('login');
    Route::post('auth/login', [AdminAuthController::class, 'login'])->name('verify_login');
});

/**
 * User Management Routes
 */
Route::group(['prefix' => 'users'], function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/', [AdminUserController::class, 'store'])->name('users.store');
    Route::post('/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::get('/edit/{id}', [AdminUserController::class, 'index'])->name('users.edit');
    Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('users.delete');
});

/**
 * Profile Management Routes
 */
Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

Route::get('/', function () {
    return view('admin::test');
});

Route::get('test', [AdminTestController::class, 'test']);

Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'roles'], function () {
    Route::get('/', [AdminRoleController::class, 'index'])->name('roles.index');
    Route::post('/', [AdminRoleController::class, 'store'])->name('roles.store');
    Route::post('/{id}', [AdminRoleController::class, 'update'])->name('roles.update');
    Route::delete('/{id}', [AdminRoleController::class, 'destroy'])->name('roles.delete');
});

/**
 * Client Management Routes
 */
Route::group(['prefix' => 'clients'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminClientController::class, 'index'])->name('clients.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminClientController::class, 'store'])->name('clients.store');
    Route::post('/{id}', [App\Http\Controllers\Admin\AdminClientController::class, 'update'])->name('clients.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminClientController::class, 'index'])->name('clients.edit');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminClientController::class, 'destroy'])->name('clients.delete');
});

/**
 * Club & Branch Management Routes
 */
Route::group(['prefix' => 'clubs'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminClubController::class, 'index'])->name('clubs.index');
    Route::post('/club', [App\Http\Controllers\Admin\AdminClubController::class, 'storeClub'])->name('clubs.store_club');
    Route::post('/club/{id}', [App\Http\Controllers\Admin\AdminClubController::class, 'updateClub'])->name('clubs.update_club');
    Route::delete('/club/{id}', [App\Http\Controllers\Admin\AdminClubController::class, 'destroyClub'])->name('clubs.delete_club');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminClubController::class, 'index'])->name('clubs.edit');

    Route::post('/branch', [App\Http\Controllers\Admin\AdminClubController::class, 'storeBranch'])->name('clubs.store_branch');
    Route::post('/branch/{id}', [App\Http\Controllers\Admin\AdminClubController::class, 'updateBranch'])->name('clubs.update_branch');
    Route::delete('/branch/{id}', [App\Http\Controllers\Admin\AdminClubController::class, 'destroyBranch'])->name('clubs.delete_branch');
    Route::get('/branch/edit/{id}', [App\Http\Controllers\Admin\AdminClubController::class, 'index'])->name('branches.edit');
});

/**
 * Floor Management Routes
 */
Route::group(['prefix' => 'floors'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminFloorController::class, 'index'])->name('floors.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminFloorController::class, 'store'])->name('floors.store');
    Route::post('/{id}', [App\Http\Controllers\Admin\AdminFloorController::class, 'update'])->name('floors.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminFloorController::class, 'index'])->name('floors.edit');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminFloorController::class, 'destroy'])->name('floors.delete');
});

/**
 * Table Management Routes
 */
Route::group(['prefix' => 'tables'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminTableController::class, 'index'])->name('tables.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminTableController::class, 'store'])->name('tables.store');
    Route::put('/{id}', [App\Http\Controllers\Admin\AdminTableController::class, 'update'])->name('tables.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminTableController::class, 'index'])->name('tables.edit');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminTableController::class, 'destroy'])->name('tables.delete');
});

/**
 * Event Management Routes
 */
Route::group(['prefix' => 'events'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminEventController::class, 'index'])->name('events.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminEventController::class, 'store'])->name('events.store');
    Route::post('/{id}', [App\Http\Controllers\Admin\AdminEventController::class, 'update'])->name('events.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminEventController::class, 'index'])->name('events.edit');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminEventController::class, 'destroy'])->name('events.delete');
});

/**
 * Booking Management Routes
 */
Route::group(['prefix' => 'bookings'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminBookingController::class, 'index'])->name('bookings.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminBookingController::class, 'store'])->name('bookings.store');
    Route::post('/{id}', [App\Http\Controllers\Admin\AdminBookingController::class, 'update'])->name('bookings.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminBookingController::class, 'index'])->name('bookings.edit');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminBookingController::class, 'destroy'])->name('bookings.delete');
});

/**
 * Payment & Transaction Routes
 */
Route::group(['prefix' => 'payments'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminPaymentController::class, 'store'])->name('payments.store');
    Route::post('/{id}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'update'])->name('payments.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.edit');
});

/**
 * Promo Code Routes
 */
Route::group(['prefix' => 'promo-codes'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminPromoCodeController::class, 'index'])->name('promo_codes.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminPromoCodeController::class, 'store'])->name('promo_codes.store');
    Route::post('/{id}', [App\Http\Controllers\Admin\AdminPromoCodeController::class, 'update'])->name('promo_codes.update');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\AdminPromoCodeController::class, 'index'])->name('promo_codes.edit');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminPromoCodeController::class, 'destroy'])->name('promo_codes.delete');
});

/**
 * Notification Routes
 */
Route::group(['prefix' => 'notifications'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminNotificationController::class, 'store'])->name('notifications.store');
});

/**
 * Review Routes
 */
Route::group(['prefix' => 'reviews'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/{id}', [App\Http\Controllers\Admin\AdminReviewController::class, 'destroy'])->name('reviews.delete');
});

/**
 * Setting Routes
 */
Route::group(['prefix' => 'settings'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/', [App\Http\Controllers\Admin\AdminSettingController::class, 'store'])->name('settings.store');
});

/**
 * Audit Log Routes
 */
Route::group(['prefix' => 'audit-logs'], function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminAuditLogController::class, 'index'])->name('audit_logs.index');
});

/**
 * Upload file.
 */
Route::post('/upload-url', [UploadFileController::class, 'getUploadUrl']);
