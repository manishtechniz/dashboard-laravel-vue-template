<?php

use App\Model\MobileAppRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Helo web dashboard";
});

Route::withoutMiddleware(['auth'])->group(function () {
    Route::get('/login', function (Request $request) {
        return Auth::guard('admin')->user();
        // echo file_get_contents(storage_path('app/private/firebase/club-app-firebase-notification.json')) . '<br>';
        return "Helo web login";
    })->name('login');
});
