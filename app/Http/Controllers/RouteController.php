<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\RouteRequest;
use App\Models\Zone;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Route/Index', compact('routes'));
    }

    public function store(RouteRequest $request): RedirectResponse
    {
        try {
            Route::create($request->all());
            return redirect()->route('routes.index')->with('toast', ['Ruta creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error: ' . $e->getMessage(), 'danger']);
            //return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(RouteRequest $request, Route $route): RedirectResponse
    {

        try {
            $route->update($request->all());
            return redirect()->route('routes.index')->with('toast', ['Ruta actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la ruta!', 'danger']);
        }
    }

    public function destroy(Route $route): RedirectResponse
    {
        try {
            $route->delete();
            return redirect()->route('routes.index')->with('toast', ['Ruta eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar la ruta, ya que tiene zonas asociadas!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $routes = Route::where('name', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Route/Index', compact('routes', 'texto'));
    }

    public function changeStatus(Route $route): RedirectResponse
    {
        try {
            $newStatus = $route->status === 1 ? 0 : 1;
            $route->update(['status' => $newStatus]);

            return redirect()->route('routes.index')->with('toast', ['Estado de la ruta cambiado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al cambiar el estado de la ruta!', 'danger']);
        }
    }

    public function show()
    {
        $routes = Route::all();
        return response()->json($routes);
    }

    public function assignZonesToRoute(Route $route)
    {
        $zones = Zone::all();
        return response()->json(['route' => $route, 'zones' => $zones]);
    }
}
