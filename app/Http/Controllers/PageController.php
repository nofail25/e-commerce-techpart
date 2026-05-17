<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Memanggil model dari database

class PageController extends Controller
{
    // Fungsi untuk halaman Beranda/Katalog
    public function katalog()
    {
        // Ambil semua data produk dari database MySQL
        $products = Product::all();
        
        // Kirim data tersebut ke file view 'katalog.blade.php'
        return view('katalog', compact('products'));
    }

    // Fungsi untuk halaman Keranjang
    public function keranjang()
    {
        return view('keranjang');
    }


}