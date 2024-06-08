<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleRequest;
use App\Models\Brand;
use App\Models\Brandmodel;
use App\Models\Vehicle;
use App\Models\Vehiclecolor;
use App\Models\Vehicletype;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{

    public function index(): Response
    {
        $vehicles = Vehicle::with('brandmodel.brand', 'vehicletype', 'vehiclecolor')->orderBy('id', 'desc')->paginate(7);
        $brands = Brand::orderBy('name', 'asc')->get();
        $brandmodels = Brandmodel::with('brand')->orderBy('name', 'asc')->get();
        $vehicletypes = Vehicletype::orderBy('name', 'asc')->get();
        $vehiclecolors = Vehiclecolor::orderBy('name', 'asc')->get();
        return Inertia::render('Vehicle/Index', compact('vehicles', 'brandmodels', 'vehicletypes', 'vehiclecolors', 'brands'));
    }

    public function store(VehicleRequest $request): RedirectResponse
    {
        try {
            Vehicle::create($request->all());
            return redirect()->route('vehicles.index')->with('toast', ['Vehículo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        try {
            $vehicle->update($request->all());
            return redirect()->route('vehicles.index')->with('toast', ['Vehículo actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el vehículo!', 'danger']);
        }
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        try {
            $vehicle->delete();
            return redirect()->route('vehicles.index')->with('toast', ['Vehículo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar el vehículo, ya que tiene elementos asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $vehicles = Vehicle::with('brandmodel.brand', 'vehicletype', 'vehiclecolor')
            ->where('name', 'like', '%' . $texto . '%')
            ->orWhere('code', 'like', '%' . $texto . '%')
            ->orWhere('year', '=', $texto)
            ->orWhere('plate', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orWhereHas('brandmodel', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('brandmodel.brand', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('vehiclecolor', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('vehicletype', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhere('status', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $brands = Brand::orderBy('name', 'asc')->get();
        $brandmodels = Brandmodel::with('brand')->orderBy('name', 'asc')->get();
        $vehicletypes = Vehicletype::orderBy('name', 'asc')->get();
        $vehiclecolors = Vehiclecolor::orderBy('name', 'asc')->get();
        return Inertia::render('Vehicle/Index', compact('vehicles', 'texto', 'brands', 'brandmodels', 'vehicletypes', 'vehiclecolors'));
    }
}
