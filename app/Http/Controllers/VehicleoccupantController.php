<?php

namespace App\Http\Controllers;

use App\Models\Typeuser;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vehicleoccupant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleoccupantController extends Controller
{

    public function index($id)
    {
        $vehicleoccupants = Vehicleoccupant::with('user.typeuser')->where('vehicle_id', $id)->get();
        $vehicle = Vehicle::find($id);
        $users = User::with('typeuser')->where('id', '!=', 1)->orderBy('name', 'asc')->get();
        $types = Typeuser::where('id', '!=', 1)->orderBy('name', 'asc')->get();

        return Inertia::render('Vehicle/Occupants/Index', compact('vehicleoccupants', 'vehicle', 'users', 'types'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicleoccupant $vehicleoccupant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicleoccupant $vehicleoccupant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicleoccupant $vehicleoccupant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicleoccupant $vehicleoccupant)
    {
        //
    }
}
