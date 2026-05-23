<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
        ]);

        $this->call([
            // Usuarios / roles (create test users used by other seeders)
            RolUsersSeeder::class,

            // Proveedores -> Productos depend de Proveedor
            ProveedorSeeder::class,
            ProductoSeeder::class,

            // Estado global independiente
            EstadoERPSeeder::class,

            // Clientes
            ClientSeeder::class,

            // Movimientos/Pedidos que dependen de Productos
            PedidoSeeder::class,
            MovimientoInventarioSeeder::class,

            // Órdenes e interacciones (requieren Client, User, Producto)
            OrderSeeder::class,
            InteraccionSeeder::class,
        ]);
    }
}
