<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'nombre_completo',
        'numero_documento',
        'direccion',
        'ciudad',
        'codigo_postal',
        'pais',
        'telefono',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación: una dirección de facturación pertenece a un cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
