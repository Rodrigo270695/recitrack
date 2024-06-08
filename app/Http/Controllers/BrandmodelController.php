<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandModelRequest;
use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BrandModelController extends Controller
{
    public function index(): Response
    {
        $brandModels = BrandModel::with('brand')->orderBy('id', 'desc')->paginate(7);
        $brands = Brand::orderBy('name', 'asc')->get();

        return Inertia::render('Model/Index', compact('brandModels', 'brands'));
    }

    public function store(BrandModelRequest $request): RedirectResponse
    {
        try {
            BrandModel::create($request->all());
            return redirect()->route('brandmodels.index')->with('toast', ['Modelo de marca creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(BrandModelRequest $request, BrandModel $brandmodel): RedirectResponse
    {
        try {
            $brandmodel->update($request->validated());
            return redirect()->route('brandmodels.index')->with('toast', ['Modelo de marca actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el modelo de marca!', 'danger']);
        }
    }

    public function destroy(BrandModel $brandmodel): RedirectResponse
    {
        try {
            $brandmodel->delete();
            return redirect()->route('brandmodels.index')->with('toast', ['Modelo de marca eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar el modelo de marca!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $brandModels = BrandModel::with('brand')
            ->where('name', 'like', '%' . $texto . '%')
            ->orWhereHas('brand', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Model/Index', compact('brandModels', 'texto'));
    }

}
