<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiclerouteRequest;
use App\Models\Route;
use App\Models\Statusroute;
use App\Models\Vehicle;
use App\Models\Vehicleroutes;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class VehicleroutesController extends Controller
{

    public function index(): Response
    {
        $today = Carbon::today();
        $vehicleroutes = Vehicleroutes::with('route', 'vehicle.brandmodel.brand', 'statusroute')->whereDate('date', $today)->paginate(9);
        $statuses = Statusroute::all();

        return Inertia::render('Programming/Index', compact('vehicleroutes', 'statuses'));
    }

    public function store(VehiclerouteRequest $request)
    {
        $vehicleId = $request->vehicle_id;
        $routeId = $request->route_id;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        DB::beginTransaction();

        try {
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                Vehicleroutes::create([
                    'vehicle_id' => $vehicleId,
                    'route_id' => $routeId,
                    'statusroute_id' => 1,
                    'date' => $date->format('Y-m-d'),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Programación de rutas creada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', ['Ocurrió un error al crear la Programación!', 'danger']);
        }
    }

    public function update(Request $request, Vehicleroutes $vehicleroute)
    {
        $vehicleId = $request->vehicle_id;
        $routeId = $request->route_id;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        if (empty($dateFrom) && empty($dateTo)) {
            $vehicleroute = Vehicleroutes::find($vehicleroute->id)->update([
                'statusroute_id' => $request->statusroute_id,
                'description' => $request->description,
            ]);
        } else if(!empty($dateFrom) && !empty($dateTo)){
            $startDate = Carbon::parse($dateFrom);
            $endDate = Carbon::parse($dateTo);

            DB::beginTransaction();
            try {
                while ($startDate->lte($endDate)) {
                    Vehicleroutes::where('vehicle_id', $vehicleId)
                        ->where('route_id', $routeId)
                        ->whereDate('date', $startDate->format('Y-m-d'))
                        ->update([
                            'statusroute_id' => $request->statusroute_id,
                            'description' => $request->description,
                        ]);
                    $startDate->addDay();
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('toast', ['Ocurrió un error durante la actualización masiva!', 'danger']);
            }
        } else {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la Programación!', 'danger']);
        }

        return redirect()->back()->with('toast', ['Actualización realizada exitosamente!', 'success']);
    }

    public function destroy(Vehicleroutes $vehicleroute)
    {
        try {
            $vehicleroute->delete();
            return redirect()->route('vehicleroutes.programming', $vehicleroute->vehicle_id)->with('toast', ['Programación eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar la Programación!', 'danger']);
        }
    }

    public function programming($id): Response
    {
        $vehicleroutes = Vehicleroutes::with('route', 'vehicle.brandmodel.brand', 'statusroute')->where('vehicle_id', $id)->orderBy('date', 'desc')->paginate(9);
        $vehicle = Vehicle::find($id);
        $routes = Route::where('status', 1)->get();

        return Inertia::render('Vehicle/Programming/Index', compact('vehicleroutes', 'vehicle', 'routes'));
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $vehicleId = $request->get('vehicle_id');

        $query = Vehicleroutes::with('route', 'vehicle.brandmodel.brand', 'statusroute')->where('vehicle_id', $vehicleId);

        if ($texto) {
            $query->where(function ($q) use ($texto) {
                $q->whereHas('route', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                })->orWhereHas('statusroute', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                });
            });
        }

        if ($date_from) {
            $query->whereDate('date', '>=', $date_from);
        }

        if ($date_to) {
            $query->whereDate('date', '<=', $date_to);
        }

        $vehicleroutes = $query->orderBy('date', 'desc')->paginate(9)->appends(['texto' => $texto, 'date_from' => $date_from, 'date_to' => $date_to, 'vehicle_id' => $vehicleId]);
        $vehicle = Vehicle::find($vehicleId);
        $routes = Route::where('status', 1)->get();

        return Inertia::render('Vehicle/Programming/Index', compact('vehicleroutes', 'vehicle', 'routes', 'texto', 'date_from', 'date_to'));
    }

    public function searchTwo(Request $request): Response
    {
        $texto = $request->get('texto');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $vehicleId = $request->get('vehicle_id');

        $query = Vehicleroutes::with('route', 'vehicle.brandmodel.brand', 'statusroute');

        if ($texto) {
            $query->where(function ($q) use ($texto) {
                $q->whereHas('route', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                })->orWhereHas('statusroute', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                })->orWhereHas('vehicle', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                })->orWhereHas('vehicle.brandmodel', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                })->orWhereHas('vehicle.brandmodel.brand', function ($q) use ($texto) {
                    $q->where('name', 'like', '%' . $texto . '%');
                });
            });
        }

        if ($date_from) {
            $query->whereDate('date', '>=', $date_from);
        }

        if ($date_to) {
            $query->whereDate('date', '<=', $date_to);
        }

        $vehicleroutes = $query->orderBy('date', 'desc')->paginate(9)->appends(['texto' => $texto, 'date_from' => $date_from, 'date_to' => $date_to, 'vehicle_id' => $vehicleId]);

        return Inertia::render('Programming/Index', compact('vehicleroutes', 'texto', 'date_from', 'date_to'));
    }
}
