<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaboratorioRequest;
use App\Http\Requests\UpdateLaboratorioRequest;
use App\Models\Laboratorio;

class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratorios = Laboratorio::with(['carrera', 'responsable'])
            ->withCount(['items'])
            ->paginate(10);

        return response()->json([
            'message' => 'Laboratorios obtenidos exitosamente',
            'data' => $laboratorios,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboratorioRequest $request)
    {
        $laboratorio = Laboratorio::create($request->validated());
        $laboratorio->load(['carrera', 'responsable']);

        return response()->json([
            'message' => 'Laboratorio creado exitosamente',
            'data' => $laboratorio,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratorio $laboratorio)
    {
        $laboratorio->load(['carrera', 'responsable', 'items']);

        return response()->json([
            'message' => 'Laboratorio obtenido exitosamente',
            'data' => $laboratorio,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboratorioRequest $request, Laboratorio $laboratorio)
    {
        $laboratorio->update($request->validated());
        $laboratorio->load(['carrera', 'responsable']);

        return response()->json([
            'message' => 'Laboratorio actualizado exitosamente',
            'data' => $laboratorio,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratorio $laboratorio)
    {
        if ($laboratorio->items()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar un laboratorio con items asociados',
                'errors' => [
                    'items' => $laboratorio->items()->count(),
                ],
            ], 422);
        }

        $laboratorio->delete();

        return response()->json([
            'message' => 'Laboratorio eliminado exitosamente',
        ], 200);
    }

    /**
     * Obtener laboratorios por carrera
     */
    public function porCarrera($carreraId)
    {
        $laboratorios = Laboratorio::where('carrera_id', $carreraId)
            ->with(['carrera', 'responsable'])
            ->get();

        return response()->json([
            'message' => 'Laboratorios obtenidos por carrera',
            'data' => $laboratorios,
        ], 200);
    }
}
