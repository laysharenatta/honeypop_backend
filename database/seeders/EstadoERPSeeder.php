<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoERP;

class EstadoERPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoERP::updateOrCreate(
            ['id' => 1],
            ['nivel' => 'normal']
        );
    }
}
