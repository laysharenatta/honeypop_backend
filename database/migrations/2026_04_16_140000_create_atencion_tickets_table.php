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
        Schema::create('atencion_tickets', function (Blueprint $table) {
            $table->id();

            // Cliente relacionado
            $table->foreignId('cliente_id')
                  ->constrained('clients')
                  ->onDelete('cascade');

            // Asunto del ticket
            $table->string('asunto');

            // Mensaje/descripción del problema
            $table->text('mensaje');

            // Estado del ticket
            $table->enum('estado', ['abierto', 'respondido', 'cerrado'])->default('abierto');

            // Fecha de creación del ticket
            $table->dateTime('fecha');

            // Respuesta del soporte
            $table->text('respuesta')->nullable();

            // Soft deletes para borrado lógico
            $table->softDeletes();

            $table->timestamps();

            // Índices
            $table->index('cliente_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencion_tickets');
    }
};
