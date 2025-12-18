<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has customer role.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Relasi ke tabel admins
     * Satu user memiliki satu admin
     */
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Relasi ke tabel customers
     * Satu user memiliki satu customer
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
