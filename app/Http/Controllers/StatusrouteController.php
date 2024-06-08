<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusrouteRequest;
use App\Models\Statusroute;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StatusrouteController extends Controller
{

    public function index(): Response
    {
        $statusroutes = Statusroute::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Statusroute/Index', compact('statusroutes'));
    }

    public function store(StatusrouteRequest $request): RedirectResponse
    {
        try {
            Statusroute::create($request->all());
            return redirect()->route('statusroutes.index')->with('toast', ['Estado de ruta creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(StatusrouteRequest $request, Statusroute $statusroute): RedirectResponse
    {
        try {
            $statusroute->update($request->all());
            return redirect()->route('statusroutes.index')->with('toast', ['Estado de ruta actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el estado de ruta!', 'danger']);
        }
    }

    public function destroy(Statusroute $statusroute): RedirectResponse
    {
        try {
            $statusroute->delete();
            return redirect()->route('statusroutes.index')->with('toast', ['Estado de ruta eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar el estado de ruta, ya que tiene rutas asociadas!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $statusroutes = Statusroute::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Statusroute/Index', compact('statusroutes', 'texto'));
    }
}
