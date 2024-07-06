<?php

namespace App\Http\Controllers;

use App\Http\Requests\HoraryRequest;
use App\Models\Horary;
use App\Models\Maintenance;
use App\Models\VehicleOccupant;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HoraryController extends Controller
{
    public function index($id): Response
    {
        $horaries = Horary::with(['maintenance', 'vehicleoccupant.user', 'vehicleoccupant.vehicle'])->where('maintenance_id', $id)->orderBy('id', 'desc')->paginate(7);
        $maintenance = Maintenance::where('id', $id)->first();
        $vehicleoccupants = VehicleOccupant::with(['user', 'vehicle'])->get();

        return Inertia::render('Maintenance/Horary/Index', compact('horaries', 'maintenance', 'vehicleoccupants'));
    }

    public function store(HoraryRequest $request): RedirectResponse
    {
        try {
            Horary::create([
                'maintenance_id' => $request->maintenance_id,
                'vehicleoccupant_id' => $request->vehicleoccupant_id,
                'day' => $request->day,
                'type' => $request->type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
            return redirect()->route('maintenances.horaries.index', $request->maintenance_id)->with('toast', ['Horario creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(HoraryRequest $request, Horary $horary): RedirectResponse
    {
        try {
            $horary->update([
                'day' => $request->day,
                'type' => $request->type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'vehicleoccupant_id' => $request->vehicleoccupant_id,
                'maintenance_id' => $request->maintenance_id,
            ]);
            return redirect()->route('maintenances.horaries.index', $horary->maintenance_id)->with('toast', ['Horario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el horario!', 'danger']);
        }
    }

    public function destroy(Horary $horary): RedirectResponse
    {
        try {
            $horary->delete();
            return redirect()->route('maintenances.horaries.index', $horary->maintenance_id)->with('toast', ['Horario eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar el horario!', 'danger']);
        }
    }
}
