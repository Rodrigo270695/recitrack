<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeuserRequest;
use App\Models\Typeuser;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class TypeuserController extends Controller
{

    public function index(): Response
    {
        $typeusers = Role::orderBy('id', 'desc')->paginate(7);
        dd($typeusers);
        return Inertia::render('Typeuser/Index', compact('typeusers'));
    }

    public function store(TypeuserRequest $request): RedirectResponse
    {
        try {
            Role::create($request->all());
            return redirect()->route('typeusers.index')->with('toast', ['Tipo de usuario creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(TypeuserRequest $request, Role $typeuser): RedirectResponse
    {
        try {
            $typeuser->update($request->all());
            return redirect()->route('typeusers.index')->with('toast', ['Tipo de usuario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el tipo de usuario!', 'danger']);
        }
    }

    public function destroy(Role $typeuser): RedirectResponse
    {
        try {
            $typeuser->delete();
            return redirect()->route('typeusers.index')->with('toast', ['Tipo de usuario eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar el tipo de usuario, ya que tiene usuarios asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $typeusers = Typeuser::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Typeuser/Index', compact('typeusers', 'texto'));
    }
}
