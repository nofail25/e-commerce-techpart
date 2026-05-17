@extends('layouts.admin')
@section('title', 'Dashboard Admin - TechPart')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">
    
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-1">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!</h1>
            <p class="text-slate-600">Kelola bisnis Anda dari sini dengan mudah dan cepat.</p>
        </div>
        <div class="text-sm text-slate-500 font-medium">{{ now()->format('d F Y') }}</div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-start gap-3 shadow-sm animate-pulse">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-6 border border-emerald-200/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-200/50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-white px-2.5 py-1 rounded-lg">Hari Ini</span>
            </div>
            <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-1">Pendapatan Bersih</p>
            <h3 class="text-2xl font-bold text-emerald-900">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
            <p class="text-xs text-emerald-600 mt-3 font-medium">↑ 12% dari minggu lalu</p>
        </div>

        <!-- Orders This Month -->
        <div class="bg-gradient-to-br from-primary-50 to-primary-100/50 rounded-2xl p-6 border border-primary-200/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary-200/50 text-primary-600 flex items-center justify-center shrink-0">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-primary-600 bg-white px-2.5 py-1 rounded-lg">Bulan Ini</span>
            </div>
            <p class="text-xs font-semibold text-primary-700 uppercase tracking-wider mb-1">Total Pesanan</p>
            <h3 class="text-2xl font-bold text-primary-900">{{ $pesananBulanIni ?? 0 }}</h3>
            <p class="text-xs text-primary-600 mt-3 font-medium">↑ 8 pesanan baru</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl p-6 border border-amber-200/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-200/50 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-white px-2.5 py-1 rounded-lg">Mendesak</span>
            </div>
            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider mb-1">Menunggu Proses</p>
            <h3 class="text-2xl font-bold text-amber-900">{{ $pesananPending ?? 0 }}</h3>
            <p class="text-xs text-amber-600 mt-3 font-medium">Segera proses pesanan ini</p>
        </div>

        <!-- Best Seller -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl p-6 border border-purple-200/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-200/50 text-purple-600 flex items-center justify-center shrink-0">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-purple-600 bg-white px-2.5 py-1 rounded-lg">Top</span>
            </div>
            <p class="text-xs font-semibold text-purple-700 uppercase tracking-wider mb-1">Produk Terlaris</p>
            @if(isset($bestSeller) && $bestSeller->product)
                <h3 class="text-lg font-bold text-purple-900 truncate">{{ $bestSeller->product->name }}</h3>
                <p class="text-xs text-purple-600 mt-3 font-medium">{{ $bestSeller->total_sold }} terjual</p>
            @else
                <h3 class="text-lg font-bold text-purple-900">Belum Ada Data</h3>
                <p class="text-xs text-purple-600 mt-3 font-medium">-</p>
            @endif
        </div>
    </div>

    <!-- Recent Transactions Section -->
    <div class="pt-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
                <p class="text-sm text-slate-600 mt-1">Pesanan yang masuk hari ini</p>
            </div>
            <a href="{{ url('/admin/pesanan') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-2 bg-white border border-primary-200 px-4 py-2 rounded-lg hover:bg-primary-50">
                Lihat Semua <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">ID Pesanan</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Belanja</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders ?? [] as $order)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-6 align-middle">
                                <a href="{{ url('/admin/pesanan') }}" class="font-bold text-primary-600 hover:text-primary-700 transition-colors">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</a>
                                <div class="text-xs text-slate-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="p-6 align-middle">
                                <div class="font-semibold text-slate-800">{{ $order->user->name }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $order->user->email }}</div>
                            </td>
                            <td class="p-6 align-middle">
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
                            <td class="p-6 align-middle text-right">
                                <a href="{{ url('/admin/pesanan') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-all">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                        <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-700">Belum Ada Transaksi</h4>
                                        <p class="text-sm text-slate-500 mt-1">Belum ada pesanan masuk hari ini.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
