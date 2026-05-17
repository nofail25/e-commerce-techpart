<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address',
        'tracking_number',
        'payment_method',
        'payment_status',
        'payment_code',
        'payment_expires_at',
        'paid_at',
    ];

    protected $casts = [
        'payment_expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relasi: Satu pesanan punya banyak detail barang
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    // TAMBAHKAN INI: Relasi yang menjelaskan bahwa pesanan ini milik seorang User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
