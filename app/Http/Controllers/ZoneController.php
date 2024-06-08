<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZoneRequest;
use App\Models\Zone;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ZoneController extends Controller
{

    public function index(): Response
    {
        $zones = Zone::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Zone/Index', compact('zones'));
    }

    public function store(ZoneRequest $request): RedirectResponse
    {
        try {
            Zone::create($request->all());
            return redirect()->route('zones.index')->with('toast', ['Zona creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(ZoneRequest $request, Zone $zone): RedirectResponse
    {
        try {
            $zone->update($request->all());
            return redirect()->route('zones.index')->with('toast', ['Zona actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la zona!', 'danger']);
        }
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        try {
            $zone->delete();
            return redirect()->route('zones.index')->with('toast', ['Zona eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar la zona, ya que tiene elementos asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $zones = Zone::where('name', 'like', '%' . $texto . '%')
            ->orWhere('area', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Zone/Index', compact('zones', 'texto'));
    }
}
