<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicletypeRequest;
use App\Models\Vehicletype;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehicletypeController extends Controller
{

    public function index(): Response
    {
        $vehicletypes = Vehicletype::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Vehicletype/Index', compact('vehicletypes'));
    }

    public function store(VehicletypeRequest $request): RedirectResponse
    {
        try {
            Vehicletype::create($request->all());
            return redirect()->route('vehicletypes.index')->with('toast', ['Tipo de vehículo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(VehicletypeRequest $request, Vehicletype $vehicletype): RedirectResponse
    {
        try {
            $vehicletype->update($request->all());
            return redirect()->route('vehicletypes.index')->with('toast', ['Tipo de vehículo actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el tipo de vehículo!', 'danger']);
        }
    }

    public function destroy(Vehicletype $vehicletype): RedirectResponse
    {
        try {
            $vehicletype->delete();
            return redirect()->route('vehicletypes.index')->with('toast', ['Tipo de vehículo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar el tipo de vehículo, ya que tiene vehículos asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $vehicletypes = Vehicletype::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Vehicletype/Index', compact('vehicletypes', 'texto'));
    }
}
