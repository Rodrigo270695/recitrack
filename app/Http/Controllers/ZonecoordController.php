<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZonecoordRequest;
use App\Models\Zone;
use App\Models\Zonecoord;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ZonecoordController extends Controller
{

    public function index($zoneId): Response
    {
        $zoneCoords = Zonecoord::where('zone_id', $zoneId)->get();
        $zone = Zone::find($zoneId);
        $lastCoord = Zonecoord::where('zone_id', $zoneId)->latest()->first();
        return Inertia::render('Zone/Coords/Index', compact('zoneCoords', 'zone', 'lastCoord'));
    }

    public function store(ZonecoordRequest $request)
    {
        try {
            Zonecoord::create($request->all());
            return redirect()->route('zone.coords.index', $request->zone_id)->with('toast', ['Coordenada creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy($id)
    {
        try {
            $zoneCoord = Zonecoord::findOrFail($id);
            $zoneCoord->delete();
            return redirect()->route('zone.coords.index', $zoneCoord->zone_id)->with('toast', ['Coordenada eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al eliminar la coordenada!', 'danger']);
        }
    }
}
