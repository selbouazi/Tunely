<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('subcategories')
            ->orderBy('nombre')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->nombre,
                'slug' => $c->slug,
                'subcategories' => $c->subcategories->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->nombre,
                    'slug' => $s->slug,
                ]),
            ]);

        return response()->json($categories);
    }
}
