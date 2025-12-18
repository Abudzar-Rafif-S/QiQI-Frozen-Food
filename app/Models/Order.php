<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'gross_amount',
        'status',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi: Order memiliki banyak OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order memiliki satu Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relasi: Order memiliki satu Shipping
    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }
}
