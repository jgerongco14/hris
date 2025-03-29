<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmpLeaveController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AccountController::class)->group(function () {
    Route::get('auth/google', [AccountController::class, 'googleLogin'])->name('auth.google');
    Route::get('auth/google-callback', [AccountController::class, 'googleAuth'])->name('auth.google-callback');
});

Route::get('/logout', [AccountController::class, 'logout'])->name('logout');


Route::get('/login', function () {
    return view('pages.login_page');
})->name('login');

//Employee
// Route::get('/employee', function () {
//     return view('pages.employee.leave');
// })->name('leave_application');
Route::post('/employee', [EmpLeaveController::class, 'store'])->name('leave_application.store');
Route::get('/employee', [EmpLeaveController::class, 'showLeave'])->name('leave_application');
Route::get('/employee/{id}', [EmpLeaveController::class, 'editForm'])->name('leave_application.edit');
Route::put('/employee/{id}', [EmpLeaveController::class, 'update'])->name('leave_application.update');



//HR
//Employee Management
Route::get('/addEmployee', function () {
    return view('pages.hr.employee_management');
})->name('addEmployee');
Route::post('/addEmployee', [EmployeeController::class, 'store'])->name('addEmployee.store');
Route::get('/employee_management', [EmployeeController::class, 'index'])
    ->name('employee_management');


//Leave Management
// Route::get('/leave_management', function () {
//     return view('pages.hr.leave_management');
// })->name('leave_management');
Route::get('/leave_management', [EmpLeaveController::class, 'index'])->name('leave_management');
Route::get('/leave_management/{id}', [EmpLeaveController::class, 'show'])->name('leave.show');
Route::post('/leave_management/{id}/approve', [EmpLeaveController::class, 'approval'])->name('leave.approval');



// User Profile
Route::get('/myProfile', function () {
    return view('pages.profile.userProfile');
})->name('myProfile');
