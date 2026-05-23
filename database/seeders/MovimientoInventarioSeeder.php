<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovimientoInventario;
use App\Models\Producto;

class MovimientoInventarioSeeder extends Seeder
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

        $movimientos = [
            [
                'producto_id' => $productos->first()->id,
                // migration enum: tipo => ['entrada','salida']
                // migration enum: motivo => ['venta','reposición','ajuste']
                'tipo' => 'entrada',
                'cantidad' => 100,
                'motivo' => 'reposición',
                'fecha' => now()->subDays(5),
            ],
            [
                'producto_id' => $productos->first()->id,
                'tipo' => 'salida',
                'cantidad' => 20,
                'motivo' => 'venta',
                'fecha' => now()->subDays(2),
            ],
            [
                'producto_id' => $productos->skip(1)->first()->id ?? $productos->first()->id,
                'tipo' => 'entrada',
                'cantidad' => 50,
                'motivo' => 'ajuste',
                'fecha' => now()->subDay(),
            ],
            [
                'producto_id' => $productos->last()->id,
                'tipo' => 'salida',
                'cantidad' => 5,
                // 'Muestra gratis' -> no exact enum, choose 'ajuste' (inventory adjustment)
                'motivo' => 'ajuste',
                'fecha' => now(),
            ],
        ];

        foreach ($movimientos as $m) {
            MovimientoInventario::create($m);
        }
    }
}
