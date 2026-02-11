<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InteraccionController;
use App\Http\Controllers\MetricasController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::post('/clients', [ClientController::class, 'store']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
Route::get("/interacciones", [InteraccionController::class, "index"])->middleware("auth:sanctum");
Route::post("/interacciones", [InteraccionController::class, "store"])->middleware("auth:sanctum");
Route::get('/clientes/{id}/interacciones', [InteraccionController::class, 'historial']);
Route::put("/clientes/{id}/etapa", [ClientController::class, "actualizarEtapa"]);
Route::get("/metricas",[MetricasController::class,"index"])->middleware("auth:sanctum");
Route::post("/login", [AuthController::class, "login"]);
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
});