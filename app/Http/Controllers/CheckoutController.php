<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process()
    {
        $userId = Auth::id();
        
        // 1. Ambil data keranjang user saat ini
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        // 2. Hitung Total Harga (Mirip dengan yang ada di tampilan keranjang)
        $isMitra = Auth::user()->role === 'mitra';
        $subtotal = 0;

        foreach ($cartItems as $item) {
            // Tentukan harga (Grosir atau Retail)
            $harga = $isMitra ? $item->product->priceMitra : $item->product->priceRetail;
            $subtotal += ($harga * $item->qty);
        }

        $pajak = $subtotal * 0.11; // PPN 11%
        $ongkir = 25000;
        $totalAkhir = $subtotal + $pajak + $ongkir;

        // 3. Simpan ke tabel `orders`
        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $totalAkhir,
            'status' => 'pending' // Status awal pesanan
        ]);

        // 4. Pindahkan rincian barang ke tabel `order_details`
        foreach ($cartItems as $item) {
            $harga = $isMitra ? $item->product->priceMitra : $item->product->priceRetail;
            
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $harga
            ]);
        }

        // 5. Kosongkan keranjang belanja karena sudah menjadi pesanan
        Cart::where('user_id', $userId)->delete();

        // 6. Arahkan ke halaman Sukses
        return redirect('/checkout/sukses/' . $order->id);
    }
}