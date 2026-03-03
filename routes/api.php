<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InteraccionController;
use App\Http\Controllers\MetricasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\MovimientoInventarioController;

/*TODAS LAS RUTAS QUE HAGAS NUEVAS, METELAS DENTRO DE EL BLOQUE DE RUTAS DE ABAJO, LAS QUE ESTAN PROTEGIDAS POR SANCTUM*/

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

Route::middleware("auth:sanctum")->group(function () {

    Route::post("/logout", [AuthController::class, "logout"]);

    Route::get("/metricas", [MetricasController::class, "index"]);

    //Clientes
    Route::get('/clientes', [ClientController::class, 'index']);
    Route::get('/clientes/{id}', [ClientController::class, 'show']);
    Route::post('/clientes', [ClientController::class, 'store']);
    Route::put('/clientes/{id}', [ClientController::class, 'update']);
    Route::delete('/clientes/{id}', [ClientController::class, 'destroy']);
    Route::get('/clientes/{id}/interacciones', [InteraccionController::class, 'historial']);
    Route::put("/clientes/{id}/etapa", [ClientController::class, "actualizarEtapa"]);

    //Interacciones
    Route::get("/interacciones", [InteraccionController::class, "index"]);
    Route::post("/interacciones", [InteraccionController::class, "store"]);

    //Proveedores
    Route::get('/proveedores', [ProveedorController::class, 'index']);
    Route::post('/proveedores', [ProveedorController::class, 'store']);
    Route::get('/proveedores/{id}', [ProveedorController::class, 'show']);
    Route::put('/proveedores/{id}', [ProveedorController::class, 'update']);
    Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy']);

    //Productos
    Route::get('/productos', [ProductoController::class, 'index']);
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{producto}', [ProductoController::class, 'update']);
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);

    //Movimientos
    Route::post('/inventario/movimientos', [MovimientoInventarioController::class, 'store']);
    Route::get('/productos/{producto}/movimientos', [MovimientoInventarioController::class, 'movements']);
});
