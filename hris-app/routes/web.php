<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AccountController::class)->group(function () {
    Route::get('auth/google', [AccountController::class, 'googleLogin'])->name('auth.google');
    Route::get('auth/google-callback', [AccountController::class, 'googleAuth'])->name('auth.google-callback');
});

Route::get('/logout', [AccountController::class, 'logout'])->name('logout');


Route::get('/leave_management', function () {
    return view('pages.hr.leave_management');
})->name('leave_management');


Route::get('/login', function () {
    return view('pages.login_page');
})->name('login');

Route::get('/employee_management', function () {
    return view('pages.hr.employee_management');
})->name('employee_management');

Route::get('/addEmployee', function () {
    return view('pages.hr.employee_management');
})->name('addEmployee');
Route::post('/addEmployee', [EmployeeController::class, 'store'])->name('addEmployee.store');
Route::get('/employee_management', [EmployeeController::class, 'index'])
     ->name('employee_management');


// User Profile
Route::get('/myProfile', function () {
    return view('pages.profile.userProfile');
})->name('myProfile');
