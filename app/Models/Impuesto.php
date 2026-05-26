<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'codigo',
        'porcentaje',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'porcentaje' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
