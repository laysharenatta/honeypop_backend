<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'pedido_id',
        'monto',
        'metodo_pago',
        'estado',
        'fecha'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
