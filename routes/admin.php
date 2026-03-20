<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
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
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('admin.students.show');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::patch('/students/{student}/approve', [StudentController::class, 'approve'])->name('admin.students.approve');
    Route::patch('/students/{student}/reject', [StudentController::class, 'reject'])->name('admin.students.reject');

    // Courses CRUD
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // Course Lessons
    Route::get('/courses/{course}/lessons', [CourseController::class, 'show'])->name('admin.courses.show');
    Route::post('/courses/{course}/lessons', [CourseController::class, 'storeLesson'])->name('admin.courses.lessons.store');
    Route::put('/courses/{course}/lessons/{lesson}', [CourseController::class, 'updateLesson'])->name('admin.courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [CourseController::class, 'destroyLesson'])->name('admin.courses.lessons.destroy');

    // Admins CRUD
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.admins');
    Route::post('/admins', [AdminController::class, 'store'])->name('admin.admins.store');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admin.admins.update');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admin.admins.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
