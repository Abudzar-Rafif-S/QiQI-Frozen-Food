<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'picture',
        'description',
        'category_id',
        'brand_id',
        'discount_id',
        'price',
        'weight',
        'stock',
    ];

    // RELATIONSHIPS

    // Product → Category (Many to One)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Product → Brand (Many to One)
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Product → Discount (Many to One / optional)
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    // Product → Reviews (Many to One)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Product → Cart (Many to One)
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
