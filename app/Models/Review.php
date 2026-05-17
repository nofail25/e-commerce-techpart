<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'order_id', 'rating', 'comment'];

    // Relasi balik ke user (siapa yang mereview)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}