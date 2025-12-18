<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $table = 'shipping_rates';

    protected $fillable = [
        'city_id',
        'price_per_kg',
        'note',
    ];

    /**
     * Relasi ke tabel cities
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
