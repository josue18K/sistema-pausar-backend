<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimientoRequest;
use App\Models\Movimiento;
use App\Models\Item;
use App\Models\Consumible;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
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
        $query = Movimiento::with(['item', 'consumible', 'origen', 'destino', 'usuario']);

        // Filtrar por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por item
        if ($request->has('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        // Filtrar por consumible
        if ($request->has('consumible_id')) {
            $query->where('consumible_id', $request->consumible_id);
        }

        // Filtrar por rango de fechas
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $movimientos = $query->orderByDesc('created_at')->paginate(15);

        return response()->json([
            'message' => 'Movimientos obtenidos exitosamente',
            'data' => $movimientos,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovimientoRequest $request)
    {
        $validated = $request->validated();
        $validated['usuario_id'] = Auth::id();

        // Validar que sea item o consumible, no ambos
        if (!$validated['item_id'] && !$validated['consumible_id']) {
            return response()->json([
                'message' => 'Debe especificar un item o consumible',
            ], 422);
        }

        // Procesar movimiento de item
        if ($validated['item_id']) {
            $item = Item::find($validated['item_id']);

            if ($validated['tipo'] === 'mantenimiento') {
                $item->estado = 'mantenimiento';
                $item->save();

                Alerta::create([
                    'tipo' => 'mantenimiento',
                    'mensaje' => "El item {$item->nombre} ha entrado en mantenimiento",
                    'usuario_id' => $item->responsable_id,
                ]);
            } elseif ($validated['tipo'] === 'baja') {
                $item->estado = 'baja';
                $item->save();

                Alerta::create([
                    'tipo' => 'baja',
                    'mensaje' => "El item {$item->nombre} ha sido dado de baja",
                    'usuario_id' => $item->responsable_id,
                ]);
            }
        }

        // Procesar movimiento de consumible
        if ($validated['consumible_id']) {
            $consumible = Consumible::find($validated['consumible_id']);

            if ($validated['tipo'] === 'salida') {
                if ($consumible->stock < $validated['cantidad']) {
                    return response()->json([
                        'message' => 'Stock insuficiente',
                        'stock_actual' => $consumible->stock,
                    ], 422);
                }
                $consumible->stock -= $validated['cantidad'];
            } elseif ($validated['tipo'] === 'entrada') {
                $consumible->stock += $validated['cantidad'];
            }

            $consumible->save();

            // Crear alerta si stock está bajo
            if ($consumible->stock <= $consumible->stock_minimo) {
                Alerta::create([
                    'tipo' => 'stock_bajo',
                    'mensaje' => "Stock bajo de {$consumible->nombre}. Stock actual: {$consumible->stock}",
                    'usuario_id' => $consumible->responsable_id,
                ]);
            }
        }

        $movimiento = Movimiento::create($validated);
        $movimiento->load(['item', 'consumible', 'origen', 'destino', 'usuario']);

        return response()->json([
            'message' => 'Movimiento registrado exitosamente',
            'data' => $movimiento,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento)
    {
        $movimiento->load(['item', 'consumible', 'origen', 'destino', 'usuario']);

        return response()->json([
            'message' => 'Movimiento obtenido exitosamente',
            'data' => $movimiento,
        ], 200);
    }

    /**
     * Obtener movimientos por item
     */
    public function porItem($itemId)
    {
        $movimientos = Movimiento::where('item_id', $itemId)
            ->with(['usuario', 'origen', 'destino'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => 'Movimientos del item obtenidos',
            'data' => $movimientos,
        ], 200);
    }

    /**
     * Obtener estadísticas de movimientos
     */
    public function estadisticas(Request $request)
    {
        $query = Movimiento::query();

        // Filtrar por rango de fechas
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $stats = [
            'total' => $query->count(),
            'por_tipo' => $query->selectRaw('tipo, COUNT(*) as total')
                ->groupBy('tipo')
                ->get(),
            'ultimos_30_dias' => $query->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ];

        return response()->json([
            'message' => 'Estadísticas de movimientos obtenidas',
            'data' => $stats,
        ], 200);
    }
}
