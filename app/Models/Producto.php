<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'stock_actual',
        'stock_minimo',
        'proveedor_id',
        'costo_unitario',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}
