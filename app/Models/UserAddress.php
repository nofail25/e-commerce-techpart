<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    // Mengizinkan Laravel untuk mengisi kolom-kolom ini
    protected $fillable = ['user_id', 'label', 'address'];
}