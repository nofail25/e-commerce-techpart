<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'qty'];

    // Relasi: Satu item keranjang pasti punya satu produk fisik
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}