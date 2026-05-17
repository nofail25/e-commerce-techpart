@extends('layouts.app')
@section('title', 'Pesanan Berhasil - TechPart')

@section('content')
<div class="page-shell py-14 sm:py-20">
    <section class="surface-card mx-auto max-w-3xl overflow-hidden text-center">
        <div class="mesh-card p-8 text-white sm:p-10">
            <div class="relative z-10 mx-auto grid h-20 w-20 place-items-center rounded-[1.75rem] bg-white/10 ring-1 ring-white/20">
                <i data-lucide="check" class="h-10 w-10 text-mint"></i>
            </div>
            <h1 class="relative z-10 mt-6 text-4xl font-black tracking-[-0.06em] sm:text-5xl">Pesanan berhasil dibuat</h1>
            <p class="relative z-10 mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-300">Nomor pesanan Anda sudah tercatat. Lanjutkan ke pembayaran atau cek status dari menu Pesanan Saya.</p>
        </div>
        <div class="p-6 sm:p-8">
            <div class="mx-auto max-w-md rounded-[1.5rem] border border-primary/20 bg-primary-50 p-5">
                <div class="text-xs font-black uppercase tracking-[0.18em] text-primary">Nomor Pesanan</div>
                <div class="mt-2 text-3xl font-black tracking-[-0.05em] text-ink">#ORD-{{ str_pad($orderId, 5, '0', STR_PAD_LEFT) }}</div>
                <p class="mt-3 text-sm font-semibold leading-6 text-slate-500">Status awal pesanan adalah pending sampai pembayaran diselesaikan.</p>
            </div>
            <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ url('/pesanan-saya') }}" class="btn-primary px-6 py-3.5 text-sm"><i data-lucide="clipboard-list" class="h-4 w-4"></i> Lihat Pesanan</a>
                <a href="{{ url('/katalog') }}" class="btn-outline px-6 py-3.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Katalog</a>
            </div>
        </div>
    </section>
</div>
@endsection