@extends('layouts.app')
@section('title', 'Masuk - TechPart')

@section('content')
<div class="page-shell py-10 sm:py-14">
    <div class="grid min-h-[620px] gap-6 lg:grid-cols-[1fr_460px] lg:items-stretch">
        <section class="mesh-card hidden rounded-[2rem] p-8 text-white shadow-glow lg:flex lg:flex-col lg:justify-between">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-2 text-xs font-black uppercase tracking-[0.18em] text-blue-100">
                    <span class="status-dot bg-mint"></span> Portal customer
                </span>
                <h1 class="mt-6 max-w-xl text-5xl font-black leading-[1.02] tracking-[-0.06em]">Masuk dan lanjutkan belanja sparepart tanpa distraksi.</h1>
                <p class="mt-5 max-w-lg text-sm leading-7 text-slate-300">Akses keranjang, pembayaran, riwayat pesanan, harga mitra, dan status retur dalam satu dashboard yang bersih.</p>
            </div>
            <div class="relative z-10 grid grid-cols-3 gap-3">
                @foreach([['shopping-bag','Keranjang'], ['credit-card','Pembayaran'], ['rotate-ccw','Retur']] as $item)
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <i data-lucide="{{ $item[0] }}" class="h-5 w-5 text-blue-200"></i>
                        <div class="mt-4 text-sm font-black">{{ $item[1] }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="surface-card flex flex-col justify-center p-6 sm:p-8">
            <div class="mx-auto w-full max-w-sm">
                <span class="eyebrow">Masuk</span>
                <h2 class="mt-4 text-3xl font-black tracking-[-0.05em] text-ink">Selamat datang kembali</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan email dan kata sandi untuk mengakses akun TechPart Anda.</p>

                @if($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="mt-7 space-y-5">
                    @csrf
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" name="email" required class="input-modern px-4 py-3.5 text-sm font-semibold" placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="field-label">Kata sandi</label>
                        <input type="password" name="password" required class="input-modern px-4 py-3.5 text-sm font-semibold" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-primary w-full px-5 py-3.5 text-sm">
                        Masuk <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </form>

                <div class="mt-7 rounded-2xl border border-slate-200/80 bg-white/60 p-4 text-center text-sm font-semibold text-slate-500">
                    Belum punya akun?
                    <a href="{{ url('/register') }}" class="font-black text-primary hover:underline">Daftar sekarang</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection