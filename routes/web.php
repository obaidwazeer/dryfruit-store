<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])
            ->name('login');

        Route::post('/login', [AuthController::class, 'login'])
            ->name('login.store');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('can:dashboard.view')
            ->name('dashboard');

        Route::get('/categories', [CategoryController::class, 'index'])
            ->middleware('can:categories.view')
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->middleware('can:categories.create')
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->middleware('can:categories.create')
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->middleware('can:categories.update')
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->middleware('can:categories.update')
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->middleware('can:categories.delete')
            ->name('categories.destroy');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });
});
