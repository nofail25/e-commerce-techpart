@extends('layouts.app')
@section('title', $product->name . ' - TechPart')

@section('content')
@php
    $reviews = $product->reviews->sortByDesc('created_at');
    $reviewCount = $reviews->count();
    $averageRating = $reviewCount > 0 ? $reviews->avg('rating') : 0;
    $isMitra = Auth::check() && Auth::user()->role === 'mitra';
@endphp

<div class="page-shell py-8 sm:py-10">
    <a href="{{ url()->previous() }}" class="btn-outline mb-6 px-4 py-2.5 text-sm">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
    </a>

    <section class="surface-card overflow-hidden">
        <div class="grid gap-0 lg:grid-cols-[1fr_.92fr]">
            <div class="relative min-h-[340px] bg-slate-100 p-5 sm:p-8">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_10%,rgba(37,99,235,.18),transparent_35%),radial-gradient(circle_at_80%_90%,rgba(20,184,166,.16),transparent_32%)]"></div>
                <div class="relative flex h-full min-h-[300px] items-center justify-center rounded-[1.5rem] border border-white/70 bg-white/50 p-4 backdrop-blur">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-[500px] w-full object-contain drop-shadow-2xl">
                    @else
                        <div class="grid h-40 w-40 place-items-center rounded-[2rem] bg-white/80 text-slate-300 shadow-soft">
                            <i data-lucide="cpu" class="h-20 w-20"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-5 sm:p-8 lg:p-10">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="eyebrow">{{ $product->category }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500 ring-1 ring-slate-200">{{ $product->brand }} • {{ $product->series }}</span>
                </div>

                <h1 class="mt-5 text-3xl font-black leading-tight tracking-[-0.05em] text-ink sm:text-4xl">{{ $product->name }}</h1>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" class="h-4 w-4 {{ $i <= round($averageRating) ? 'fill-amber-400 text-amber-400' : 'text-slate-300' }}"></i>
                        @endfor
                    </div>
                    <span class="text-sm font-black text-slate-700">{{ $reviewCount > 0 ? number_format($averageRating, 1) : 'Belum ada rating' }}</span>
                    <span class="text-sm font-semibold text-slate-400">{{ $reviewCount }} ulasan</span>
                </div>

                <div class="mt-7 rounded-[1.5rem] border border-slate-200/80 bg-white/70 p-5">
                    @if($isMitra)
                        <div class="text-sm font-bold text-slate-400 line-through">Rp {{ number_format($product->priceRetail, 0, ',', '.') }}</div>
                        <div class="mt-1 flex flex-wrap items-end gap-3">
                            <div class="text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Rp {{ number_format($product->priceMitra, 0, ',', '.') }}</div>
                            <span class="mb-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-[0.14em] text-emerald-700 ring-1 ring-emerald-100">Harga Mitra</span>
                        </div>
                    @else
                        <div class="text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Rp {{ number_format($product->priceRetail, 0, ',', '.') }}</div>
                        @guest
                            <a href="{{ url('/login') }}" class="mt-3 inline-flex text-sm font-bold text-primary hover:underline">Masuk sebagai mitra untuk harga khusus</a>
                        @endguest
                    @endif
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    <div class="soft-card p-4">
                        <i data-lucide="package" class="h-5 w-5 text-primary"></i>
                        <div class="mt-3 text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Stok</div>
                        <div class="mt-1 font-black {{ $product->stock < 5 ? 'text-rose-600' : 'text-ink' }}">{{ $product->stock }} Unit</div>
                    </div>
                    <div class="soft-card p-4">
                        <i data-lucide="weight" class="h-5 w-5 text-primary"></i>
                        <div class="mt-3 text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Berat</div>
                        <div class="mt-1 font-black text-ink">{{ $product->weight }} gram</div>
                    </div>
                    <div class="soft-card p-4">
                        <i data-lucide="cpu" class="h-5 w-5 text-primary"></i>
                        <div class="mt-3 text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Model</div>
                        <div class="mt-1 font-black text-ink">{{ $product->model ?? 'Universal' }}</div>
                    </div>
                </div>

                <form onsubmit="tambahKeKeranjang(event, {{ $product->id }})" class="mt-7">
                    @csrf
                    @if($product->stock > 0)
                        <button type="submit" class="btn-primary w-full px-6 py-4 text-sm">
                            <i data-lucide="shopping-bag" class="h-5 w-5"></i> Tambah ke Keranjang
                        </button>
                    @else
                        <button type="button" disabled class="w-full rounded-2xl bg-slate-200 px-6 py-4 text-sm font-black text-slate-500">
                            Stok Habis
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </section>

    <section class="mt-6 grid gap-6 lg:grid-cols-[.9fr_1.1fr]">
        <div class="surface-card p-6 sm:p-8">
            <span class="eyebrow">Deskripsi</span>
            <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Detail produk</h2>
            <div class="mt-5 text-sm leading-7 text-slate-600 sm:text-base">
                {!! nl2br(e($product->description ?? 'Deskripsi belum ditambahkan untuk produk ini.')) !!}
            </div>
        </div>

        <div class="surface-card p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="eyebrow">Ulasan</span>
                    <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Suara pembeli</h2>
                </div>
                <div class="rounded-2xl bg-slate-900 px-4 py-3 text-white">
                    <div class="text-2xl font-black">{{ $reviewCount > 0 ? number_format($averageRating, 1) : '0.0' }}</div>
                    <div class="text-xs font-bold text-slate-300">{{ $reviewCount }} ulasan</div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($reviews as $review)
                    <div class="soft-card p-4">
                        <div class="flex items-start gap-3">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-primary-50 text-sm font-black text-primary ring-1 ring-primary-100">
                                {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="text-sm font-black text-ink">{{ $review->user->name ?? 'Pembeli' }}</h3>
                                        <p class="text-xs font-semibold text-slate-400">{{ optional($review->created_at)->format('d M Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="h-4 w-4 {{ $i <= $review->rating ? 'fill-amber-400 text-amber-400' : 'text-slate-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $review->comment ?: 'Pembeli memberikan rating tanpa komentar.' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white/50 p-8 text-center">
                        <i data-lucide="message-circle" class="mx-auto h-12 w-12 text-slate-300"></i>
                        <h3 class="mt-3 font-black text-ink">Belum ada ulasan</h3>
                        <p class="mt-1 text-sm text-slate-500">Ulasan tampil setelah pembeli menyelesaikan pesanan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection