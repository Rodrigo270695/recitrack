<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandmodelController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\StatusrouteController;
use App\Http\Controllers\TypeuserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiclecolorController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleimageController;
use App\Http\Controllers\VehicleoccupantController;
use App\Http\Controllers\VehicletypeController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ZonecoordController;
use App\Models\Vehicleimage;
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

    Route::get('zones/search', [ZoneController::class, 'search' ])->name('zones.search');
    Route::resource('zones', ZoneController::class);

    Route::get('vehicles/search', [VehicleController::class, 'search' ])->name('vehicles.search');
    Route::resource('vehicles', VehicleController::class);

    Route::get('vehicles/images/{id}', [VehicleimageController::class, 'index'])->name('vehicles.images.index');
    Route::post('vehicles/images', [VehicleimageController::class, 'store'])->name('vehicles.images.store');
    Route::delete('vehicles/images/{id}', [VehicleimageController::class, 'destroy'])->name('vehicles.images.destroy');

    Route::get('zone/coords/{id}', [ZonecoordController::class, 'index'])->name('zone.coords.index');
    Route::post('zone/coords', [ZonecoordController::class, 'store'])->name('zone.coords.store');
    Route::delete('zone/coords/{id}', [ZonecoordController::class, 'destroy'])->name('zone.coords.destroy');

    Route::get('users/search', [UserController::class, 'search' ])->name('users.search');
    Route::resource('users', UserController::class);

    Route::get('routes/search', [RouteController::class, 'search' ])->name('routes.search');
    Route::resource('routes', RouteController::class);

    Route::get('vehicle/occupants/{id}', [VehicleoccupantController::class, 'index' ])->name('vehicle.occupants.index');
    Route::post('vehicle/occupants', [VehicleoccupantController::class, 'store'])->name('vehicle.occupants.store');
    Route::delete('vehicle/occupants/{vehicleoccupant}', [VehicleoccupantController::class, 'destroy'])->name('vehicle.occupants.destroy');

});
