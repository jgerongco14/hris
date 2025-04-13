<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmpLeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignEmpController;
use App\Http\Controllers\EmpContributionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TrainingsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login_page');
})->name('login');

// ✅ Login form submission
Route::post('/login', [AccountController::class, 'defaultlogin'])->name('defaultlogin');

// Google login
Route::controller(AccountController::class)->group(function () {
    Route::get('auth/google', 'googleLogin')->name('auth.google');
    Route::get('auth/google-callback', 'googleAuth')->name('auth.google-callback');
});

Route::get('/logout', [AccountController::class, 'logout'])->name('logout');


// Admin
// User Management
Route::get('/admin/user_management', [AdminController::class, 'showUserManagement'])->name('user_management');
Route::post('/admin/user_management/import', [AdminController::class, 'importUserData'])->name('user.store');
Route::post('/admin/user_management/create', [AdminController::class, 'createUser'])->name('user.create');
Route::get('/admin/user_management/{id}', [AdminController::class, 'editUser'])->name('user.edit');
Route::put('/admin/user_management/{id}', [AdminController::class, 'updateUser'])->name('user.update');
Route::delete('/admin/user_management/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');


// Position Management
Route::get('/admin/position_management', [AssignmentController::class, 'showPositionList'])->name('assignment_management');
Route::post('/admin/position_management', [AssignmentController::class, 'storePosition'])->name('assignment.storePosition');
Route::put('/admin/position_management/{id}', [AssignmentController::class, 'updatePosition'])->name('assignment.updatePosition');
Route::delete('/admin/position_management/{id}', [AssignmentController::class, 'deletePosition'])->name('assignment.delete');
Route::post('/admin/import-positions', [AssignmentController::class, 'importPosition'])->name('assignment.importPosition');



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


// Employee Training

Route::prefix('training')->group(function () {
    Route::get('/', [TrainingsController::class, 'showTrainings'])->name('training');
    Route::post('/', [TrainingsController::class, 'createTraining'])->name('training.store');
    Route::delete('/{id}', [TrainingsController::class, 'deleteTraining'])->name('training.delete');
    Route::get('/{id}', [TrainingsController::class, 'editTraining'])->name('training.edit');
    Route::put('/{id}', [TrainingsController::class, 'updateTraining'])->name('training.update');
});



//HR
//Employee Management
Route::get('/addEmployee', function () {
    return view('pages.hr.employee_management');
})->name('addEmployee');
Route::post('/addEmployee', [EmployeeController::class, 'store'])->name('addEmployee.store');
Route::get('/employee', [EmployeeController::class, 'index'])->name('employee_management');
Route::post('/employee/import', [EmployeeController::class, 'importEmp'])->name('employee.import');
Route::get('/employee/{id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
Route::put('/employee/{id}', [EmployeeController::class, 'update']);
Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');



//Leave Management
Route::get('/leave_management', [EmpLeaveController::class, 'index'])->name('leave_management');
Route::get('/leave_management/{id}', [EmpLeaveController::class, 'show'])->name('leave.show');
Route::post('/leave_management/{id}/approve', [EmpLeaveController::class, 'approval'])->name('leave.approval');


//Attendance Management
Route::get('/attendance_management', function () {
    return view('pages.hr.attendance_management');
})->name('attendance_management');
Route::get('/attendance-management', [AttendanceController::class, 'showAttendance'])->name('attendance_management');
Route::post('/attendance/import', [AttendanceController::class, 'import'])->name('attendance.import');


// Position Assignment
Route::post('/hr/assign-position', [AssignEmpController::class, 'empAssignment'])->name('empAssignment');
Route::get('/employee/{id}/positions', [AssignEmpController::class, 'getPositions'])->name('employee.positions');
Route::delete('/employee/assignment/{id}/delete', [AssignEmpController::class, 'deleteAssignment']);


// Reports
Route::get('/reports', function () {
    return view('pages.reports');
})->name('reports');

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportsController::class, 'viewReport'])->name('reports');
    Route::post('/', [ReportsController::class, 'createReport'])->name('reports.create');
    Route::delete('/{id}', [ReportsController::class, 'deleteReport'])->name('reports.delete');
});


// Employee Contribution

Route::post('/contribution', [EmpContributionController::class, 'store'])->name('contribution.store');
Route::post('/contribution/import', [EmpContributionController::class, 'importContributions'])->name('importContributions');
Route::get('/contribution_management', [EmpContributionController::class, 'showContributionManagement'])->name('contribution_management');
Route::get('/contribution-management', [EmpContributionController::class, 'showContributionManagement'])->name('contribution.management');
// Delete Contribution
Route::delete('/contribution/{contribution}', [EmpContributionController::class, 'destroy'])->name('contribution.destroy');
// Export Contribution
Route::get('/contributions/export-word', [EmpContributionController::class, 'exportWord'])->name('contribution.exportWord');
Route::get('/contributions/{id}/edit', [EmpContributionController::class, 'edit'])->name('contribution.edit');
Route::put('/contributions/{id}', [EmpContributionController::class, 'update'])->name('contribution.update');
Route::get('/contribution', [EmpContributionController::class, 'employeeContribution'])->name('contribution.show');


// User Profile
Route::get('/myProfile', [ProfileController::class, 'index'])->name('myProfile');
Route::post('/myProfile', [ProfileController::class, 'update'])->name('profile.update');




// Departments and Offices Management
Route::get('/departments_offices_management', [DepartmentController::class, 'displayManagementPage'])
    ->name('departments_offices_management');
// Departments Routes
Route::prefix('departments')->group(function () {
    Route::get('/', [DepartmentController::class, 'displayDepartmentList'])->name('departments.index');
    Route::post('/', [DepartmentController::class, 'createDepartment'])->name('departments.store');
    Route::post('/import', [DepartmentController::class, 'importDepartment'])->name('departments.import');
    Route::delete('/{id}', [DepartmentController::class, 'deleteDepartment'])->name('departments.destroy');
    Route::delete('/{departmentId}/programs/{programId}', [DepartmentController::class, 'removeProgram'])->name('departments.removeProgram');
});

// Offices Routes
Route::prefix('offices')->group(function () {
    Route::post('/', [OfficeController::class, 'createOffice'])->name('offices.store');
    Route::post('/import', [OfficeController::class, 'importOfficeCSV'])->name('offices.import');
    Route::delete('/{id}', [OfficeController::class, 'deleteOffice'])->name('offices.destroy');
});
