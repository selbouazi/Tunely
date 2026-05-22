<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Instrument;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstrumentController extends Controller
{
    public function index(): Response
    {
        $instruments = Instrument::with('category', 'subcategory')->withCount('orderItems')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return Inertia::render('Admin/Instruments/Index', [
            'instruments' => $instruments,
            'categories' => $categories,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Instruments/Form', [
            'categories' => Category::with('subcategories')->get(),
            'subcategories' => Subcategory::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'tipo' => 'required|in:nuevo,usado',
            'precio' => 'required|numeric|min:0',
            'precio_original' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|string|max:500',
            'descripcion' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'disponible' => 'boolean',
        ]);

        Instrument::create($validated);

        return redirect()->route('admin.instrumentos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Instrument $instrument): Response
    {
        return Inertia::render('Admin/Instruments/Form', [
            'instrument' => $instrument->load('category', 'subcategory'),
            'categories' => Category::with('subcategories')->get(),
            'subcategories' => Subcategory::all(),
        ]);
    }

    public function update(Request $request, Instrument $instrument): RedirectResponse
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'tipo' => 'required|in:nuevo,usado',
            'precio' => 'required|numeric|min:0',
            'precio_original' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|string|max:500',
            'descripcion' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'disponible' => 'boolean',
        ]);

        if (empty($validated['precio_original']) && (float) $validated['precio'] !== (float) $instrument->precio) {
            $validated['precio_original'] = $instrument->precio;
        }

        $validated['descuento_general_applied'] = false;
        $instrument->update($validated);

        return redirect()->route('admin.instrumentos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Instrument $instrument): RedirectResponse
    {
        $instrument->update(['disponible' => false, 'stock' => 0]);

        return redirect()->route('admin.instrumentos.index')->with('success', 'Producto desactivado correctamente.');
    }

    public function activate(Request $request, Instrument $instrument): RedirectResponse
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        $instrument->update([
            'disponible' => true,
            'stock' => $validated['stock'],
        ]);

        return redirect()->route('admin.instrumentos.index')->with('success', 'Producto activado correctamente.');
    }
}
