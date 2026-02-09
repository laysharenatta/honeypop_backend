<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interaccion;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'empresa',
        'fecha_registro',
        'estado',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'estado' => 'boolean',
    ];

    // Relación: un cliente tiene muchas interacciones
    public function interacciones()
    {
        return $this->hasMany(Interaccion::class, 'cliente_id');
    }
}
