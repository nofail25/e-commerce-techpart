<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController; // Tambahkan ini agar rute publik tidak error
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\MitraController;

// ==========================================
// RUTE PUBLIK (Bisa diakses tanpa login)
// ==========================================
Route::get('/', [ProductController::class, 'index']);
Route::get('/katalog', [ProductController::class, 'index']);
Route::get('/api/get-series', [ProductController::class, 'getSeries']);
Route::get('/produk/{id}', [ProductController::class, 'detailProduk']);
Route::get('/pengembalian-barang', [ReturnRequestController::class, 'info']);
Route::get('/jadi-mitra', [MitraController::class, 'create']);
Route::post('/jadi-mitra', [MitraController::class, 'store']);

// Halaman Auth
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registerPost']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// RUTE TERPROTEKSI (Hanya untuk User Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // --- AREA PELANGGAN (USER REGULER) ---
    // Fitur Keranjang
    Route::post('/keranjang/tambah', [CartController::class, 'tambah']);
    Route::get('/keranjang', [CartController::class, 'index']);
    Route::post('/keranjang/update/{id}', [CartController::class, 'updateQty']);
    Route::post('/keranjang/hapus/{id}', [CartController::class, 'hapus']);
    
    // Fitur Checkout & Pesanan Saya
    Route::post('/checkout', [CartController::class, 'checkout']);
    Route::get('/pembayaran/{id}', [CartController::class, 'pembayaran']);
    Route::post('/pembayaran/{id}/metode', [CartController::class, 'ubahMetodePembayaran']);
    Route::post('/pembayaran/{id}/bayar', [CartController::class, 'prosesPembayaran']);
    Route::get('/pesanan-saya', [OrderController::class, 'riwayat']);
    Route::post('/pesanan/ulasan', [OrderController::class, 'simpanUlasan']);
    Route::get('/retur-saya', [ReturnRequestController::class, 'index']);
    Route::get('/pesanan/{order}/retur', [ReturnRequestController::class, 'create']);
    Route::post('/pesanan/{order}/retur', [ReturnRequestController::class, 'store']);
    
    // Status Pengajuan Mitra
    Route::get('/status-pengajuan-mitra', [MitraController::class, 'status']);

    // Cetak Invoice Pesanan Sendiri
    Route::get('/pesanan/{id}/invoice', function ($id) {
        $order = \App\Models\Order::with('details.product', 'user')->findOrFail($id);
        
        // Keamanan: Pastikan yang mencetak adalah pemilik pesanannya atau admin
        if ($order->user_id !== \Illuminate\Support\Facades\Auth::id() && \Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }
        return view('invoice', compact('order'));
    });

    // --- AREA ADMINISTRATOR ---
    // Dashboard & Laporan
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    Route::get('/admin/laporan/pdf', [AdminController::class, 'unduhLaporanPDF']);

    // Kelola Pesanan (PENAMBAHAN RUTE BARU)
    Route::get('/admin/pesanan', [AdminController::class, 'kelolaPesanan']);
    Route::post('/admin/pesanan/{id}/status', [AdminController::class, 'updateStatusPesanan']); 
    Route::get('/admin/retur', [ReturnRequestController::class, 'adminIndex']);
    Route::post('/admin/retur/{returnRequest}/status', [ReturnRequestController::class, 'adminUpdate']);
    
    // Kelola Produk
    Route::get('/admin/produk', [AdminController::class, 'kelolaProduk']);
    Route::get('/admin/produk/tambah', [AdminController::class, 'tambahProduk']);
    Route::post('/admin/produk/simpan', [AdminController::class, 'simpanProduk']);
    Route::get('/admin/produk/edit/{id}', [AdminController::class, 'editProduk']);
    Route::post('/admin/produk/update/{id}', [AdminController::class, 'updateProduk']);
    Route::delete('/admin/produk/hapus/{id}', [AdminController::class, 'hapusProduk']); // PERBAIKAN: Menggunakan DELETE

    // Rute Persetujuan Mitra
    Route::get('/admin/pengajuan-mitra', [AdminController::class, 'pengajuanMitra']);
    Route::post('/admin/pengajuan-mitra/{id}', [AdminController::class, 'setujuiMitra']);
    
    // Rute Laporan Penjualan
    Route::get('/admin/laporan', [AdminController::class, 'laporanPenjualan']);
    Route::get('/admin/laporan/pdf', [AdminController::class, 'unduhLaporanPDF']);
});
