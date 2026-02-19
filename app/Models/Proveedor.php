<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto; // <- Esta línea es necesaria

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedors';

    protected $fillable = [
        'nombre', 'contacto', 'correo', 'telefono',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
