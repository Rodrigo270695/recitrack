<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleimageRequest;
use App\Models\Vehicle;
use App\Models\Vehicleimage;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehicleimageController extends Controller
{

    public function index($id)
    {
        $vehicleimages = Vehicle::with('vehicleimages')->find($id);
        return Inertia::render('Vehicle/Images/Index', compact('vehicleimages'));
    }

    public function store(VehicleimageRequest $request)
    {
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('vehicle_images', 'public');
                $url = Storage::url($image);

                if ($request->profile) {
                    Vehicleimage::where('vehicle_id', $request->vehicle_id)->update(['profile' => 0]);
                }

                Vehicleimage::create([
                    'image' => $url,
                    'profile' => $request->profile === null ? 0 : 1,
                    'vehicle_id' => $request->vehicle_id
                ]);
            }

            return redirect()->route('vehicles.images.index', $request->vehicle_id)->with('toast', ['Imagen registrada exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error al registrar la imagen!', 'danger']);
        }
    }

    public function destroy($id)
    {
        try {
            $vehicleimage = Vehicleimage::findOrFail($id);
            Storage::disk('public')->delete(str_replace('/storage/', '', $vehicleimage->image));

            $vehicleimage->delete();

            return redirect()->back()->with('toast', ['Imagen eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar la imagen!', 'danger']);
        }
    }

}
