<?php

use HasinHayder\TyroDashboard\Http\Controllers\DashboardController;
use HasinHayder\TyroDashboard\Http\Controllers\ComponentsController;
use HasinHayder\TyroDashboard\Http\Controllers\WidgetsController;
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

// Backwards-compatible alias for the Examples Components page
Route::get('/components', [ComponentsController::class, 'components'])->name('components');

// Optional alias (kept for backwards compatibility if anyone bookmarked it)
Route::get('/examples/components', [ComponentsController::class, 'components'])->name('examples.components');

// Widgets (interactive examples)
Route::get('/widgets', [WidgetsController::class, 'widgets'])->name('widgets');
Route::get('/examples/widgets', [WidgetsController::class, 'widgets'])->name('examples.widgets');

// Same-origin proxies for third-party widget data (avoid browser CORS)
Route::get('/examples/widgets/xkcd/{id?}', [WidgetsController::class, 'xkcd'])->where('id', '[0-9]+')->name('examples.widgets.xkcd');
Route::get('/examples/widgets/stocks/{symbol}', [WidgetsController::class, 'stockQuote'])->name('examples.widgets.stocks');
Route::get('/examples/widgets/fx/{base}', [WidgetsController::class, 'fxRates'])->name('examples.widgets.fx');
Route::get('/examples/widgets/flights', [WidgetsController::class, 'flightStates'])->name('examples.widgets.flights');

// Profile Management (all authenticated users)
Route::prefix('profile')->name('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index']);
    Route::put('/update', [ProfileController::class, 'update'])->name('.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('.password');
    Route::delete('/2fa/reset', [ProfileController::class, 'reset2FA'])->name('.2fa.reset');
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
        Route::delete('/{id}/2fa', [UserController::class, 'reset2FA'])->name('2fa.reset');
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
        Route::delete('/{id}/users/{userId}', [RoleController::class, 'removeUser'])->name('remove-user');
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
        Route::delete('/{id}/roles/{roleId}', [PrivilegeController::class, 'removeRole'])->name('remove-role');
    });

    // Dynamic Resources
    // This route group handles all dynamic resources defined in config
    Route::prefix('resources/{resource}')->name('resources.')->group(function () {
        Route::get('/', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'index'])->name('index');
        Route::get('/create', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'create'])->name('create');
        Route::post('/', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'store'])->name('store');
        Route::get('/{id}', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'update'])->name('update');
        Route::delete('/{id}', [\HasinHayder\TyroDashboard\Http\Controllers\ResourceController::class, 'destroy'])->name('destroy');
    });
});
