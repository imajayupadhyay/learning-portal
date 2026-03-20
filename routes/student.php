<?php

use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\GoalController;
use App\Http\Controllers\Student\TodoController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('student.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('student.register');
});

Route::middleware(['auth', 'student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/courses', [DashboardController::class, 'courses'])->name('student.courses');
    Route::get('/courses/{id}', [DashboardController::class, 'courseShow'])->name('student.courses.show');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('student.profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('student.profile.update');

    // Goals
    Route::get('/goals', [GoalController::class, 'index'])->name('student.goals');
    Route::post('/goals', [GoalController::class, 'addGoal'])->name('student.goals.add');
    Route::delete('/goals/{courseId}', [GoalController::class, 'removeGoal'])->name('student.goals.remove');

    // Lesson completion
    Route::post('/lessons/toggle-complete', [GoalController::class, 'toggleLessonComplete'])->name('student.lessons.toggle');

    // Todos
    Route::get('/todos', [TodoController::class, 'index'])->name('student.todos');
    Route::post('/todos', [TodoController::class, 'store'])->name('student.todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('student.todos.update');
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggleComplete'])->name('student.todos.toggle');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('student.todos.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('student.logout');
});
