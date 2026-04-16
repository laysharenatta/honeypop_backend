<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtencionTicket extends Model
{
    use HasFactory, SoftDeletes;

    // Campos que se pueden insertar
    protected $fillable = [
        'cliente_id',
        'asunto',
        'mensaje',
        'estado',
        'fecha',
        'respuesta',
    ];

    // Tipos de datos
    protected $casts = [
        'fecha' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relación: un ticket pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Client::class);
    }
}
