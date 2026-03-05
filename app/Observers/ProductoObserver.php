<?php

namespace App\Observers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Mail\PedidoAutomaticoMail;
use Illuminate\Support\Facades\Mail;

class ProductoObserver
{
    /**
     * Handle the Producto "created" event.
     */
    public function created(Producto $producto): void
    {
        if ($producto->estrategia_logistica === 'PUSH' && $producto->stock_actual <= $producto->stock_minimo) {
            $this->checkAndCreateReplenishmentOrder($producto);
        }
    }

    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto): void
    {
        // Actuar si cambió el stock o si la estrategia cambió a PUSH
        $becamePush = $producto->wasChanged('estrategia_logistica') && $producto->estrategia_logistica === 'PUSH';
        $stockChanged = $producto->wasChanged('stock_actual');

        if (($becamePush || $stockChanged) &&
            $producto->estrategia_logistica === 'PUSH' &&
            $producto->stock_actual <= $producto->stock_minimo
        ) {

            $this->checkAndCreateReplenishmentOrder($producto);
        }
    }

    /**
     * Verifica si es necesario crear un pedido automático.
     */
    private function checkAndCreateReplenishmentOrder(Producto $producto): void
    {
        // Buscar si ya hay un pedido de reposición pendiente para evitar duplicados
        $pedidoExistente = Pedido::where('producto_id', $producto->id)
            ->where('tipo', 'reposicion')
            ->where('estado', 'pendiente')
            ->exists();

        if (!$pedidoExistente) {
            // Cantidad sugerida: para que el stock vuelva a ser al menos el doble del mínimo
            $cantidadBase = max($producto->stock_minimo * 2, 10);
            $cantidad = $cantidadBase - $producto->stock_actual;

            if ($cantidad > 0) {
                $pedido = Pedido::create([
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'tipo' => 'reposicion',
                    'estado' => 'pendiente',
                ]);

                // Enviar correo de notificación al proveedor del producto
                if ($producto->proveedor && $producto->proveedor->correo) {
                    Mail::to($producto->proveedor->correo)->send(new PedidoAutomaticoMail($pedido, $producto));
                }
            }
        }
    }

    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "restored" event.
     */
    public function restored(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "force deleted" event.
     */
    public function forceDeleted(Producto $producto): void
    {
        //
    }
}
