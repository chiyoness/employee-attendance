<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/active-users', [AttendanceController::class, 'activeUsers'])->name('attendance.active-users');
    Route::get('/attendance/history/{user}', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

// Profile routes consolidated into one group
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile', [ProfileController::class, 'updateExt'])->name('profile.update-extended');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/employees/export/csv', [ExportController::class, 'exportCsv'])->name('employees.export.csv');
    Route::get('/employees/export/pdf', [ExportController::class, 'exportPdf'])->name('employees.export.pdf');
    Route::get('/employees/export/excel', [ExportController::class, 'exportExcel'])->name('employees.export.excel');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    
    // Active employees export routes
    Route::get('/active-employees/export/excel', [ExportController::class, 'exportActiveEmployeesExcel'])->name('export.employees.excel');
    Route::get('/active-employees/export/csv', [ExportController::class, 'exportActiveEmployeesCsv'])->name('export.employees.csv');
    Route::get('/active-employees/export/pdf', [ExportController::class, 'exportActiveEmployeesPdf'])->name('export.employees.pdf');
});

// Test route to check middleware functionality
Route::get('/test-admin', function () {
    return 'This route is accessible to everyone';
});

Route::middleware(['role:admin'])->group(function () {
    Route::get('/test-admin-only', function () {
        return 'If you can see this, the role middleware is working correctly!';
    });
});

// Duplicate attendance routes removed as they are already defined above

require __DIR__.'/auth.php';
