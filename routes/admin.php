<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/lms-secure-login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/lms-secure-login', [AuthController::class, 'login']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Students CRUD
    Route::get('/students', [StudentController::class, 'index'])->name('admin.students');
    Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

    // Admins CRUD
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.admins');
    Route::post('/admins', [AdminController::class, 'store'])->name('admin.admins.store');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admin.admins.update');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admin.admins.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
