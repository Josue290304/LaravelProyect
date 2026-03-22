<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductoController;

// Health check - UNA SOLA VEZ
Route::get('/health-check', function() {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
        'message' => 'Servidor funcionando correctamente'
    ]);
});

// Ruta de login
Route::post('/login', [LoginController::class, 'login']);

// Rutas de Productos SIN protección (acceso libre)
Route::apiResource('productos', ProductoController::class);

// Ruta para usuario autenticado (opcional)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Rutas para pruebas (solo desarrollo)
Route::get('/error-500', function() {
    abort(500, 'Error interno del servidor');
});

Route::get('/error-401', function() {
    abort(401, 'No autorizado');
});

// Ruta para guardar datos
Route::post('/guardar', function() {
    return response()->json([
        'success' => true,
        'message' => 'Datos guardados',
        'received' => request()->all()
    ]);
});