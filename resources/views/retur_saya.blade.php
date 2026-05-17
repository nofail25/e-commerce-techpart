@extends('layouts.app')
@section('title', 'Retur Saya - TechPart')

@section('content')
@php
    $statusStyles = [
        'diajukan' => 'bg-amber-50 text-amber-700 border-amber-200',
        'disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
        'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
        'barang_dikirim' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'barang_diterima' => 'bg-purple-50 text-purple-700 border-purple-200',
        'dana_dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
    $statusLabels = [
        'diajukan' => 'Pengajuan dikirim', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak',
        'barang_dikirim' => 'Barang dikirim', 'barang_diterima' => 'Barang diterima',
        'dana_dikembalikan' => 'Dana dikembalikan', 'selesai' => 'Selesai',
    ];
@endphp

<div class="page-shell py-8 sm:py-10">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <a href="{{ url('/pesanan-saya') }}" class="btn-outline mb-4 px-4 py-2.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Riwayat</a>
            <span class="eyebrow">Retur</span>
            <h1 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Retur saya</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Pantau pengembalian barang dari pengajuan sampai selesai.</p>
        </div>
    </div>

    <div class="space-y-5">
        @forelse($returns as $return)
            <article class="surface-card overflow-hidden">
                <header class="border-b border-slate-200/70 p-5 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="font-mono text-sm font-black text-primary">{{ $return->return_code }}</div>
                            <div class="mt-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Order #{{ str_pad($return->order_id, 5, '0', STR_PAD_LEFT) }} • {{ $return->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-full border px-3 py-1.5 text-xs font-black {{ $statusStyles[$return->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ $statusLabels[$return->status] ?? ucfirst($return->status) }}
                        </span>
                    </div>
                </header>

                <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-[1fr_260px]">
                    <div>
                        <h2 class="text-lg font-black text-ink">{{ $return->product->name ?? 'Produk' }}</h2>
                        <p class="mt-1 text-sm font-semibold text-slate-500">Qty retur: {{ $return->qty }} • {{ $return->reason }}</p>
                        <p class="mt-4 text-sm font-semibold leading-7 text-slate-600">{{ $return->description }}</p>
                        @if($return->admin_note)
                            <div class="mt-4 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm font-semibold leading-6 text-blue-800">
                                <span class="font-black">Catatan admin:</span> {{ $return->admin_note }}
                            </div>
                        @endif
                    </div>
                    <aside class="soft-card p-4">
                        <h3 class="font-black text-ink">Langkah berikutnya</h3>
                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-500">
                            @if($return->status === 'diajukan')
                                Menunggu admin meninjau pengajuan Anda.
                            @elseif($return->status === 'disetujui')
                                Kirim barang ke alamat retur sesuai catatan admin.
                            @elseif($return->status === 'ditolak')
                                Pengajuan ditolak. Lihat catatan admin untuk detailnya.
                            @elseif($return->status === 'selesai')
                                Proses pengembalian sudah selesai.
                            @else
                                Pantau pembaruan status dari admin.
                            @endif
                        </p>
                    </aside>
                </div>
            </article>
        @empty
            <div class="surface-card p-12 text-center">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-slate-300"><i data-lucide="rotate-ccw" class="h-9 w-9"></i></div>
                <h2 class="mt-4 text-xl font-black text-ink">Belum ada pengajuan retur</h2>
                <p class="mt-2 text-sm text-slate-500">Pengajuan pengembalian akan tampil di sini.</p>
                <a href="{{ url('/pesanan-saya') }}" class="btn-primary mt-6 px-5 py-3 text-sm">Buka Pesanan Saya</a>
            </div>
        @endforelse
    </div>
</div>
@endsection