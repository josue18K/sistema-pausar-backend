<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertaController extends Controller
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
        $user = Auth::user();
        $query = Alerta::with(['usuario']);

        // Filtrar por usuario actual
        if ($request->has('mi_usuario') && $request->mi_usuario == true) {
            $query->where('usuario_id', $user?->id);
        }

        // Filtrar solo no leídas
        if ($request->has('no_leidas') && $request->no_leidas == true) {
            $query->where('leido', false);
        }

        // Filtrar por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $alertas = $query->orderByDesc('created_at')->paginate(15);

        return response()->json([
            'message' => 'Alertas obtenidas exitosamente',
            'data' => $alertas,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerta $alerta)
    {
        return response()->json([
            'message' => 'Alerta obtenida exitosamente',
            'data' => $alerta,
        ], 200);
    }

    /**
     * Marcar alerta como leída
     */
    public function marcarLeida(Alerta $alerta)
    {
        $alerta->update(['leido' => true]);

        return response()->json([
            'message' => 'Alerta marcada como leída',
            'data' => $alerta,
        ], 200);
    }

    /**
     * Marcar todas las alertas como leídas
     */
    public function marcarTodasLeidas()
    {
        $user = Auth::user();

        Alerta::where('usuario_id', $user?->id)
            ->where('leido', false)
            ->update(['leido' => true]);

        return response()->json([
            'message' => 'Todas las alertas han sido marcadas como leídas',
        ], 200);
    }

    /**
     * Obtener alertas no leídas del usuario actual
     */
    public function noLeidas()
    {
        $user = Auth::user();

        $alertas = Alerta::where('usuario_id', $user?->id)
            ->where('leido', false)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => 'Alertas no leídas obtenidas',
            'total' => count($alertas),
            'data' => $alertas,
        ], 200);
    }

    /**
     * Eliminar alerta
     */
    public function destroy(Alerta $alerta)
    {
        $alerta->delete();

        return response()->json([
            'message' => 'Alerta eliminada exitosamente',
        ], 200);
    }

    /**
     * Eliminar todas las alertas leídas
     */
    public function eliminarLleidas()
    {
        $user = Auth::user();

        Alerta::where('usuario_id', $user?->id)
            ->where('leido', true)
            ->delete();

        return response()->json([
            'message' => 'Alertas leídas eliminadas exitosamente',
        ], 200);
    }

    /**
     * Obtener estadísticas de alertas
     */
    public function estadisticas()
    {
        $user = Auth::user();

        $stats = [
            'total' => Alerta::count(),
            'no_leidas' => Alerta::where('leido', false)->count(),
            'por_tipo' => Alerta::selectRaw('tipo, COUNT(*) as total')
                ->groupBy('tipo')
                ->get(),
            'mis_alertas_no_leidas' => Alerta::where('usuario_id', $user?->id)
                ->where('leido', false)
                ->count(),
        ];

        return response()->json([
            'message' => 'Estadísticas de alertas obtenidas',
            'data' => $stats,
        ], 200);
    }
}
