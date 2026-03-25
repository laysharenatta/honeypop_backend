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
        Schema::create('estado_e_r_p_s', function (Blueprint $table) {
            $table->id();
            $table->enum('nivel', ['Básico', 'Integrado', 'Automatizado', 'Optimizado'])->default('Básico');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_e_r_p_s');
    }
};
