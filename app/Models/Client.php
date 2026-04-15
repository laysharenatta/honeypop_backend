<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'empresa',
        'fecha_registro',
        'estado',
        'etapa_crm',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'estado' => 'boolean',
        'password' => 'hashed',
    ];

    // Relación: un cliente tiene muchas interacciones
    public function interacciones()
    {
        return $this->hasMany(Interaccion::class, 'cliente_id');
    }
}
