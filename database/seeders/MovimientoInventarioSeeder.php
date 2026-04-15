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
                'tipo' => 'Entrada',
                'cantidad' => 100,
                'motivo' => 'Compra a proveedor',
                'fecha' => now()->subDays(5),
            ],
            [
                'producto_id' => $productos->first()->id,
                'tipo' => 'Salida',
                'cantidad' => 20,
                'motivo' => 'Venta Directa',
                'fecha' => now()->subDays(2),
            ],
            [
                'producto_id' => $productos->skip(1)->first()->id ?? $productos->first()->id,
                'tipo' => 'Entrada',
                'cantidad' => 50,
                'motivo' => 'Ajuste de inventario',
                'fecha' => now()->subDay(),
            ],
            [
                'producto_id' => $productos->last()->id,
                'tipo' => 'Salida',
                'cantidad' => 5,
                'motivo' => 'Muestra gratis',
                'fecha' => now(),
            ],
        ];

        foreach ($movimientos as $m) {
            MovimientoInventario::create($m);
        }
    }
}
