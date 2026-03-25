<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;
use App\Models\Producto;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado', // boolean
        'total',
        'user_id'
    ];

    /**
     * Get the client that belongs to the order.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    /**
     * Get the user that created the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the order.
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'order_product')
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }
}
