<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::withCount(['items', 'consumibles'])
            ->paginate(10);

        return response()->json([
            'message' => 'Categorías obtenidas exitosamente',
            'data' => $categorias,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        $categoria = Categoria::create($request->validated());

        return response()->json([
            'message' => 'Categoría creada exitosamente',
            'data' => $categoria,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $categoria->load(['items', 'consumibles']);

        return response()->json([
            'message' => 'Categoría obtenida exitosamente',
            'data' => $categoria,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());

        return response()->json([
            'message' => 'Categoría actualizada exitosamente',
            'data' => $categoria,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->items()->count() > 0 || $categoria->consumibles()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar una categoría con datos asociados',
                'errors' => [
                    'items' => $categoria->items()->count(),
                    'consumibles' => $categoria->consumibles()->count(),
                ],
            ], 422);
        }

        $categoria->delete();

        return response()->json([
            'message' => 'Categoría eliminada exitosamente',
        ], 200);
    }
}
