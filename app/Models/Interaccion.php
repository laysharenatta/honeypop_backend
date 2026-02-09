<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaccion extends Model
{
    use HasFactory;

    // Nombre de la tabla (porque Laravel la generó raro como interaccions)
    protected $table = 'interacciones';

    // Campos que se pueden insertar
    protected $fillable = [
        'cliente_id',
        'tipo',
        'descripcion',
        'fecha',
        'usuario_id'
    ];

    // Relación: una interacción pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Client::class);
    }

    // Relación: una interacción pertenece a un usuario (opcional)
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
