@extends('layouts.admin')
@section('title', 'Kelola Pesanan - TechPart')
@section('page_title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6 sm:space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Manajemen Pesanan</h1>
            <p class="text-slate-600">Kelola semua pesanan pelanggan dan perbarui status pengiriman</p>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
            <input type="text" placeholder="Cari ID pesanan, nama pelanggan, atau email..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-start gap-3 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Main Content Box -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Status Filter Tabs -->
        <div class="border-b border-slate-200 bg-slate-50/50 px-4 sm:px-6 flex overflow-x-auto hide-scrollbar">
            <a href="?status=semua" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ !request('status') || request('status') == 'semua' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">
                Semua
            </a>
            <a href="?status=pending" class="py-4 px-4 text-sm font-semibold whitespace-nowrap flex items-center gap-2 {{ request('status') == 'pending' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">
                Pending
                <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded-full text-[10px] font-bold">Baru</span>
            </a>
            <a href="?status=dikemas" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ request('status') == 'dikemas' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">
                Dikemas
            </a>
            <a href="?status=dikirim" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ request('status') == 'dikirim' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">
                Dikirim
            </a>
            <a href="?status=selesai" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ request('status') == 'selesai' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">
                Selesai
            </a>
            <a href="?status=dibatalkan" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ request('status') == 'dibatalkan' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">
                Dibatalkan
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-white border-b border-slate-200">
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pesanan</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Total</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders ?? [] as $order)
                    <tr class="hover:bg-slate-50 transition-colors">
                        
                        <td class="px-6 py-4 align-middle">
                            <div class="font-bold text-primary-600">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-slate-500 mt-1.5 flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 align-middle">
                            <div class="font-semibold text-slate-800">{{ $order->user->name }}</div>
                            <div class="text-xs text-slate-500 mt-1.5 flex items-center gap-1">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i> {{ $order->user->email }}
                            </div>
                            @if(isset($order->user->phone))
                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i> {{ $order->user->phone }}
                            </div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 align-middle text-right">
                            <div class="font-bold text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </td>
                        
                        <td class="px-6 py-4 align-middle text-center">
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
                        
                        <td class="px-6 py-4 align-middle">
                            <form action="{{ url('/admin/pesanan/'.$order->id.'/status') }}" method="POST" class="flex flex-col items-end gap-2">
                                @csrf
                                <div class="flex items-center gap-2 w-full max-w-xs justify-end">
                                    <select name="status" class="flex-1 text-sm font-medium border border-slate-200 bg-white text-slate-700 rounded-lg p-2.5 outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all cursor-pointer">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dikemas" {{ ($order->status == 'dikemas' || $order->status == 'diproses') ? 'selected' : '' }}>Dikemas</option>
                                        <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="bg-white border border-slate-200 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 text-slate-700 p-2.5 rounded-lg text-sm font-semibold transition-all shrink-0" title="Simpan">
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Nomor Resi Pengiriman" class="text-sm font-medium border border-slate-200 bg-white text-slate-800 rounded-lg p-2.5 w-full max-w-xs outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all placeholder:text-slate-400 placeholder:font-normal">
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="shopping-bag" class="w-10 h-10 text-slate-400"></i>
                                </div>
                                <div class="text-center max-w-sm">
                                    <h4 class="text-lg font-semibold text-slate-700">Tidak Ada Pesanan</h4>
                                    <p class="text-sm text-slate-500 mt-2">Belum ada pesanan yang sesuai dengan filter saat ini.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($orders) && method_exists($orders, 'links'))
        <div class="p-4 sm:px-6 border-t border-slate-200 bg-slate-50/50">
            {{ $orders->links('pagination::tailwind') }}
        </div>
        @endif

    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
