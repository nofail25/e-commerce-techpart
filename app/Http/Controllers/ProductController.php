<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 

class ProductController extends Controller
{
    // 1. Fungsi menampilkan halaman katalog dengan filter
    // 1. Fungsi menampilkan halaman katalog dengan filter
    public function index(Request $request)
    {
        $query = \App\Models\Product::query();

        // -- TAMBAHAN BARU: Filter Kategori Populer --
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // -- TAMBAHAN BARU: Filter Pencarian Kata Kunci --
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // -- BAWAAN ASLI: Filter Merk --
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        
        // -- BAWAAN ASLI: Filter Seri --
        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }

        // Eksekusi pengambilan data
        $products = $query->get();
        $brands = \App\Models\Product::whereNotNull('brand')->distinct()->pluck('brand');
        
        // Cek apakah user yang login adalah mitra (untuk harga grosir)
        $isMitra = auth()->check() && auth()->user()->role === 'mitra';

        // -- BAWAAN ASLI: JIKA PERMINTAAN DARI AJAX --
        if ($request->ajax()) {
            return view('partials.product_list', compact('products'))->render();
        }

        // Kirim semua variabel ke tampilan
        return view('katalog', compact('products', 'brands', 'isMitra'));
    }

    // 2. Mesin AJAX: Mengambil "Seri" berdasarkan "Merk"
    public function getSeries(Request $request)
    {
        $series = \App\Models\Product::where('brand', $request->brand)
                                     ->whereNotNull('series')
                                     ->distinct()
                                     ->pluck('series');
                                     
        return response()->json($series);
    }
    public function detailProduk($id)
    {
        // Cari produk berdasarkan ID beserta ulasan dan nama pembelinya
        $product = \App\Models\Product::with(['reviews.user'])
            ->findOrFail($id);

        // Tampilkan halaman produk_detail.blade.php
        return view('produk_detail', compact('product'));
    }
}
