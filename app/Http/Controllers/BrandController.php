<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{

    public function index(): Response
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Brand/Index', compact('brands'));
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        try {
            if ($request->hasFile('logo')) {
                $image = $request->file('logo')->store('brands_logo', 'public');
                $url = Storage::url($image);
                Brand::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'logo' => $url
                ]);
            } else {
                Brand::create($request->except('logo'));
            }
            return redirect()->route('brands.index')->with('toast', ['Marca creada exitosamente!','success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
        }
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        try {
            $brand->name = $request->name;
            $brand->description = $request->description;

            if ($request->hasFile('logo')) {
                if ($brand->logo) {
                    $relativePath = str_replace('/storage/', '', $brand->logo);
                    Storage::disk('public')->delete($relativePath);
                }
                $image = $request->file('logo')->store('brands_logo', 'public');
                $brand->logo = Storage::url($image);
            }

            $brand->save();

            return redirect()->route('brands.index')->with('toast', ['Marca actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la marca!', 'danger']);
        }
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        try {
            if ($brand->logo) {
                $relativePath = str_replace('/storage/', '', $brand->logo);
                Storage::disk('public')->delete($relativePath);
            }
            $brand->delete();
            return redirect()->route('brands.index')->with('toast', ['Marca eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar la marca, ya que tiene modelos asociados!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $brands = Brand::where('name', 'like', '%' . $texto . '%')
            ->orWhere('description', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Brand/Index', compact('brands', 'texto'));
    }
}
