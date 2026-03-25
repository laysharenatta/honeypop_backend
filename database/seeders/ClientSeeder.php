<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'nombre' => 'Juan Pérez',
                'correo' => 'juan.perez@ejemplo.com',
                'telefono' => '555-0101',
                'empresa' => 'Abarrotes El Sol',
                'fecha_registro' => now()->subDays(10),
                'estado' => true,
                'etapa_crm' => 'Prospecto',
            ],
            [
                'nombre' => 'María López',
                'correo' => 'maria.lopez@ejemplo.com',
                'telefono' => '555-0202',
                'empresa' => 'Dulcería La Estrella',
                'fecha_registro' => now()->subDays(5),
                'estado' => true,
                'etapa_crm' => 'Activo',
            ],
            [
                'nombre' => 'Carlos Ramírez',
                'correo' => 'carlos.ramirez@ejemplo.com',
                'telefono' => '555-0303',
                'empresa' => 'Distribuidora Norte',
                'fecha_registro' => now(),
                'estado' => true,
                'etapa_crm' => 'Frecuente',
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['correo' => $client['correo']],
                $client
            );
        }
    }
}
