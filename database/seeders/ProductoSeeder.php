<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = Proveedor::all();

        if ($proveedores->isEmpty()) {
            $this->command->error('No hay proveedores registrados, no se puede crear productos.');
            return;
        }

        $productos = [
            [
                'nombre' => 'Honey Pop Classic',
                'descripcion' => 'Paleta de miel tradicional 15g',
                'categoria' => 'Paletas',
                'stock_actual' => 500,
                'stock_minimo' => 100,
                'estrategia_logistica' => 'PULL',
                'proveedor_id' => $proveedores->first()->id,
                'costo_unitario' => 2.50
            ],
            [
                'nombre' => 'Honey Pop Mentol',
                'descripcion' => 'Paleta de miel con mentol 15g',
                'categoria' => 'Paletas',
                'stock_actual' => 80,
                'stock_minimo' => 100,
                'estrategia_logistica' => 'PULL',
                'proveedor_id' => $proveedores->first()->id,
                'costo_unitario' => 2.75
            ],
            [
                'nombre' => 'Miel Orgánica 250g',
                'descripcion' => 'Frasco de miel pura de abeja',
                'categoria' => 'Frascos',
                'stock_actual' => 200,
                'stock_minimo' => 50,
                'estrategia_logistica' => 'PULL',
                'proveedor_id' => $proveedores->skip(1)->first()->id ?? $proveedores->first()->id,
                'costo_unitario' => 45.00
            ],
            [
                'nombre' => 'Cera de Abeja Industrial',
                'descripcion' => 'Bloque de cera 1kg',
                'categoria' => 'Insumos',
                'stock_actual' => 15,
                'stock_minimo' => 20,
                'estrategia_logistica' => 'PUSH',
                'proveedor_id' => $proveedores->last()->id,
                'costo_unitario' => 120.00
            ],
        ];

        foreach ($productos as $prod) {
            DB::table('productos')->updateOrInsert(
                ['nombre' => $prod['nombre']],
                $prod
            );
        }
    }
}
