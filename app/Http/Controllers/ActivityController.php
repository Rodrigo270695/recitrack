<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Horary;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivityController extends Controller
{
    public function index($id): Response
    {
        $activities = Activity::with('horary')->where('horary_id', $id)->orderBy('id', 'desc')->paginate(7);
        $horary = Horary::where('id', $id)->first();

        return Inertia::render('Maintenance/Horary/Activity/Index', compact('activities', 'horary'));
    }

    public function store(ActivityRequest $request): RedirectResponse
    {
        try {
            Activity::create([
                'horary_id' => $request->horary_id,
                'date' => $request->date,
                'description' => $request->description,
            ]);
            return redirect()->route('maintenances.horaries.activities.index', $request->horary_id)->with('toast', ['Actividad creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(ActivityRequest $request, Activity $activity): RedirectResponse
    {
        try {
            $activity->update([
                'date' => $request->date,
                'description' => $request->description,
                'horary_id' => $request->horary_id,
            ]);
            return redirect()->route('horaries.activities.index', $activity->horary_id)->with('toast', ['Actividad actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la actividad!', 'danger']);
        }
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        try {
            $activity->delete();
            return redirect()->route('maintenances.horaries.activities.index', $activity->horary_id)->with('toast', ['Actividad eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar la actividad!', 'danger']);
        }
    }
}
