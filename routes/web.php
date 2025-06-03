<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Department routes
    Route::resource('departments', DepartmentController::class);

    // Employee routes
    Route::resource('employees', EmployeeController::class);

    // Project routes
    Route::resource('projects', ProjectController::class);
    Route::get('projects/employee/{employee}', [ProjectController::class, 'byEmployee'])->name('projects.by_employee');
    Route::get('projects-analysis/employees-multiple-projects', [ProjectController::class, 'employeesWithMultipleProjects'])->name('projects.employees_multiple_projects');
    Route::get('projects-analysis/salary-by-department', [ProjectController::class, 'salaryByDepartment'])->name('projects.salary_by_department');

    // User Management routes - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::get('users-search', [\App\Http\Controllers\UserController::class, 'search'])->name('users.search');
    });
});

// Logout route
Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';
