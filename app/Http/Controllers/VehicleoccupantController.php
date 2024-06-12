<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleoccupantRequest;
use App\Models\Typeuser;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vehicleoccupant;
use Illuminate\Database\QueryException;
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

    public function store(VehicleoccupantRequest $request)
    {
        try {
            Vehicleoccupant::create($request->all());


            return redirect()->route('vehicle.occupants.index', $request->vehicle_id)->with('toast', ['Ocupante creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }
    
    public function destroy(Vehicleoccupant $vehicleoccupant)
    {
        try {
            $vehicleoccupant->delete();
            return redirect()->route('vehicle.occupants.index', $vehicleoccupant->vehicle_id)->with('toast', ['Ocupante eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar el ocupante!', 'danger']);
        }
    }
}
