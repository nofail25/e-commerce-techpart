<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\MitraApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ==========================================
    // 1. AREA DASHBOARD UTAMA
    // ==========================================
    public function index()
    {
        // Ambil 5 pesanan terbaru saja untuk tabel ringkasan di dashboard
        $orders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // Hitung Total Pendapatan (Hanya dari pesanan yang sudah 'selesai')
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_price');

        // Hitung Total Pesanan Bulan Ini
        $pesananBulanIni = Order::whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))
                                ->count();

        // Hitung Pesanan yang Menunggu Pembayaran (Pending)
        $pesananPending = Order::where('status', 'pending')->count();

        // Cari Produk Terlaris (Best Seller)
        $bestSeller = \App\Models\OrderDetail::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(qty) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->with('product') 
            ->first();

        return view('admin.dashboard', compact('orders', 'totalPendapatan', 'pesananBulanIni', 'pesananPending', 'bestSeller'));
    }

    // ==========================================
    // 2. AREA MANAJEMEN PESANAN (PENJUALAN)
    // ==========================================
    
    // Menampilkan halaman semua pesanan
    public function kelolaPesanan(Request $request)
    {
        // Query dasar
        $query = Order::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan status dari URL (contoh: ?status=pending)
        if ($request->has('status') && $request->status != 'semua') {
            if ($request->status === 'dikemas') {
                $query->whereIn('status', ['dikemas', 'diproses']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Gunakan pagination (misal 15 per halaman) agar lebih rapi
        $orders = $query->paginate(15);

        return view('admin.penjualan_index', compact('orders'));
    }

    // Fungsi untuk mengubah status pesanan (Pending -> Dikemas -> Dikirim -> Selesai)
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,dikemas,dikirim,selesai,dibatalkan',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order = Order::findOrFail($id);
        
        $order->status = $request->status;
        
        if ($request->has('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
        }
        
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan & resi berhasil diperbarui!');
    }

    // ==========================================
    // 3. AREA MANAJEMEN MITRA
    // ==========================================
    public function pengajuanMitra()
    {
        // Ambil dari tabel mitra_applications yang baru
        $pengajuan = MitraApplication::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pengajuan_mitra', compact('pengajuan'));
    }

    public function setujuiMitra(Request $request, $id)
    {
        $application = MitraApplication::findOrFail($id);

        if ($request->aksi === 'setujui') {
            // Update status aplikasi
            $application->status = 'disetujui';
            $application->approved_at = now();
            $application->catatan_admin = $request->catatan ?? null;
            $application->save();

            // Update user role
            $user = $application->user;
            if ($user) {
                $user->role = 'mitra';
                $user->upgradeRequested = false;
                $user->save();
            }

            return back()->with('success', 'Akun ' . $application->name . ' resmi menjadi Mitra Teknisi!');
        } else {
            // Tolak pengajuan
            $application->status = 'ditolak';
            $application->catatan_admin = $request->catatan ?? null;
            $application->save();

            // Update user upgradeRequested flag
            $user = $application->user;
            if ($user) {
                $user->upgradeRequested = false;
                $user->save();
            }

            return back()->with('success', 'Pengajuan Mitra untuk ' . $application->name . ' telah ditolak.');
        }
    }

    // ==========================================
    // 4. AREA MANAJEMEN PRODUK
    // ==========================================
    public function kelolaProduk()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.produk_index', compact('products'));
    }

    public function tambahProduk()
    {
        return view('admin.produk_form');
    }

    public function simpanProduk(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable|string', 
            'brand' => 'required',
            'category' => 'required',
            'weight' => 'required|numeric',
            'priceRetail' => 'required|numeric',
            'priceMitra' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect('/admin/produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduk($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.produk_form', compact('product'));
    }

    public function updateProduk(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'description' => 'nullable|string',
            'brand' => 'required',
            'category' => 'required',
            'weight' => 'required|numeric',
            'priceRetail' => 'required|numeric',
            'priceMitra' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect('/admin/produk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function hapusProduk($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect('/admin/produk')->with('success', 'Produk berhasil dihapus!');
    }

    // ==========================================
    // 5. AREA LAPORAN
    // ==========================================
    public function laporanPenjualan(Request $request)
    {
        $query = Order::with(['user', 'details.product']);
        
        // Filter tanggal
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Filter status
        $status = $request->input('status');
        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Summary stats
        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->where('status', 'selesai')->sum('total_price');
        $pendingOrders = (clone $query)->where('status', 'pending')->count();
        
        return view('admin.laporan_penjualan', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'startDate', 'endDate', 'status'));
    }

    public function unduhLaporanPDF(Request $request)
    {
        $query = Order::with(['user', 'details.product']);
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $orders = $query->where('status', 'selesai')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $totalPendapatan = $orders->sum('total_price');

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('orders', 'totalPendapatan', 'startDate', 'endDate'));
        return $pdf->download('Laporan_Penjualan_TechPart.pdf');
    }
}
