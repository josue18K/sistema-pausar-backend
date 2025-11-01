<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'register']);
    }
    /**
     * Login de usuario
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('api-token', ['*'], now()->addDay())->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'carrera_id' => $user->carrera_id,
            ],
        ], 200);
    }

    /**
     * Registro de nuevo usuario
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'carrera_id' => $request->carrera_id,
        ]);

        $token = $user->createToken('api-token', ['*'], now()->addDay())->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
            ],
        ], 201);
    }

    /**
     * Obtener usuario actual
     */
    public function me()
    {
        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'carrera_id' => $user->carrera_id,
                'carrera' => $user->carrera()->select('id', 'nombre', 'abreviatura')->first(),
            ],
        ], 200);
    }

    /**
     * Logout
     */
    public function logout()
    {
        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout exitoso',
        ], 200);
    }

    /**
     * Refrescar token
     */
    public function refresh()
    {
        /** @var User $user */
        $user = Auth::user();

        // Eliminar token actual
        $user->currentAccessToken()->delete();

        // Crear nuevo token
        $token = $user->createToken('api-token', ['*'], now()->addDay())->plainTextToken;

        return response()->json([
            'message' => 'Token refrescado',
            'token' => $token,
        ], 200);
    }
}
