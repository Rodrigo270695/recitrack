<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceRequest;
use App\Models\Maintenance;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceController extends Controller
{

    public function index(): Response
    {
        $maintenances = Maintenance::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Maintenance/Index', compact('maintenances'));
    }

    public function store(MaintenanceRequest $request): RedirectResponse
    {
        try {
            Maintenance::create($request->all());
            return redirect()->route('maintenances.index')->with('toast', ['Mantenimiento creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(MaintenanceRequest $request, Maintenance $maintenance): RedirectResponse
    {
        try {
            $maintenance->update($request->all());
            return redirect()->route('maintenances.index')->with('toast', ['Mantenimiento actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el mantenimiento!', 'danger']);
        }
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        try {
            $maintenance->delete();
            return redirect()->route('maintenances.index')->with('toast', ['Mantenimiento eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar el mantenimiento por que tiene horarios asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $maintenances = Maintenance::where('name', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Maintenance/Index', compact('maintenances', 'texto'));
    }
}
