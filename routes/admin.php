<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminTestController;
use App\Http\Controllers\Admin\AdminUserController;
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
    Route::get('/edit/{id?}', [AdminUserController::class, 'edit'])->name('users.edit');
});

/**
 * Profile Management Routes
 */
Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

Route::get('/', function() {
    return view('admin::test');
});

Route::get('test', [AdminTestController::class, 'test']);

Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'roles'], function () {
    Route::get('/', [AdminRoleController::class, 'index'])->name('roles.index');
}); 

