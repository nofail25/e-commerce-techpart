<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'product_id', 'qty', 'price'];

    // Relasi: Detail ini milik produk apa
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }
}
