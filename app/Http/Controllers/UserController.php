<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Typeuser;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $loggedInUserId = auth()->id();
        $users = User::with('typeuser')
            ->where('id', '!=', $loggedInUserId)
            ->orderBy('id', 'desc')
            ->paginate(7);
        $typeUsers = Typeuser::orderBy('name', 'asc')->get();
        return Inertia::render('User/Index', compact('users', 'typeUsers'));
    }

    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'dni' => $request->dni,
                'license' => $request->license,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'typeuser_id' => $request->typeuser_id,
                'password' => Hash::make($request->dni),
            ]);

            return redirect()->route('users.index')->with('toast', ['Usuario creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al crear el usuario!', 'danger']);
        }
    }


    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'dni' => $request->dni,
                'birthdate' => $request->birthdate,
                'license' => $request->license,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'typeuser_id' => $request->typeuser_id,
            ]);

            return redirect()->route('users.index')->with('toast', ['Usuario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el usuario!', 'danger']);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('toast', ['Usuario eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar el usuario!', 'danger']);
        }
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');
        $users = User::with('typeuser')
            ->where('name', 'like', '%' . $texto . '%')
            ->orWhere('last_name', 'like', '%' . $texto . '%')
            ->orWhere('dni', 'like', '%' . $texto . '%')
            ->orWhere('email', 'like', '%' . $texto . '%')
            ->orWhereHas('typeUser', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);
            $typeUsers = Typeuser::orderBy('name', 'asc')->get();
        return Inertia::render('User/Index', compact('users', 'texto', 'typeUsers'));
    }
}
