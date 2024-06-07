<?php

namespace App\Http\Controllers;

use App\Models\VehicleTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\VehicleTypesRequest;
use Illuminate\Database\QueryException;
use Inertia\Response;


class VehicleTypesController extends Controller
{

    public function index()
    {

        $vehicletypes = VehicleTypes::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('VehicleTypes/Index', compact('vehicletypes'));
    }


    public function store(VehicleTypesRequest $request): RedirectResponse
    {
        try {
            VehicleTypes::create($request->validated());
            return redirect()->route('vehicletypes.index')->with('toast', ['Tipo de Vehículo Creado Exitosamente', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al Crear el Tipo de Vehículo: ', 'danger']);
        }
    }

    public function update(Request $request, VehicleTypes $vehicletype): RedirectResponse
    {

        try {

            $vehicletype->name = $request->name;
            $vehicletype->description = $request->description;
            $vehicletype->save();
            return redirect()->route('vehicletypes.index')->with('toast', ['Tipo de Vehiculo Actualizado Correctamente', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al Actualizar el Tipo de Vehiculo: ', 'danger']);
        }
    }


    public function destroy(VehicleTypes $vehicletype): RedirectResponse
    {
        try {
            $vehicletype->delete();
            return redirect()->route('vehicletypes.index')->with('toast', ['Tipo de Vehículo Eliminado Exitosamente', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al Eliminar el Tipo de Vehículo: ' . $e->getMessage(), 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $vehicletypes = VehicleTypes::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('VehicleTypes/Index', compact('vehicletypes', 'texto'));
    }






}
