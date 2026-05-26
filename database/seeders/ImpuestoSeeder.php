<?php

namespace Database\Seeders;

use App\Models\Impuesto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImpuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Impuesto::create([
            'nombre' => 'IVA',
            'codigo' => 'IVA',
            'porcentaje' => 16.00,
            'descripcion' => 'Impuesto al Valor Agregado',
            'activo' => true,
        ]);
    }
}
