<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * Tabel yang digunakan model ini.
     */
    protected $table = 'customers';

    /**
     * Kolom yang bisa diisi (mass assignable).
     */
    protected $fillable = [
        'fullname',
        'user_id',
        'city_id',
        'address',
        'phone',
        'postal_code',
        'avatar',
    ];

    /**
     * Relasi ke tabel users.
     * Customer dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel cities.
     * Customer tinggal di satu city.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
