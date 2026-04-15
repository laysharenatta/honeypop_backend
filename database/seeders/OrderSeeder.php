<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Client;
use App\Models\User;
use App\Models\Producto;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $users = User::all();
        $productos = Producto::all();

        if ($clients->isEmpty() || $users->isEmpty() || $productos->isEmpty()) {
            return;
        }

        $pedidos = [
            [
                'cliente_id' => $clients->first()->id,
                'user_id' => $users->first()->id,
                'fecha' => now()->subDays(2),
                'estado' => true,
                'total' => 150.00,
                'productos' => [
                    ['id' => $productos->first()->id, 'cantidad' => 10, 'precio' => 2.50],
                    ['id' => $productos->skip(2)->first()->id ?? $productos->first()->id, 'cantidad' => 2, 'precio' => 45.00],
                ]
            ],
            [
                'cliente_id' => $clients->skip(1)->first()->id ?? $clients->first()->id,
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'fecha' => now()->subDay(),
                'estado' => false,
                'total' => 240.00,
                'productos' => [
                    ['id' => $productos->last()->id, 'cantidad' => 2, 'precio' => 120.00],
                ]
            ],
        ];

        foreach ($pedidos as $pData) {
            $order = Order::create([
                'cliente_id' => $pData['cliente_id'],
                'user_id' => $pData['user_id'],
                'fecha' => $pData['fecha'],
                'estado' => $pData['estado'],
                'total' => $pData['total'],
            ]);

            foreach ($pData['productos'] as $prod) {
                $order->productos()->attach($prod['id'], [
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $prod['precio'],
                ]);
            }
        }
    }
}
