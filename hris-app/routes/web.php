<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AccountController::class)->group(function () {
    Route::get('auth/google', [AccountController::class, 'googleLogin'])->name('auth.google');
    Route::get('auth/google-callback', [AccountController::class, 'googleAuth'])->name('auth.google-callback');
});


Route::get('/leave_management', function () {
    return view('pages.hr.leave_management');
})->name('leave_management');


Route::get('/login', function () {
    return view('pages.login_page');
})->name('login');
