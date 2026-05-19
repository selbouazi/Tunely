<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class SubcategoryController extends Controller
{
    public function index(): Response
    {
        $subcategories = Subcategory::with('category')->orderBy('category_id')->orderBy('nombre')->get();
        $categories = Category::all();

        return Inertia::render('Admin/Subcategories/Index', [
            'subcategories' => $subcategories,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nombre' => 'required|string|max:255',
        ]);

        Subcategory::create([
            'category_id' => $validated['category_id'],
            'nombre' => $validated['nombre'],
            'slug' => Str::slug($validated['nombre']),
        ]);

        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría creada correctamente.');
    }

    public function update(Request $request, Subcategory $subcategory): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nombre' => 'required|string|max:255',
        ]);

        $subcategory->update([
            'category_id' => $validated['category_id'],
            'nombre' => $validated['nombre'],
            'slug' => Str::slug($validated['nombre']),
        ]);

        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría actualizada correctamente.');
    }

    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        if ($subcategory->instruments()->count() > 0) {
            return redirect()->route('admin.subcategorias.index')->with('error', 'No se puede eliminar una subcategoría con productos asociados.');
        }

        $subcategory->delete();

        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría eliminada correctamente.');
    }
}
