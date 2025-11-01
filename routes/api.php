<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarreraController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\LaboratorioController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ConsumibleController;
use App\Http\Controllers\Api\MovimientoController;
use App\Http\Controllers\Api\AlertaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\DashboardController;

// Rutas públicas (sin autenticación)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas (con autenticación)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh-token', [AuthController::class, 'refresh']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/resumen-general', [DashboardController::class, 'resumenGeneral']);

    // Carreras
    Route::apiResource('carreras', CarreraController::class);
    Route::get('/carreras/{carrera}/detalles', [CarreraController::class, 'show']);

    // Categorías
    Route::apiResource('categorias', CategoriaController::class);

    // Laboratorios
    Route::apiResource('laboratorios', LaboratorioController::class);
    Route::get('/carreras/{carreraId}/laboratorios', [LaboratorioController::class, 'porCarrera']);

    // Items
    Route::apiResource('items', ItemController::class);
    Route::get('/items/por-laboratorio/{laboratorioId}', [ItemController::class, 'porLaboratorio']);
    Route::get('/items/por-estado/{estado}', [ItemController::class, 'porEstado']);
    Route::get('/items-estadisticas', [ItemController::class, 'estadisticas']);

    // Consumibles
    Route::apiResource('consumibles', ConsumibleController::class);
    Route::get('/consumibles/stock-bajo', [ConsumibleController::class, 'stockBajo']);
    Route::post('/consumibles/{consumible}/actualizar-stock', [ConsumibleController::class, 'actualizarStock']);
    Route::get('/consumibles-estadisticas', [ConsumibleController::class, 'estadisticas']);

    // Movimientos
    Route::apiResource('movimientos', MovimientoController::class)->only(['index', 'store', 'show']);
    Route::get('/movimientos/por-item/{itemId}', [MovimientoController::class, 'porItem']);
    Route::get('/movimientos-estadisticas', [MovimientoController::class, 'estadisticas']);

    // Alertas
    Route::apiResource('alertas', AlertaController::class)->only(['index', 'show', 'destroy']);
    Route::post('/alertas/{alerta}/marcar-leida', [AlertaController::class, 'marcarLeida']);
    Route::post('/alertas/marcar-todas-leidas', [AlertaController::class, 'marcarTodasLeidas']);
    Route::get('/alertas/no-leidas', [AlertaController::class, 'noLeidas']);
    Route::delete('/alertas/eliminar-leidas', [AlertaController::class, 'eliminarLleidas']);
    Route::get('/alertas-estadisticas', [AlertaController::class, 'estadisticas']);

    // Usuarios (solo admin)
    Route::middleware('can:es-admin')->group(function () {
        Route::apiResource('usuarios', UsuarioController::class);
        Route::get('/usuarios/por-rol/{rol}', [UsuarioController::class, 'porRol']);
        Route::get('/usuarios-estadisticas', [UsuarioController::class, 'estadisticas']);
    });

    Route::apiResource('usuarios', UsuarioController::class);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::get('/usuarios/{user}', [UsuarioController::class, 'show']);
    Route::put('/usuarios/{user}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy']);



    // Cambiar contraseña (cualquier usuario)
    Route::post('/usuarios/{usuario}/cambiar-password', [UsuarioController::class, 'cambiarPassword']);
});

// Ruta de prueba
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
