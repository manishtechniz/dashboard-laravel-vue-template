<?php

use App\Model\Booking;
use App\Model\Client;
use App\Model\MobileAppRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['auth'])->group(function () {
    Route::get('/', function (Request $request) {
        return "Helo web dashboard";
    });

    Route::get('/login', function (Request $request) {
        return "Hello login";
    })->name('login');
});
