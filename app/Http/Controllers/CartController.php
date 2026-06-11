<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;

class CartController extends Controller
{
    // --- FUNGSI 1: TAMBAH KE KERANJANG VIA AJAX ---
    public function tambah(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $request->validate(['product_id' => 'required|exists:products,id']);

        // Validasi stok produk
        $product = Product::findOrFail($request->product_id);
        if ($product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Maaf, produk ini stok habis'], 400);
        }

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            $cart->qty += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'qty' => 1
            ]);
        }

        $cartTotal = Cart::where('user_id', Auth::id())->sum('qty');

        return response()->json([
            'success' => true, 
            'message' => 'Produk berhasil masuk ke keranjang!',
            'cartTotal' => $cartTotal
        ]);
    }

    // --- FUNGSI BARU: UPDATE QTY (PLUS/MINUS) ---
    public function updateQty(Request $request, $id)
    {
        $cart = Cart::with('product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($request->action === 'plus') {
            // Pastikan qty tidak melebihi stok yang ada
            if ($cart->qty < $cart->product->stock) {
                $cart->qty += 1;
            } else {
                return back()->with('error', 'Maaf, stok produk ini hanya tersisa ' . $cart->product->stock);
            }
        } elseif ($request->action === 'minus') {
            // Pastikan qty tidak kurang dari 1
            if ($cart->qty > 1) {
                $cart->qty -= 1;
            }
        }

        $cart->save();
        return back();
    }

    // --- FUNGSI BARU: HAPUS DARI KERANJANG ---
    public function hapus($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    // --- FUNGSI 2: HALAMAN KERANJANG (DIUPDATE UNTUK BUKU ALAMAT) ---
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $isMitra = Auth::user()->role === 'mitra';
        $paymentMethods = $this->paymentMethods();

        // Ambil buku alamat milik user ini
        $addresses = \App\Models\UserAddress::where('user_id', Auth::id())->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $hargaAktif = $isMitra ? $item->product->priceMitra : $item->product->priceRetail;
            $subtotal += $hargaAktif * $item->qty;
        }

        $totalAkhir = $subtotal;

        return view('keranjang', compact('cartItems', 'subtotal', 'totalAkhir', 'isMitra', 'addresses', 'paymentMethods'));
    }

    // --- FUNGSI 3: CHECKOUT ---
    public function checkout(Request $request)
    {
        // Validasi pilihan cart_ids
        $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id',
        ]);

        $cartIds = $request->input('cart_ids', []);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $cartIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['error' => 'Pilih setidaknya satu produk untuk checkout!']);
        }

        // Validasi Stok
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->qty) {
                return back()->withErrors(['error' => 'Maaf, stok ' . $item->product->name . ' tidak cukup. Sisa: ' . $item->product->stock]);
            }
        }

        $isMitra = Auth::user()->role === 'mitra';
        $subtotal = 0;

        // Buat Nota Utama
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_price' => 0,
            'shipping_address' => null, // <--- Akan diisi di tahap selanjutnya
            'payment_method' => null,
            'payment_status' => 'unpaid',
            'payment_code' => null,
            'payment_expires_at' => null,
        ]);

        // Catat Detail Barang (Stok belum dipotong)
        foreach ($cartItems as $item) {
            $hargaAktif = $isMitra ? $item->product->priceMitra : $item->product->priceRetail;
            $subtotal += $hargaAktif * $item->qty;

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $hargaAktif
            ]);
        }

        // Hitung Total
        $order->total_price = $subtotal;
        $order->save();

        // Kosongkan keranjang (hanya item yang dicheckout)
        Cart::where('user_id', Auth::id())->whereIn('id', $cartIds)->delete();

        return redirect('/pesanan/' . $order->id . '/lengkap');
    }

    public function lengkap($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        // Jika sudah lengkap, langsung ke pembayaran
        if ($order->shipping_address && $order->payment_method) {
            return redirect('/pembayaran/' . $order->id);
        }

        $addresses = \App\Models\UserAddress::where('user_id', Auth::id())->get();
        $paymentMethods = $this->paymentMethods();

        return view('lengkap_pesanan', compact('order', 'addresses', 'paymentMethods'));
    }

    public function simpanLengkap(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'address_option' => 'required',
            'new_label' => 'required_if:address_option,new',
            'new_address' => 'required_if:address_option,new',
            'payment_method' => 'required|in:' . implode(',', array_keys($this->paymentMethods())),
        ]);

        if ($request->address_option === 'new') {
            $fullAddress = $request->new_address;
            if ($request->filled('detail_address')) {
                $fullAddress .= ' (' . $request->detail_address . ')';
            }

            $alamatBaru = \App\Models\UserAddress::create([
                'user_id' => Auth::id(),
                'label' => $request->new_label,
                'address' => $fullAddress
            ]);
            $shipping_address = $alamatBaru->address;
        } else {
            $alamatLama = \App\Models\UserAddress::findOrFail($request->address_option);
            if ($alamatLama->user_id !== Auth::id()) abort(403);
            $shipping_address = $alamatLama->address;
        }

        $order->update([
            'shipping_address' => $shipping_address,
            'payment_method' => $request->payment_method,
            'payment_code' => $this->generatePaymentCode($request->payment_method),
            'payment_expires_at' => now()->addHours(24),
        ]);

        return redirect('/pembayaran/' . $order->id)->with('success', 'Checkout berhasil. Silakan selesaikan pembayaran Anda.');
    }

    public function pembayaran($id)
    {
        $order = Order::with('details.product', 'user')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if (!$order->shipping_address || !$order->payment_method) {
            return redirect('/pesanan/' . $order->id . '/lengkap');
        }

        $paymentMethods = $this->paymentMethods();
        $selectedMethod = $paymentMethods[$order->payment_method] ?? $paymentMethods['bank_bca'];

        return view('pembayaran', compact('order', 'paymentMethods', 'selectedMethod'));
    }

    public function ubahMetodePembayaran(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:' . implode(',', array_keys($this->paymentMethods())),
        ]);

        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Metode pembayaran tidak dapat diubah karena pembayaran sudah diproses.');
        }

        $order->update([
            'payment_method' => $request->payment_method,
            'payment_code' => $this->generatePaymentCode($request->payment_method),
            'payment_expires_at' => now()->addHours(24),
        ]);

        return back()->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function prosesPembayaran($id)
    {
        $order = Order::with('details.product')->where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect('/pesanan-saya')->with('success', 'Pembayaran pesanan ini sudah diproses.');
        }

        // Cek stok kembali
        foreach ($order->details as $detail) {
            $product = $detail->product;
            if ($product->stock < $detail->qty) {
                return back()->with('error', 'Maaf, pembayaran gagal diproses karena stok ' . $product->name . ' tidak mencukupi.');
            }
        }

        // Potong stok
        foreach ($order->details as $detail) {
            $product = $detail->product;
            $product->stock -= $detail->qty;
            $product->save();
        }

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'status' => 'dikemas',
        ]);

        return redirect('/pesanan-saya')->with('success', 'Pembayaran berhasil! Pesanan Anda sekarang sedang dikemas.');
    }

    private function paymentMethods(): array
    {
        return [
            'bank_bca' => [
                'label' => 'BCA Virtual Account',
                'short' => 'BCA VA',
                'icon' => 'landmark',
                'fee' => 0,
                'badge' => 'Rekomendasi',
            ],
            'bank_mandiri' => [
                'label' => 'Mandiri Virtual Account',
                'short' => 'Mandiri VA',
                'icon' => 'building-2',
                'fee' => 0,
                'badge' => 'Bank Transfer',
            ],
            'qris' => [
                'label' => 'QRIS',
                'short' => 'QRIS',
                'icon' => 'qr-code',
                'fee' => 0,
                'badge' => 'Scan QR',
            ],
            'ewallet' => [
                'label' => 'E-Wallet',
                'short' => 'E-Wallet',
                'icon' => 'wallet',
                'fee' => 0,
                'badge' => 'Instan',
            ],
        ];
    }

    private function generatePaymentCode(string $method): string
    {
        $prefix = match ($method) {
            'bank_bca' => '39010',
            'bank_mandiri' => '88708',
            'qris' => 'QRIS',
            'ewallet' => 'EWALLET',
            default => 'PAY',
        };

        return $prefix . now()->format('ymd') . Auth::id() . random_int(1000, 9999);
    }
}
