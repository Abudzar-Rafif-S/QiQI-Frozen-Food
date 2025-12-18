<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'payment_status',
        'snap_token',
        'payment_date',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper: apakah pembayaran sukses?
    public function isPaid()
    {
        return $this->payment_status === 'settlement';
    }
}
