<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmpLeaveController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Login form view
Route::get('/login', function () {
    return view('pages.login_page');
})->name('login');

// ✅ Login form submission
Route::post('/login', [AccountController::class, 'defaultlogin'])->name('defaultlogin');

// Google login
Route::controller(AccountController::class)->group(function () {
    Route::get('auth/google', 'googleLogin')->name('auth.google');
    Route::get('auth/google-callback', 'googleAuth')->name('auth.google-callback');
});

Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

//Employee
//Employee Leave Application
Route::post('/leave', [EmpLeaveController::class, 'store'])->name('leave_application.store');
Route::get('/leave', [EmpLeaveController::class, 'showLeave'])->name('leave_application');
Route::get('/leave/{id}', [EmpLeaveController::class, 'editForm'])->name('leave_application.edit');
Route::put('/leave/{id}', [EmpLeaveController::class, 'update'])->name('leave_application.update');

// Employee Attendance
Route::get('/attendance', function () {
    return view('pages.employee.attendance');
})->name('attendance');
Route::get('/attendance', [AttendanceController::class, 'showEmployeeAttendance'])->name('attendance');

//HR
//Employee Management
Route::get('/addEmployee', function () {
    return view('pages.hr.employee_management');
})->name('addEmployee');
Route::post('/addEmployee', [EmployeeController::class, 'store'])->name('addEmployee.store');
Route::get('/employee_management', [EmployeeController::class, 'index'])
    ->name('employee_management');


//Leave Management
Route::get('/leave_management', [EmpLeaveController::class, 'index'])->name('leave_management');
Route::get('/leave_management/{id}', [EmpLeaveController::class, 'show'])->name('leave.show');
Route::post('/leave_management/{id}/approve', [EmpLeaveController::class, 'approval'])->name('leave.approval');


//Attendance Management
Route::get('/attendance_management', function () {
    return view('pages.hr.attendance_management');
})->name('attendance_management');
Route::get('/attendance_management', [AttendanceController::class, 'showAttendance'])->name('attendance_management');
Route::post('/attendance/import', [AttendanceController::class, 'import'])->name('attendance.import');



// User Profile
Route::get('/myProfile', function () {
    return view('pages.profile.userProfile');
})->name('myProfile');
