<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Consumible;
use App\Models\Movimiento;
use App\Models\Alerta;
use App\Models\Laboratorio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Obtener datos para el dashboard principal
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Determinar qué datos mostrar según el rol
        $query_items = Item::query();
        $query_consumibles = Consumible::query();
        $query_laboratorios = Laboratorio::query();

        // Filtrar por carrera del responsable
        if ($user->rol === 'responsable' && $user->carrera_id) {
            $query_items->where('carrera_id', $user->carrera_id);
            $query_consumibles->where('responsable_id', $user->id);
            $query_laboratorios->where('carrera_id', $user->carrera_id);
        } elseif ($user->rol === 'docente' && $user->carrera_id) {
            $query_items->where('carrera_id', $user->carrera_id);
            $query_laboratorios->where('carrera_id', $user->carrera_id);
        }

        // KPIs principales
        $kpis = [
            'items_totales' => $query_items->count(),
            'items_activos' => (clone $query_items)->where('estado', 'activo')->count(),
            'items_mantenimiento' => (clone $query_items)->where('estado', 'mantenimiento')->count(),
            'items_bajas' => (clone $query_items)->where('estado', 'baja')->count(),
            'consumibles_stock_bajo' => Consumible::whereRaw('stock <= stock_minimo')->count(),
            'laboratorios' => $query_laboratorios->count(),
            'alertas_no_leidas' => Alerta::where('usuario_id', $user->id)
                ->where('leido', false)
                ->count(),
        ];

        // Gráfico: Items por categoría
        $items_por_categoria = (clone $query_items)
            ->selectRaw('categorias.nombre, COUNT(items.id) as total')
            ->join('categorias', 'items.categoria_id', '=', 'categorias.id')
            ->groupBy('categorias.nombre', 'items.categoria_id')
            ->get();

        // Gráfico: Items por laboratorio
        $items_por_laboratorio = (clone $query_items)
            ->selectRaw('laboratorios.nombre, COUNT(items.id) as total')
            ->leftJoin('laboratorios', 'items.laboratorio_id', '=', 'laboratorios.id')
            ->groupBy('laboratorios.nombre', 'items.laboratorio_id')
            ->get();

        // Gráfico: Estados de items
        $estados_items = [
            'activo' => (clone $query_items)->where('estado', 'activo')->count(),
            'mantenimiento' => (clone $query_items)->where('estado', 'mantenimiento')->count(),
            'baja' => (clone $query_items)->where('estado', 'baja')->count(),
        ];

        // Movimientos recientes
        $query_movimientos = Movimiento::query();
        if ($user->rol === 'responsable' || $user->rol === 'docente') {
            $query_movimientos->where('usuario_id', $user->id);
        }

        $movimientos_recientes = $query_movimientos
            ->with(['item', 'consumible', 'usuario'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Consumibles con stock bajo
        $consumibles_stock_bajo = Consumible::whereRaw('stock <= stock_minimo')
            ->with('categoria')
            ->limit(5)
            ->get();

        // Alertas recientes
        $alertas_recientes = Alerta::where('usuario_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Valor total de items
        $valor_total_items = (clone $query_items)->sum('valor');

        // Movimientos por mes (últimos 6 meses)
        $movimientos_por_mes = Movimiento::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return response()->json([
            'message' => 'Datos del dashboard obtenidos exitosamente',
            'data' => [
                'kpis' => $kpis,
                'items_por_categoria' => $items_por_categoria,
                'items_por_laboratorio' => $items_por_laboratorio,
                'estados_items' => $estados_items,
                'movimientos_recientes' => $movimientos_recientes,
                'consumibles_stock_bajo' => $consumibles_stock_bajo,
                'alertas_recientes' => $alertas_recientes,
                'valor_total_items' => $valor_total_items,
                'movimientos_por_mes' => $movimientos_por_mes,
            ],
        ], 200);
    }

    /**
     * Obtener resumen general (solo para admin)
     */
    public function resumenGeneral()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->rol !== 'admin') {
            return response()->json([
                'message' => 'Solo administradores pueden acceder a este recurso',
            ], 403);
        }

        $resumen = [
            'items' => [
                'total' => Item::count(),
                'activos' => Item::where('estado', 'activo')->count(),
                'valor_total' => Item::sum('valor'),
            ],
            'consumibles' => [
                'total' => Consumible::count(),
                'stock_bajo' => Consumible::whereRaw('stock <= stock_minimo')->count(),
            ],
            'movimientos' => [
                'total' => Movimiento::count(),
                'ultimo_mes' => Movimiento::where('created_at', '>=', now()->subMonth())->count(),
            ],
            'alertas' => [
                'total' => Alerta::count(),
                'no_leidas' => Alerta::where('leido', false)->count(),
            ],
            'laboratorios' => Laboratorio::count(),
            'usuarios' => User::count(),
        ];

        return response()->json([
            'message' => 'Resumen general obtenido',
            'data' => $resumen,
        ], 200);
    }
}
