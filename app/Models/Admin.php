<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    // Jika tabel memakai plural 'admins', ini bisa dihapus karena Laravel otomatis mengenali
    protected $table = 'admins';

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'fullname',
        'phone_number',
        'address',
        'avatar',
    ];

    /**
     * Relasi ke tabel users
     * Satu admin memiliki satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
