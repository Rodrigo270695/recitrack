<?php

namespace App\Http\Controllers;

use App\Models\RouteZones;
use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Zone;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class RouteZonesController extends Controller
{

    public function index(Zone $zone): Response
    {
        $routes = RouteZones::where('zone_id', $zone->id)->with('route')->paginate(7);
        return Inertia::render('Zone/Routeszone/Index', compact('routes'));
    }



    public function getAssignedZones($routeId)
    {
        $assignedZones = RouteZones::where('route_id', $routeId)->with('zone')->get()->pluck('zone');
        return response()->json(['zones' => $assignedZones]);
    }

    public function store(Request $request, $routeId)
    {
        try {
            logger()->info('Datos recibidos:', $request->all());

            $validatedData = $request->validate([
                'zones' => 'required|array',
                'zones.*' => 'exists:zones,id',
            ]);


            RouteZones::where('route_id', $routeId)->delete();


            foreach ($validatedData['zones'] as $zoneId) {
                RouteZones::create([
                    'route_id' => $routeId,
                    'zone_id' => $zoneId,
                ]);
            }

            return response()->json(['message' => 'Zonas asignadas a la ruta exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al asignar zonas: ' . $e->getMessage()], 500);
        }
    }
}
