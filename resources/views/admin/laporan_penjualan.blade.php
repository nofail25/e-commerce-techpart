@extends('layouts.admin')
@section('title', 'Laporan Penjualan - TechPart')

@section('content')
<div class="space-y-6 sm:space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Laporan Penjualan</h1>
            <p class="text-slate-600">Analisis performa penjualan dan transaksi bisnis Anda</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('/admin/dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-start gap-3 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <form action="{{ url('/admin/laporan') }}" method="GET" class="grid sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                    <option value="semua" {{ ($status ?? '') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dikemas" {{ ($status ?? '') === 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                    <option value="dikirim" {{ ($status ?? '') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ ($status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ ($status ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition">
                    <i data-lucide="filter" class="w-4 h-4"></i> Filter
                </button>
                <a href="{{ url('/admin/laporan/pdf') }}?{{ http_build_query(['start_date' => $startDate ?? '', 'end_date' => $endDate ?? '', 'status' => $status ?? '']) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition">
                    <i data-lucide="download" class="w-4 h-4"></i> PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-primary-50 to-primary-100/50 rounded-2xl p-6 border border-primary-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-200/50 text-primary-600 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-bold text-primary-700 uppercase tracking-wider">Total Pesanan</span>
            </div>
            <h3 class="text-2xl font-bold text-primary-900">{{ $totalOrders ?? 0 }}</h3>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-6 border border-emerald-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-200/50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Pendapatan</span>
            </div>
            <h3 class="text-2xl font-bold text-emerald-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl p-6 border border-amber-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-200/50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Menunggu Bayar</span>
            </div>
            <h3 class="text-2xl font-bold text-amber-900">{{ $pendingOrders ?? 0 }}</h3>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">ID Pesanan</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Produk</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Jumlah</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Total</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders ?? [] as $order)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-6 align-middle">
                            <span class="font-bold text-primary-600">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="p-6 align-middle">
                            <span class="text-sm text-slate-700">{{ $order->created_at->format('d M Y') }}</span>
                            <span class="text-xs text-slate-500 block">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td class="p-6 align-middle">
                            <div class="font-semibold text-slate-800">{{ $order->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="p-6 align-middle">
                            <div class="text-sm text-slate-700 max-w-xs truncate">
                                @foreach($order->details as $detail)
                                    {{ $detail->product->name ?? 'Produk' }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="p-6 align-middle text-center">
                            <span class="font-semibold text-slate-700">{{ $order->details->sum('qty') }} item</span>
                        </td>
                        <td class="p-6 align-middle text-right">
                            <span class="font-bold text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="p-6 align-middle text-center">
                            @if($order->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span> Pending
                                </span>
                            @elseif($order->status == 'dikemas' || $order->status == 'diproses')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span> Dikemas
                                </span>
                            @elseif($order->status == 'dikirim')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></span> Dikirim
                                </span>
                            @elseif($order->status == 'selesai')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Selesai
                                </span>
                            @elseif($order->status == 'dibatalkan')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></span> Dibatalkan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-700">Belum Ada Data</h4>
                                    <p class="text-sm text-slate-500 mt-1">Belum ada transaksi pada periode yang dipilih.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($orders) && $orders->hasPages())
            <div class="border-t border-slate-200 p-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection