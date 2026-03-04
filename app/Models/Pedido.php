<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'cantidad',
        'tipo',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
