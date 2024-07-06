<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandmodelController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\HoraryController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteZonesController;
use App\Http\Controllers\StatusrouteController;
use App\Http\Controllers\TypeuserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiclecolorController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleimageController;
use App\Http\Controllers\VehicleoccupantController;
use App\Http\Controllers\VehicleroutesController;
use App\Http\Controllers\VehicletypeController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ZonecoordController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/register', function () {
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
    Route::get('/routes/map-data', [RouteController::class, 'show'])->name('routes.mapData');

    Route::get('vehicle/occupants/{id}', [VehicleoccupantController::class, 'index' ])->name('vehicle.occupants.index');
    Route::post('vehicle/occupants', [VehicleoccupantController::class, 'store'])->name('vehicle.occupants.store');
    Route::delete('vehicle/occupants/{vehicleoccupant}', [VehicleoccupantController::class, 'destroy'])->name('vehicle.occupants.destroy');

    Route::get('vehicleroutes/search', [VehicleroutesController::class, 'search' ])->name('vehicleroutes.search');
    Route::get('vehicleroutes/searchTwo', [VehicleroutesController::class, 'searchTwo' ])->name('vehicleroutes.searchTwo');
    Route::get('vehicle/routes/{id}', [VehicleroutesController::class, 'programming' ])->name('vehicleroutes.programming');
    Route::resource('vehicleroutes', VehicleroutesController::class);

    Route::get('maintenances/search', [MaintenanceController::class, 'search' ])->name('maintenances.search');
    Route::resource('maintenances', MaintenanceController::class);

    Route::get('maintenances/horaries/{id}', [HoraryController::class, 'index' ])->name('maintenances.horaries.index');
    Route::post('maintenances/horaries', [HoraryController::class, 'store'])->name('maintenances.horaries.store');
    Route::put('maintenances/horaries/{horary}', [HoraryController::class, 'update'])->name('maintenances.horaries.update');
    Route::delete('maintenances/horaries/{horary}', [HoraryController::class, 'destroy'])->name('maintenances.horaries.destroy');

    Route::get('maintenances/horaries/activities/{id}', [ActivityController::class, 'index' ])->name('maintenances.horaries.activities.index');
    Route::post('maintenances/horaries/activities', [ActivityController::class, 'store'])->name('maintenances.horaries.activities.store');
    Route::put('maintenances/horaries/activities/{activity}', [ActivityController::class, 'update'])->name('maintenances.horaries.activities.update');
    Route::delete('maintenances/horaries/activities/{activity}', [ActivityController::class, 'destroy'])->name('maintenances.horaries.activities.destroy');

    Route::get('zone/getAllZones', [ZoneController::class, 'getAllZones'])->name('zone.getAllZones');
    Route::post('/route-zones/{routeId}', [RouteZonesController::class, 'store'])->name('routeZones.store');
    Route::get('/route-zones/{routeId}', [RouteZonesController::class, 'getAssignedZones'])->name('routeZones.getAssignedZones');
    Route::get('zone/{zone}/routeszone', [RouteZonesController::class, 'index'])->name('zone.routeszone.index');
});
