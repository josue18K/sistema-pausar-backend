<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
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
        $query = User::with(['carrera']);

        // Filtrar por rol
        if ($request->has('rol')) {
            $query->where('rol', $request->rol);
        }

        // Filtrar por carrera
        if ($request->has('carrera_id')) {
            $query->where('carrera_id', $request->carrera_id);
        }

        // Buscar por nombre o email
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $usuarios = $query->paginate(15);

        return response()->json([
            'message' => 'Usuarios obtenidos exitosamente',
            'data' => $usuarios,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,almacen,responsable,docente,auditor',
            'carrera_id' => 'nullable|exists:carreras,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $usuario = User::create($validated);
        $usuario->load('carrera');

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'data' => $usuario,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        $usuario->load(['carrera', 'laboratorios', 'movimientos']);

        return response()->json([
            'message' => 'Usuario obtenido exitosamente',
            'data' => $usuario,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:users,email,' . $usuario->id,
            'rol' => 'sometimes|in:admin,almacen,responsable,docente,auditor',
            'carrera_id' => 'nullable|exists:carreras,id',
        ]);

        $usuario->update($validated);
        $usuario->load('carrera');

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'data' => $usuario,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Evitar eliminar el usuario actual
        if ($authUser->id === $usuario->id) {
            return response()->json([
                'message' => 'No puedes eliminar tu propio usuario',
            ], 422);
        }

        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente',
        ], 200);
    }

    /**
     * Cambiar contraseña
     */
    public function cambiarPassword(Request $request, User $usuario)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Solo admin o el mismo usuario
        if ($authUser->id !== $usuario->id && $authUser->rol !== 'admin') {
            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        $request->validate([
            'password_actual' => 'required|string',
            'password_nueva' => 'required|string|min:6|confirmed',
        ]);

        // Verificar contraseña actual si no es admin
        if ($authUser->rol !== 'admin') {
            if (!Hash::check($request->password_actual, $usuario->password)) {
                throw ValidationException::withMessages([
                    'password_actual' => ['La contraseña actual es incorrecta'],
                ]);
            }
        }

        $usuario->update([
            'password' => Hash::make($request->password_nueva),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente',
        ], 200);
    }

    /**
     * Obtener usuarios por rol
     */
    public function porRol($rol)
    {
        $rolesValidos = ['admin', 'almacen', 'responsable', 'docente', 'auditor'];

        if (!in_array($rol, $rolesValidos)) {
            return response()->json([
                'message' => 'Rol inválido',
                'roles_válidos' => $rolesValidos,
            ], 422);
        }

        $usuarios = User::where('rol', $rol)
            ->with('carrera')
            ->get();

        return response()->json([
            'message' => "Usuarios con rol: $rol",
            'data' => $usuarios,
        ], 200);
    }

    /**
     * Obtener estadísticas de usuarios
     */
    public function estadisticas()
    {
        $stats = [
            'total' => User::count(),
            'por_rol' => User::selectRaw('rol, COUNT(*) as total')
                ->groupBy('rol')
                ->get(),
            'por_carrera' => User::selectRaw('carrera_id, COUNT(*) as total')
                ->groupBy('carrera_id')
                ->with('carrera')
                ->get(),
        ];

        return response()->json([
            'message' => 'Estadísticas de usuarios obtenidas',
            'data' => $stats,
        ], 200);
    }
}
