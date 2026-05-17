<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'brand',
        'series',
        'model',
        'category',
        'weight',
        'priceRetail',
        'priceMitra',
        'stock',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}