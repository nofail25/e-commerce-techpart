@extends('layouts.admin')
@section('title', 'Kelola Retur - TechPart')
@section('page_title', 'Manajemen Retur')

@section('content')
@php
    $statusLabels = [
        'diajukan' => 'Diajukan',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'barang_dikirim' => 'Barang Dikirim',
        'barang_diterima' => 'Barang Diterima',
        'dana_dikembalikan' => 'Dana Dikembalikan',
        'selesai' => 'Selesai',
    ];
@endphp

<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Manajemen Pengembalian</h1>
            <p class="text-slate-600">Tinjau dan kelola semua pengajuan retur dari pelanggan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-start gap-3 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Status Filter Tabs -->
        <div class="border-b border-slate-200 bg-slate-50/50 px-4 sm:px-6 flex overflow-x-auto hide-scrollbar">
            <a href="?status=semua" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ !request('status') || request('status') == 'semua' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">Semua</a>
            @foreach($statusLabels as $key => $label)
                <a href="?status={{ $key }}" class="py-4 px-4 text-sm font-semibold whitespace-nowrap {{ request('status') == $key ? 'text-primary-600 border-b-2 border-primary-600' : 'text-slate-600 hover:text-slate-800 transition-colors' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1100px]">
                <thead>
                    <tr class="bg-white border-b border-slate-200">
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Retur</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Produk</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Alasan</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($returns as $return)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 align-middle">
                                <div class="font-bold text-primary-600 whitespace-nowrap">{{ $return->return_code }}</div>
                                <div class="text-xs text-slate-500 mt-1.5">Order #{{ str_pad($return->order_id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ $return->created_at->format('d M Y, H:i') }}</div>
                                <div class="mt-2.5">
                                    <span class="inline-flex rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ $statusLabels[$return->status] ?? ucfirst($return->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="font-semibold text-slate-800">{{ $return->user->name }}</div>
                                <div class="text-xs text-slate-500 mt-1.5">{{ $return->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="font-semibold text-slate-800 max-w-xs">{{ $return->product->name ?? 'Produk' }}</div>
                                <div class="text-xs text-slate-500 mt-1.5">Qty: {{ $return->qty }}</div>
                                @if($return->evidence_image)
                                    <a href="{{ asset('storage/'.$return->evidence_image) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-700 mt-2 transition-colors">
                                        <i data-lucide="image" class="w-3.5 h-3.5"></i> Lihat bukti
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="font-semibold text-slate-700">{{ $return->reason }}</div>
                                <p class="text-xs text-slate-500 mt-1.5 max-w-xs leading-relaxed">{{ $return->description }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <form action="{{ url('/admin/retur/'.$return->id.'/status') }}" method="POST" class="flex flex-col items-end gap-2">
                                    @csrf
                                    <select name="status" class="w-full max-w-xs text-sm font-medium border border-slate-200 bg-white text-slate-700 rounded-lg p-2.5 outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all cursor-pointer">
                                        @foreach($statusLabels as $key => $label)
                                            <option value="{{ $key }}" {{ $return->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="admin_note" rows="2" placeholder="Catatan untuk pelanggan..." class="w-full max-w-xs text-sm border border-slate-200 bg-white text-slate-800 rounded-lg p-2.5 outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all resize-none placeholder:text-slate-400 placeholder:font-normal">{{ $return->admin_note }}</textarea>
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-all shadow-sm hover:shadow-md">
                                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="undo-2" class="w-10 h-10 text-slate-400"></i>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-lg font-semibold text-slate-700">Belum Ada Pengajuan Retur</h4>
                                        <p class="text-sm text-slate-500 mt-2">Pengajuan pengembalian akan muncul di sini.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($returns, 'links'))
            <div class="p-4 sm:px-6 border-t border-slate-200 bg-slate-50/50">
                {{ $returns->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
