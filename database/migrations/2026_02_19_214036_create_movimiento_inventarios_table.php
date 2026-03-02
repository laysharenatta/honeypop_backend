<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('movimiento_inventarios', function (Blueprint $table) {
        $table->id();

        // Relación con productos
        $table->foreignId('producto_id')
              ->constrained('productos')
              ->onDelete('cascade');

        // Tipo de movimiento
        $table->enum('tipo', ['entrada', 'salida']);

        // Cantidad movida
        $table->integer('cantidad');

        // Motivo del movimiento
        $table->enum('motivo', ['venta', 'reposición', 'ajuste']);

        // Fecha del movimiento
        $table->dateTime('fecha');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventarios');
    }
};
