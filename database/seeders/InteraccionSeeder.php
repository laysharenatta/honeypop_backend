<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Interaccion;
use App\Models\Client;
use App\Models\User;

class InteraccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $users = User::all();

        if ($clients->isEmpty() || $users->isEmpty()) {
            return;
        }

        $interacciones = [
            [
                'cliente_id' => $clients->first()->id,
                'usuario_id' => $users->first()->id,
                'tipo' => 'Llamada',
                'descripcion' => 'Llamada de seguimiento para prospecto.',
                'fecha' => now()->subDays(3),
            ],
            [
                'cliente_id' => $clients->skip(1)->first()->id ?? $clients->first()->id,
                'usuario_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'tipo' => 'Correo',
                'descripcion' => 'Enlace a catálogo enviado.',
                'fecha' => now()->subDay(),
            ],
            [
                'cliente_id' => $clients->last()->id,
                'usuario_id' => $users->first()->id,
                'tipo' => 'Visita',
                'descripcion' => 'Reunión presencial para cerrar trato.',
                'fecha' => now(),
            ],
        ];

        foreach ($interacciones as $i) {
            Interaccion::create($i);
        }
    }
}
