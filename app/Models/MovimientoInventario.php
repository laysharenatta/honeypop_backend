<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimiento_inventarios';

    protected $fillable = [
        'producto_id',
        'tipo',
        'cantidad',
        'motivo',
        'fecha'
    ];

    // Relación: un movimiento pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}