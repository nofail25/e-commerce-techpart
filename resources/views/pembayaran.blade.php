@extends('layouts.app')
@section('title', 'Pembayaran Pesanan - TechPart')

@section('content')
@php
    $isPaid = $order->status !== 'pending' || $order->payment_status === 'paid';
@endphp

<div class="page-shell py-8 sm:py-10">
    <div class="mb-7 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ url('/pesanan-saya') }}" class="btn-outline mb-4 px-4 py-2.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Riwayat</a>
            <span class="eyebrow">Pembayaran</span>
            <h1 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Selesaikan pembayaran dengan detail yang jelas dan mudah dicek.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full border px-4 py-2 text-sm font-black {{ $isPaid ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
            <span class="status-dot {{ $isPaid ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
            {{ $isPaid ? 'Pembayaran berhasil' : 'Menunggu pembayaran' }}
        </span>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_400px] lg:items-start">
        <main class="space-y-6">
            <section class="surface-card p-5 sm:p-6">
                <div class="grid gap-3 sm:grid-cols-4">
                    @foreach([
                        ['shopping-cart', 'Checkout', true],
                        [$selectedMethod['icon'], 'Metode', true],
                        [$isPaid ? 'check' : 'timer', 'Bayar', $isPaid],
                        ['package-check', 'Dikemas', $isPaid],
                    ] as $step)
                        <div class="rounded-2xl border {{ $step[2] ? 'border-primary/20 bg-primary-50 text-primary' : 'border-slate-200 bg-white/70 text-slate-400' }} p-4">
                            <i data-lucide="{{ $step[0] }}" class="h-5 w-5"></i>
                            <div class="mt-4 text-sm font-black text-ink">{{ $step[1] }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="surface-card overflow-hidden">
                <div class="border-b border-slate-200/70 p-5 sm:p-6">
                    <span class="eyebrow">{{ $selectedMethod['short'] }}</span>
                    <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">{{ $selectedMethod['label'] }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan kode berikut sesuai instruksi metode pembayaran.</p>
                </div>

                <div class="p-5 sm:p-6">
                    @if($order->payment_method === 'qris')
                        <div class="grid gap-6 md:grid-cols-[240px_1fr] md:items-center">
                            <div class="grid aspect-square grid-cols-5 gap-1 rounded-[1.5rem] border-8 border-slate-900 bg-white p-4 shadow-soft">
                                @for($i = 0; $i < 25; $i++)
                                    <div class="{{ in_array($i, [0,1,5,6,18,19,23,24,12,7,17]) ? 'bg-slate-900' : 'bg-slate-100' }} rounded-sm"></div>
                                @endfor
                            </div>
                            <div>
                                <div class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">Kode QRIS</div>
                                <p class="mt-4 text-sm leading-6 text-slate-500">Buka aplikasi bank/e-wallet, pilih QRIS, lalu scan QR ini.</p>
                            </div>
                        </div>
                    @else
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-[1.5rem] border border-primary/20 bg-primary-50/70 p-5 md:col-span-2">
                                <div class="text-xs font-black uppercase tracking-[0.16em] text-primary">Total bayar</div>
                                <div class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <div class="text-xs font-black uppercase tracking-[0.16em] text-amber-700">Batas pembayaran</div>
                            <div class="mt-2 font-black text-ink">{{ optional($order->payment_expires_at)->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                            <div class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">Status gateway</div>
                            <div class="mt-2 font-black {{ $isPaid ? 'text-emerald-600' : 'text-amber-600' }}">{{ $isPaid ? 'Paid' : 'Unpaid' }}</div>
                        </div>
                    </div>
                </div>
            </section>



            @if(!$isPaid)
                <section class="surface-card p-5 sm:p-6">
                    <span class="eyebrow">Alternatif</span>
                    <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Ganti metode pembayaran</h2>
                    <form action="{{ url('/pembayaran/'.$order->id.'/metode') }}" method="POST" class="mt-5 grid gap-3 sm:grid-cols-2">
                        @csrf
                        @foreach($paymentMethods as $key => $method)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border p-3 transition {{ $order->payment_method === $key ? 'border-primary bg-primary-50' : 'border-slate-200 bg-white/70 hover:border-primary/30' }}">
                                <input type="radio" name="payment_method" value="{{ $key }}" class="h-4 w-4 text-primary focus:ring-primary" {{ $order->payment_method === $key ? 'checked' : '' }}>
                                <i data-lucide="{{ $method['icon'] }}" class="h-4 w-4 text-primary"></i>
                                <span class="text-sm font-black text-ink">{{ $method['label'] }}</span>
                            </label>
                        @endforeach
                        <div class="sm:col-span-2 flex justify-end">
                            <button type="submit" class="btn-dark px-5 py-3 text-sm">Update Metode</button>
                        </div>
                    </form>
                </section>
            @endif
        </main>

        <aside class="surface-card overflow-hidden lg:sticky lg:top-24">
            <div class="border-b border-slate-200/70 p-5 sm:p-6">
                <h2 class="text-xl font-black text-ink">Ringkasan pesanan</h2>
                <p class="mt-1 text-sm font-semibold text-slate-500">{{ $order->details->sum('qty') }} item</p>
            </div>
            <div class="max-h-[360px] space-y-4 overflow-y-auto p-5 sm:p-6">
                @foreach($order->details as $detail)
                    <div class="flex gap-3">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200">
                            @if($detail->product && $detail->product->image)
                                <img src="{{ Storage::disk('public')->url($detail->product->image) }}" alt="{{ $detail->product->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="grid h-full w-full place-items-center text-slate-300"><i data-lucide="package" class="h-6 w-6"></i></div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="line-clamp-2 text-sm font-black text-ink">{{ $detail->product->name ?? 'Produk' }}</div>
                            <div class="mt-1 text-xs font-semibold text-slate-500">{{ $detail->qty }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-slate-200/70 p-5 sm:p-6">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="font-semibold text-slate-500">Metode</span><span class="font-black text-ink">{{ $selectedMethod['short'] }}</span></div>
                    <div class="flex justify-between"><span class="font-semibold text-slate-500">Biaya admin</span><span class="font-black text-ink">Rp {{ number_format($selectedMethod['fee'], 0, ',', '.') }}</span></div>
                    <div class="flex items-end justify-between border-t border-slate-200 pt-4"><span class="font-black text-ink">Total</span><span class="text-2xl font-black tracking-[-0.04em] text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
                </div>

                @if(!$isPaid)
                    <form action="{{ url('/pembayaran/'.$order->id.'/bayar') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" class="btn-primary w-full px-5 py-4 text-sm"><i data-lucide="check-circle" class="h-5 w-5"></i> Selesaikan Pembayaran</button>
                    </form>
                @else
                    <a href="{{ url('/pesanan-saya') }}" class="btn-dark mt-6 w-full px-5 py-4 text-sm"><i data-lucide="clipboard-list" class="h-5 w-5"></i> Lihat Pesanan</a>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
