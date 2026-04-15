<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Producto;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::all();

        if ($productos->isEmpty()) {
            return;
        }

        $pedidos = [
            [
                'producto_id' => $productos->skip(1)->first()->id ?? $productos->first()->id,
                'cantidad' => 100,
                'tipo' => 'Entrada',
                'estado' => 'Completado',
            ],
            [
                'producto_id' => $productos->last()->id,
                'cantidad' => 50,
                'tipo' => 'Compra',
                'estado' => 'Pendiente',
            ],
        ];

        foreach ($pedidos as $p) {
            Pedido::create($p);
        }
    }
}
