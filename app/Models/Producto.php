<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovimientoInventario;
use App\Models\Promocion;

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

    /**
     * Get the promotions for this product.
     */
    public function promociones()
    {
        return $this->belongsToMany(Promocion::class, 'producto_promocion')
                    ->withTimestamps();
    }
}
