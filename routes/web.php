<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandmodelController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('brands/search', [BrandController::class, 'search' ])->name('brands.search');
    Route::resource('brands', BrandController::class);

    Route::get('brandmodels/search', [BrandmodelController::class, 'search' ])->name('brandmodels.search');
    Route::resource('brandmodels', BrandmodelController::class);

});
