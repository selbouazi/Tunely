<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Instrument::with('subcategory')
            ->orderBy('marca')
            ->orderBy('modelo')
            ->get()
            ->map(fn($i) => $this->formatProduct($i));

        return response()->json($products);
    }

    public function bySubcategory(int $subcategoryId): JsonResponse
    {
        $products = Instrument::with('subcategory')
            ->where('subcategory_id', $subcategoryId)
            ->orderBy('marca')
            ->orderBy('modelo')
            ->get()
            ->map(fn($i) => $this->formatProduct($i));

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $instrument = Instrument::with('category', 'subcategory')->findOrFail($id);

        return response()->json($this->formatProduct($instrument));
    }

    private function formatProduct(Instrument $instrument): array
    {
        return [
            'id' => $instrument->id,
            'name' => $instrument->marca . ' ' . $instrument->modelo,
            'description' => $instrument->descripcion,
            'price' => number_format($instrument->precio, 2, '.', ''),
            'stock' => $instrument->stock,
            'image' => $instrument->imagen ? asset($instrument->imagen) : null,
            'subcategory_id' => $instrument->subcategory_id,
            'category_id' => $instrument->category_id,
            'created_at' => $instrument->created_at,
            'updated_at' => $instrument->updated_at,
        ];
    }
}
