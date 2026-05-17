<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnRequestController extends Controller
{
    public function info()
    {
        return view('pengembalian_info');
    }

    public function index()
    {
        $returns = ReturnRequest::with(['order', 'product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('retur_saya', compact('returns'));
    }

    public function create(Order $order, Request $request)
    {
        $order->load(['details.product', 'details.returnRequests']);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'selesai') {
            return redirect('/pesanan-saya')->with('error', 'Pengembalian hanya bisa diajukan untuk pesanan yang sudah selesai.');
        }

        $selectedDetail = null;
        if ($request->filled('detail')) {
            $selectedDetail = $order->details->firstWhere('id', (int) $request->detail);
        }

        return view('retur_form', compact('order', 'selectedDetail'));
    }

    public function store(Order $order, Request $request)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'selesai') {
            return redirect('/pesanan-saya')->with('error', 'Pengembalian hanya bisa diajukan untuk pesanan yang sudah selesai.');
        }

        $data = $request->validate([
            'order_detail_id' => 'required|exists:order_details,id',
            'qty' => 'required|integer|min:1',
            'reason' => 'required|string|max:100',
            'description' => 'required|string|min:10|max:2000',
            'evidence_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $detail = OrderDetail::with('product')
            ->where('order_id', $order->id)
            ->where('id', $data['order_detail_id'])
            ->firstOrFail();

        if ($data['qty'] > $detail->qty) {
            return back()->withErrors(['qty' => 'Jumlah retur tidak boleh melebihi jumlah produk yang dibeli.'])->withInput();
        }

        $existingReturn = ReturnRequest::where('order_detail_id', $detail->id)
            ->whereNotIn('status', ['ditolak'])
            ->exists();

        if ($existingReturn) {
            return back()->withErrors(['order_detail_id' => 'Produk ini sudah memiliki pengajuan pengembalian yang sedang berjalan.'])->withInput();
        }

        $evidencePath = null;
        if ($request->hasFile('evidence_image')) {
            $evidencePath = $request->file('evidence_image')->store('return-evidence', 'public');
        }

        ReturnRequest::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'order_detail_id' => $detail->id,
            'product_id' => $detail->product_id,
            'return_code' => $this->generateReturnCode(),
            'qty' => $data['qty'],
            'reason' => $data['reason'],
            'description' => $data['description'],
            'evidence_image' => $evidencePath,
            'status' => 'diajukan',
        ]);

        return redirect('/retur-saya')->with('success', 'Pengajuan pengembalian berhasil dikirim. Tim kami akan meninjau permintaan Anda.');
    }

    public function adminIndex(Request $request)
    {
        $query = ReturnRequest::with(['user', 'order', 'product'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $returns = $query->paginate(15);

        return view('admin.retur_index', compact('returns'));
    }

    public function adminUpdate(Request $request, ReturnRequest $returnRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:diajukan,disetujui,ditolak,barang_dikirim,barang_diterima,dana_dikembalikan,selesai',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $returnRequest->update($data);

        return back()->with('success', 'Status pengembalian berhasil diperbarui.');
    }

    private function generateReturnCode(): string
    {
        do {
            $code = 'RTR-' . now()->format('ymd') . '-' . random_int(1000, 9999);
        } while (ReturnRequest::where('return_code', $code)->exists());

        return $code;
    }
}
