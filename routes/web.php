<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandmodelController;
use App\Http\Controllers\StatusrouteController;
use App\Http\Controllers\TypeuserController;
use App\Http\Controllers\VehiclecolorController;
use App\Http\Controllers\VehicleTypesController;
use App\Http\Controllers\VehicleColorsController;
use App\Http\Controllers\VehicletypeController;
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

    Route::get('statusroutes/search', [StatusrouteController::class, 'search' ])->name('statusroutes.search');
    Route::resource('statusroutes', StatusrouteController::class);

    Route::get('typeusers/search', [TypeuserController::class, 'search' ])->name('typeusers.search');
    Route::resource('typeusers', TypeuserController::class);

    Route::get('vehicletypes/search', [VehicletypeController::class, 'search' ])->name('vehicletypes.search');
    Route::resource('vehicletypes', VehicleTypeController::class);

    Route::get('vehiclecolors/search', [VehicleColorController::class, 'search' ])->name('vehiclecolors.search');
    Route::resource('vehiclecolors', VehiclecolorController::class);
});
