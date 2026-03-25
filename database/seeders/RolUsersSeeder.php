<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RolUsersSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name'     => 'Admin ERP',
                'email'    => 'admin@honeypop.com',
                'password' => Hash::make('password123'),
                'rol'      => 'administrador',
            ],
            [
                'name'     => 'Vendedor ERP',
                'email'    => 'ventas@honeypop.com',
                'password' => Hash::make('password123'),
                'rol'      => 'ventas',
            ],
            [
                'name'     => 'Logística ERP',
                'email'    => 'logistica@honeypop.com',
                'password' => Hash::make('password123'),
                'rol'      => 'logistica',
            ],
        ];

        foreach ($usuarios as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }

        $this->command->info('✅ Usuarios de prueba creados:');
        $this->command->table(
            ['Nombre', 'Email', 'Contraseña', 'Rol'],
            collect($usuarios)->map(fn($u) => [$u['name'], $u['email'], 'password123', $u['rol']])->toArray()
        );
    }
}
