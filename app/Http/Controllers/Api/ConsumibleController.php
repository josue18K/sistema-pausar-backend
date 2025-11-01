<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsumibleRequest;
use App\Http\Requests\UpdateConsumibleRequest;
use App\Models\Consumible;
use App\Models\Alerta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumibleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Consumible::with(['categoria', 'responsable']);

        // Filtrar por categoría
        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtrar solo stock bajo
        if ($request->has('stock_bajo') && $request->stock_bajo == true) {
            $query->whereRaw('stock <= stock_minimo');
        }

        // Buscar por nombre
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $consumibles = $query->paginate(15);

        return response()->json([
            'message' => 'Consumibles obtenidos exitosamente',
            'data' => $consumibles,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsumibleRequest $request)
    {
        $consumible = Consumible::create($request->validated());
        $consumible->load(['categoria', 'responsable']);

        // Crear alerta si stock está bajo
        if ($consumible->stock <= $consumible->stock_minimo) {
            Alerta::create([
                'tipo' => 'stock_bajo',
                'mensaje' => "Stock bajo de {$consumible->nombre}. Stock actual: {$consumible->stock}, Mínimo: {$consumible->stock_minimo}",
                'usuario_id' => $consumible->responsable_id,
            ]);
        }

        return response()->json([
            'message' => 'Consumible creado exitosamente',
            'data' => $consumible,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consumible $consumible)
    {
        $consumible->load(['categoria', 'responsable', 'movimientos']);

        return response()->json([
            'message' => 'Consumible obtenido exitosamente',
            'data' => $consumible,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsumibleRequest $request, Consumible $consumible)
    {
        $consumible->update($request->validated());
        $consumible->load(['categoria', 'responsable']);

        // Crear alerta si stock está bajo
        if ($consumible->stock <= $consumible->stock_minimo) {
            $alertaExistente = Alerta::where('tipo', 'stock_bajo')
                ->where('usuario_id', $consumible->responsable_id)
                ->where('leido', false)
                ->first();

            if (!$alertaExistente) {
                Alerta::create([
                    'tipo' => 'stock_bajo',
                    'mensaje' => "Stock bajo de {$consumible->nombre}. Stock actual: {$consumible->stock}, Mínimo: {$consumible->stock_minimo}",
                    'usuario_id' => $consumible->responsable_id,
                ]);
            }
        }

        return response()->json([
            'message' => 'Consumible actualizado exitosamente',
            'data' => $consumible,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumible $consumible)
    {
        $consumible->delete();

        return response()->json([
            'message' => 'Consumible eliminado exitosamente',
        ], 200);
    }

    /**
     * Obtener consumibles con stock bajo
     */
    public function stockBajo()
    {
        $consumibles = Consumible::whereRaw('stock <= stock_minimo')
            ->with(['categoria', 'responsable'])
            ->get();

        return response()->json([
            'message' => 'Consumibles con stock bajo obtenidos',
            'total' => count($consumibles),
            'data' => $consumibles,
        ], 200);
    }

    /**
     * Actualizar stock de consumible
     */
    public function actualizarStock(Request $request, Consumible $consumible)
    {
        $request->validate([
            'cantidad' => 'required|integer',
            'tipo' => 'required|in:entrada,salida',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $cantidadAnterior = $consumible->stock;

        if ($request->tipo === 'entrada') {
            $consumible->stock += $request->cantidad;
        } else {
            if ($consumible->stock < $request->cantidad) {
                return response()->json([
                    'message' => 'Stock insuficiente',
                    'stock_actual' => $consumible->stock,
                ], 422);
            }
            $consumible->stock -= $request->cantidad;
        }

        $consumible->save();

        // Registrar movimiento
        $consumible->movimientos()->create([
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'usuario_id' => $user->id,
            'observaciones' => $request->observaciones ?? null,
        ]);

        // Crear alerta si stock está bajo
        if ($consumible->stock <= $consumible->stock_minimo) {
            Alerta::create([
                'tipo' => 'stock_bajo',
                'mensaje' => "Stock bajo de {$consumible->nombre}. Stock actual: {$consumible->stock}, Mínimo: {$consumible->stock_minimo}",
                'usuario_id' => $consumible->responsable_id,
            ]);
        }

        return response()->json([
            'message' => 'Stock actualizado exitosamente',
            'data' => [
                'consumible' => $consumible,
                'stock_anterior' => $cantidadAnterior,
                'cambio' => $request->tipo === 'entrada' ? $request->cantidad : -$request->cantidad,
                'stock_actual' => $consumible->stock,
            ],
        ], 200);
    }

    /**
     * Obtener estadísticas de consumibles
     */
    public function estadisticas()
    {
        $stats = [
            'total' => Consumible::count(),
            'stock_bajo' => Consumible::whereRaw('stock <= stock_minimo')->count(),
            'valor_stock' => Consumible::selectRaw('SUM(stock * 1) as total_unidades, COUNT(*) as tipos')
                ->first(),
            'por_categoria' => Consumible::selectRaw('categoria_id, COUNT(*) as total, SUM(stock) as stock_total')
                ->groupBy('categoria_id')
                ->with('categoria')
                ->get(),
        ];

        return response()->json([
            'message' => 'Estadísticas de consumibles obtenidas',
            'data' => $stats,
        ], 200);
    }
}
