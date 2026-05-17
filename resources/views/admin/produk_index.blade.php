@extends('layouts.admin')
@section('title', 'Kelola Produk - TechPart')
@section('page_title', 'Kelola Produk')

@section('content')
<div class="space-y-6 sm:space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Katalog Produk</h1>
            <p class="text-slate-600">Kelola daftar produk, harga, dan ketersediaan stok dengan mudah</p>
        </div>
        
        <!-- Tombol Tambah Produk -->
        <a href="{{ url('/admin/produk/tambah') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-6 py-3 rounded-xl transition-all duration-200 font-semibold text-sm shadow-lg hover:shadow-xl w-full sm:w-auto">
            <i data-lucide="plus" class="w-5 h-5"></i> Tambah Produk
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-start gap-3 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
            <input type="text" placeholder="Cari produk, merek, atau kategori..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
        </div>
        <button class="flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-sm hover:bg-slate-50 transition-all">
            <i data-lucide="filter" class="w-5 h-5"></i> Filter
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <p class="text-sm font-semibold text-slate-700">Total Produk: <span class="text-primary-600 font-bold">{{ count($products ?? []) }}</span></p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20 text-center">Foto</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Produk</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Kategori</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Stok</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Harga</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products ?? [] as $p)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <!-- Foto -->
                        <td class="p-6 align-middle text-center">
                            <div class="w-12 h-12 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 mx-auto flex items-center justify-center shrink-0 shadow-sm">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="image" class="w-5 h-5 text-slate-400"></i>
                                @endif
                            </div>
                        </td>

                        <!-- Info Produk -->
                        <td class="p-6 align-middle">
                            <div class="font-semibold text-slate-800">{{ $p->name }}</div>
                            <div class="text-xs text-slate-500 mt-2 flex items-center gap-2">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded font-medium">{{ $p->brand }}</span>
                            </div>
                        </td>

                        <!-- Kategori -->
                        <td class="p-6 align-middle text-center">
                            <span class="text-sm text-slate-600 font-medium">{{ $p->category }}</span>
                        </td>

                        <!-- Stok -->
                        <td class="p-6 align-middle text-right">
                            @if($p->stock < 5)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                    {{ $p->stock }} unit
                                </span>
                            @elseif($p->stock < 10)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                    {{ $p->stock }} unit
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                    {{ $p->stock }} unit
                                </span>
                            @endif
                        </td>

                        <!-- Harga -->
                        <td class="p-6 align-middle text-right">
                            <div class="font-bold text-slate-800">Rp {{ number_format($p->priceRetail, 0, ',', '.') }}</div>
                        </td>

                        <!-- Aksi -->
                        <td class="p-6 align-middle text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ url('/admin/produk/edit/'.$p->id) }}" class="p-2 text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors border border-primary-100" title="Edit Produk">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ url('/admin/produk/hapus/'.$p->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus {{ $p->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors border border-rose-100" title="Hapus Produk">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i data-lucide="package-open" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <div class="text-center">
                                    <h4 class="font-semibold text-slate-700">Belum Ada Produk</h4>
                                    <p class="text-sm text-slate-500 mt-1 mb-4">Katalog Anda masih kosong. Mulai dengan menambahkan produk pertama.</p>
                                    <a href="{{ url('/admin/produk/tambah') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg transition-all font-medium text-sm shadow-md hover:shadow-lg">
                                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Produk
                                    </a>
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
@endsection