<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiclecolorRequest;
use App\Models\Vehiclecolor;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehiclecolorController extends Controller
{

    public function index(): Response
    {
        $vehiclecolors = Vehiclecolor::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Vehiclecolor/Index', compact('vehiclecolors'));
    }

    public function store(VehiclecolorRequest $request): RedirectResponse
    {
        try {
            Vehiclecolor::create($request->all());
            return redirect()->route('vehiclecolors.index')->with('toast', ['Color de vehículo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(VehiclecolorRequest $request, Vehiclecolor $vehiclecolor): RedirectResponse
    {
        try {
            $vehiclecolor->update($request->all());
            return redirect()->route('vehiclecolors.index')->with('toast', ['Color de vehículo actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el color de vehículo!', 'danger']);
        }
    }

    public function destroy(Vehiclecolor $vehiclecolor): RedirectResponse
    {
        try {
            $vehiclecolor->delete();
            return redirect()->route('vehiclecolors.index')->with('toast', ['Color de vehículo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar el color de vehículo, ya que tiene vehículos asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $vehiclecolors = Vehiclecolor::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Vehiclecolor/Index', compact('vehiclecolors', 'texto'));
    }
}
