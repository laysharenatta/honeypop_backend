<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
