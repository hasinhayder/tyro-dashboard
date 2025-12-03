<?php

use HasinHayder\TyroDashboard\Http\Controllers\DashboardController;
use HasinHayder\TyroDashboard\Http\Controllers\PrivilegeController;
use HasinHayder\TyroDashboard\Http\Controllers\ProfileController;
use HasinHayder\TyroDashboard\Http\Controllers\RoleController;
use HasinHayder\TyroDashboard\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tyro Dashboard Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Tyro Dashboard package.
|
*/

// Dashboard Home
Route::get('/', [DashboardController::class, 'index'])->name('index');

// Profile Management (all authenticated users)
Route::prefix('profile')->name('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index']);
    Route::put('/update', [ProfileController::class, 'update'])->name('.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('.password');
});

// Admin-only routes
Route::middleware('tyro-dashboard.admin')->group(function () {
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/{id}/suspend', [UserController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/unsuspend', [UserController::class, 'unsuspend'])->name('unsuspend');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Role Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Privilege Management
    Route::prefix('privileges')->name('privileges.')->group(function () {
        Route::get('/', [PrivilegeController::class, 'index'])->name('index');
        Route::get('/create', [PrivilegeController::class, 'create'])->name('create');
        Route::post('/', [PrivilegeController::class, 'store'])->name('store');
        Route::get('/{id}', [PrivilegeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PrivilegeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PrivilegeController::class, 'update'])->name('update');
        Route::delete('/{id}', [PrivilegeController::class, 'destroy'])->name('destroy');
    });
});
