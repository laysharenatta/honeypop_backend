<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovimientoInventario;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'stock_actual',
        'stock_minimo',
        'estrategia_logistica',
        'proveedor_id',
        'costo_unitario',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Un producto tiene muchos movimientos
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
