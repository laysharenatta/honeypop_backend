<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proveedors')->insert([
            'nombre' => 'Proveedor 1',
            'contacto' => 'Calle Falsa 123',
            'telefono' => '555-1234',
            'correo' => 'proveedor1@example.com'
        ]);
    }
}
