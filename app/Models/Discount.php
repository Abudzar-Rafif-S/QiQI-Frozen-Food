<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['value'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
