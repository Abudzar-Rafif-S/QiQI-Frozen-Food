<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'address',
        'shipping_cost',
        'shipping_status',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper status
    public function isDelivered()
    {
        return $this->shipping_status === 'on_delivery';
    }
}
