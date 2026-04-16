<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InteraccionController;
use App\Http\Controllers\MetricasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\AtencionTicketController;

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
    Route::get('/productos/{id}', [ProductoController::class, 'show']);
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{producto}', [ProductoController::class, 'update']);
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);
    Route::put('/productos/{producto}/estrategia', [ProductoController::class, 'updateEstrategia']);

    //Promociones
    Route::get('/promociones', [PromocionController::class, 'index']);
    Route::post('/promociones', [PromocionController::class, 'store']);
    Route::get('/promociones/{id}', [PromocionController::class, 'show']);
    Route::put('/promociones/{id}', [PromocionController::class, 'update']);
    Route::delete('/promociones/{id}', [PromocionController::class, 'destroy']);

    //Pedidos
    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::put('/pedidos/{pedido}/estado', [PedidoController::class, 'updateEstado']);

    //Movimientos
    Route::post('/inventario/movimientos', [MovimientoInventarioController::class, 'store']);
    Route::get('/productos/{producto}/movimientos', [MovimientoInventarioController::class, 'movements']);

    //Reportes
    Route::get('/reportes/productos-mas-vendidos', [ReportesController::class, 'productosMasVendidos']);
    Route::get('/reportes/inventario-critico', [ReportesController::class, 'inventarioCritico']);
    Route::get('/reportes/rotacion-lenta', [ReportesController::class, 'rotacionLenta']);
    Route::get('/reportes/conteo-estrategias', [ReportesController::class, 'conteoEstrategias']);

    // Órdenes Empresariales
    Route::get('/ordenes', [\App\Http\Controllers\OrderController::class, 'index']);
    Route::post('/ordenes', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::post('/ordenes/procesar', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::get('/ordenes/{id}', [\App\Http\Controllers\OrderController::class, 'show']);
    Route::put('/ordenes/{id}/estado', [\App\Http\Controllers\OrderController::class, 'updateEstado']);

    // Tickets de Atención
    Route::get('/atencion/tickets', [AtencionTicketController::class, 'index']);
    Route::post('/atencion/tickets', [AtencionTicketController::class, 'store']);
    Route::get('/atencion/tickets/{id}', [AtencionTicketController::class, 'show']);
    Route::put('/atencion/tickets/{id}/respuesta', [AtencionTicketController::class, 'updateRespuesta']);

    // ERP y Roles
    Route::get('/usuarios/rol', function(Request $request) {
        return response()->json(['rol' => $request->user()->rol]);
    });
    Route::get('/erp/estado', [\App\Http\Controllers\EstadoERPController::class, 'index']);
    Route::put('/erp/estado', [\App\Http\Controllers\EstadoERPController::class, 'update']);
    Route::get('/erp/metricas', [\App\Http\Controllers\DashboardERPController::class, 'index']);

});

// ─── Autenticación de Clientes (frontend de clientes) ────────────────────────
Route::prefix('cliente')->group(function () {
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/register', [ClientAuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ClientAuthController::class, 'logout']);
        Route::get('/me', [ClientAuthController::class, 'me']);
        Route::post('/pagos/procesar', [PagoController::class, 'procesar']);
        Route::get('/pagos/{id}', [PagoController::class, 'show']);

        // Carrito
        Route::prefix('carrito')->group(function () {
            Route::post('/agregar', [\App\Http\Controllers\CarritoController::class, 'agregar']);
            Route::get('/', [\App\Http\Controllers\CarritoController::class, 'show']);
            Route::put('/item/{item_id}', [\App\Http\Controllers\CarritoController::class, 'updateItem']);
            Route::delete('/item/{item_id}', [\App\Http\Controllers\CarritoController::class, 'removeItem']);
            Route::delete('/vaciar', [\App\Http\Controllers\CarritoController::class, 'vaciar']);
            Route::post('/comprar', [\App\Http\Controllers\CarritoController::class, 'comprar']);
        });
    });
});
