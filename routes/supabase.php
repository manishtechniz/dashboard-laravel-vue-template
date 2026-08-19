<?php

use App\Model\Booking;
use App\Model\Client;
use App\Model\MobileAppRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['auth'])->group(function () {
    Route::get('/tables', function (Request $request) {
        return view('websocket::index');
    });

    Route::post('/club-tables/{id}/update', [\App\Http\Controllers\Supabase\ClubTableController::class, 'update']);
    Route::post('/club-tables/{id}/toggle-lock', [\App\Http\Controllers\Supabase\ClubTableController::class, 'toggleLock']);
});
