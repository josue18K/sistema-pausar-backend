<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::with(['categoria', 'laboratorio', 'carrera', 'responsable']);

        // Filtrar por categoría
        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtrar por laboratorio
        if ($request->has('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        // Filtrar por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        // Buscar por código o nombre
        if ($request->has('search')) {
            $query->where('codigo', 'like', '%' . $request->search . '%')
                ->orWhere('nombre', 'like', '%' . $request->search . '%');
        }

        $items = $query->paginate(15);

        return response()->json([
            'message' => 'Items obtenidos exitosamente',
            'data' => $items,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();

        // Procesar imagen si existe
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item = Item::create($validated);
        $item->load(['categoria', 'laboratorio', 'carrera', 'responsable']);

        return response()->json([
            'message' => 'Item creado exitosamente',
            'data' => $item,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['categoria', 'laboratorio', 'carrera', 'responsable', 'movimientos']);

        return response()->json([
            'message' => 'Item obtenido exitosamente',
            'data' => $item,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();

        // Procesar imagen si existe
        if ($request->hasFile('foto')) {
            // Eliminar imagen anterior si existe
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            $validated['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item->update($validated);
        $item->load(['categoria', 'laboratorio', 'carrera', 'responsable']);

        return response()->json([
            'message' => 'Item actualizado exitosamente',
            'data' => $item,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Eliminar imagen si existe
        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }

        $item->delete();

        return response()->json([
            'message' => 'Item eliminado exitosamente',
        ], 200);
    }


    /**
     * Obtener items por laboratorio
     */
    public function porLaboratorio($laboratorioId)
    {
        $items = Item::where('laboratorio_id', $laboratorioId)
            ->with(['categoria', 'laboratorio', 'carrera'])
            ->get();

        return response()->json([
            'message' => 'Items obtenidos por laboratorio',
            'data' => $items,
        ], 200);
    }

    /**
     * Obtener items por estado
     */
    public function porEstado($estado)
    {
        $estados = ['activo', 'mantenimiento', 'baja'];

        if (!in_array($estado, $estados)) {
            return response()->json([
                'message' => 'Estado inválido',
                'estados_válidos' => $estados,
            ], 422);
        }

        $items = Item::where('estado', $estado)
            ->with(['categoria', 'laboratorio', 'carrera'])
            ->paginate(15);

        return response()->json([
            'message' => "Items con estado: $estado",
            'data' => $items,
        ], 200);
    }

    /**
     * Obtener estadísticas de items
     */
    public function estadisticas()
    {
        $stats = [
            'total' => Item::count(),
            'activos' => Item::where('estado', 'activo')->count(),
            'mantenimiento' => Item::where('estado', 'mantenimiento')->count(),
            'bajas' => Item::where('estado', 'baja')->count(),
            'por_categoria' => Item::selectRaw('categoria_id, COUNT(*) as total')
                ->groupBy('categoria_id')
                ->with('categoria')
                ->get(),
            'por_laboratorio' => Item::selectRaw('laboratorio_id, COUNT(*) as total')
                ->groupBy('laboratorio_id')
                ->with('laboratorio')
                ->get(),
            'valor_total' => Item::sum('valor'),
        ];

        return response()->json([
            'message' => 'Estadísticas de items obtenidas',
            'data' => $stats,
        ], 200);
    }
}
