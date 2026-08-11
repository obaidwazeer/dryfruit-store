<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductImageController;
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

        Route::get('/products', [ProductController::class, 'index'])
            ->middleware('can:products.view')
            ->name('products.index');

        Route::get('/products/create', [ProductController::class, 'create'])
            ->middleware('can:products.create')
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->middleware('can:products.create')
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->middleware('can:products.update')
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->middleware('can:products.update')
            ->name('products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->middleware('can:products.delete')
            ->name('products.destroy');

        Route::prefix('products/{product}/variants')
            ->name('products.variants.')
            ->group(function () {

        Route::get('/', [
            ProductVariantController::class,
            'index'
        ])
            ->middleware('can:products.update')
            ->name('index');

        Route::get('/create', [
            ProductVariantController::class,
            'create'
        ])
            ->middleware('can:products.update')
            ->name('create');

        Route::post('/', [
            ProductVariantController::class,
            'store'
        ])
            ->middleware('can:products.update')
            ->name('store');

        Route::get('/{variant}/edit', [
            ProductVariantController::class,
            'edit'
        ])
            ->middleware('can:products.update')
            ->name('edit');

        Route::put('/{variant}', [
            ProductVariantController::class,
            'update'
        ])
            ->middleware('can:products.update')
            ->name('update');

        Route::delete('/{variant}', [
            ProductVariantController::class,
            'destroy'
        ])
            ->middleware('can:products.update')
            ->name('destroy');
    });

    Route::prefix('products/{product}/images')
            ->name('products.images.')
            ->group(function () {

        Route::post('/', [
            ProductImageController::class,
            'store'
        ])
            ->middleware('can:products.update')
            ->name('store');

        Route::patch('/{image}/primary', [
            ProductImageController::class,
            'primary'
        ])
            ->middleware('can:products.update')
            ->name('primary');

        Route::delete('/{image}', [
            ProductImageController::class,
            'destroy'
        ])
            ->middleware('can:products.update')
            ->name('destroy');
    });



    // Logout Route
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });
});
