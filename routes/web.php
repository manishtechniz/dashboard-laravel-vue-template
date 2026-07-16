<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Helo web dashboard";
});

Route::withoutMiddleware(['auth'])->group(function () {
    Route::get('/login', function (Request $request) {
        return "Helo web login";
    })->name('login');
});  
