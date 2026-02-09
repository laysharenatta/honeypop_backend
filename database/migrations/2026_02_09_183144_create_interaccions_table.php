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
        Schema::create('interacciones', function (Blueprint $table) {

            $table->id();

            // Cliente relacionado
            $table->foreignId('cliente_id')
                  ->constrained('clients')
                  ->onDelete('cascade');

            // Tipo de interacción
            $table->enum('tipo', ['llamada', 'correo', 'reunion']);

            // Descripción
            $table->text('descripcion');

            // Fecha de interacción
            $table->dateTime('fecha');

            // Usuario que registró la interacción (opcional)
            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interacciones');
    }
};
