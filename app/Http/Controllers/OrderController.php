<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // --- FUNGSI 1: MENAMPILKAN HALAMAN PESANAN SAYA ---
    public function riwayat()
    {
        // Ambil SEMUA order milik user ini, urutkan dari yang terbaru
        $orders = Order::with(['details.product', 'details.returnRequests'])
                       ->where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('pesanan-saya', compact('orders'));
    }

    // --- FUNGSI 2: MENYIMPAN ULASAN (RATING) ---
    public function simpanUlasan(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'order_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // Pastikan pesanan ini milik user yang login dan statusnya sudah "selesai"
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'selesai')
                      ->firstOrFail();

        // Cek apakah sudah pernah direview sebelumnya (mencegah spam)
        $cekReview = Review::where('order_id', $request->order_id)
                           ->where('product_id', $request->product_id)
                           ->first();
                           
        if ($cekReview) {
            return back()->with('error', 'Anda sudah mengulas produk ini.');
        }

        // Simpan ulasan ke database
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}
