<?php

namespace App\Http\Controllers;

use App\Models\VehicleColors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\VehicleColorsRequest;

class VehicleColorsController extends Controller
{
    
    public function index()
    {

        $vehiclecolors = VehicleColors::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('VehicleColors/Index', compact('vehiclecolors'));
        
    }

    
    public function store(VehicleColorsRequest $request): RedirectResponse
    {
        try {
            VehicleColors::create($request->validated());
            return redirect()->route('vehiclecolors.index')->with('toast', ['Color de Vehículo Creado Exitosamente', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al Crear el Color de Vehículo: ' , 'danger']);
        }
    }


    public function update(Request $request, VehicleColors $vehiclecolor): RedirectResponse
    {

        try {

            $vehiclecolor->name = $request->name;
            $vehiclecolor->description = $request->description;
            $vehiclecolor->save();
            return redirect()->route('vehiclecolors.index')->with('toast', ['Color de Vehículo Actualizado Correctamente', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al Actualizar el Color de Vehículo: ' , 'danger']);
        }
    }


    public function destroy(VehicleColors $vehiclecolor): RedirectResponse
    {
        try {
            $vehiclecolor->delete();
            return redirect()->route('vehiclecolors.index')->with('toast', ['Color de Vehículo Eliminado Exitosamente', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al Eliminar el Tipo de Vehículo: ' , 'danger']);
        }
        
    }


    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $vehiclecolors = VehicleColors::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('VehicleColors/Index', compact('vehiclecolors', 'texto'));
    }

}
