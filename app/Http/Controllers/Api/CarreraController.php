<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarreraRequest;
use App\Http\Requests\UpdateCarreraRequest;
use App\Models\Carrera;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carreras = Carrera::withCount(['usuarios', 'laboratorios', 'items'])
            ->paginate(10);

        return response()->json([
            'message' => 'Carreras obtenidas exitosamente',
            'data' => $carreras,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarreraRequest $request)
    {
        $carrera = Carrera::create($request->validated());

        return response()->json([
            'message' => 'Carrera creada exitosamente',
            'data' => $carrera,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Carrera $carrera)
    {
        $carrera->load(['usuarios', 'laboratorios', 'items']);

        return response()->json([
            'message' => 'Carrera obtenida exitosamente',
            'data' => $carrera,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarreraRequest $request, Carrera $carrera)
    {
        $carrera->update($request->validated());

        return response()->json([
            'message' => 'Carrera actualizada exitosamente',
            'data' => $carrera,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrera $carrera)
    {
        // Verificar si tiene relaciones activas
        if ($carrera->usuarios()->count() > 0 || $carrera->laboratorios()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar una carrera con datos asociados',
                'errors' => [
                    'usuarios' => $carrera->usuarios()->count(),
                    'laboratorios' => $carrera->laboratorios()->count(),
                ],
            ], 422);
        }

        $carrera->delete();

        return response()->json([
            'message' => 'Carrera eliminada exitosamente',
        ], 200);
    }
}
