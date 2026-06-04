@extends('layouts.app')
@section('title', 'Pesanan Saya - TechPart')

@section('content')
@php
    $paymentLabels = [
        'bank_bca' => 'BCA Virtual Account',
        'bank_mandiri' => 'Mandiri Virtual Account',
        'qris' => 'QRIS',
        'ewallet' => 'E-Wallet',
    ];
    $returnStatusLabels = [
        'diajukan' => 'Pengajuan dikirim', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak',
        'barang_dikirim' => 'Barang dikirim', 'barang_diterima' => 'Barang diterima',
        'dana_dikembalikan' => 'Dana dikembalikan', 'selesai' => 'Selesai',
    ];
@endphp

<div class="page-shell py-8 sm:py-10">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="eyebrow">Akun</span>
            <h1 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Riwayat pesanan</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Pantau pembayaran, pengiriman, invoice, ulasan, dan pengembalian.</p>
        </div>
        <a href="{{ url('/katalog') }}" class="btn-outline px-4 py-2.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Katalog</a>
    </div>

    <div class="space-y-5">
        @forelse($orders as $order)
            <article class="surface-card overflow-hidden">
                <header class="border-b border-slate-200/70 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y, H:i') }}</div>
                            <h2 class="mt-2 text-xl font-black tracking-[-0.03em] text-ink">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:justify-end">
                            @if($order->status === 'pending')
                                <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-black text-amber-700"><span class="status-dot bg-amber-500 animate-pulse"></span> Menunggu Bayar</span>
                            @elseif($order->status === 'dikemas' || $order->status === 'diproses')
                                <span class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-black text-blue-700"><span class="status-dot bg-blue-500"></span> Dikemas</span>
                            @elseif($order->status === 'dikirim')
                                <span class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-black text-indigo-700"><span class="status-dot bg-indigo-500"></span> Dikirim</span>
                            @elseif($order->status === 'selesai')
                                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700"><span class="status-dot bg-emerald-500"></span> Selesai</span>
                            @elseif($order->status === 'dibatalkan')
                                <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-black text-rose-700"><span class="status-dot bg-rose-500"></span> Dibatalkan</span>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-black text-slate-700"><span class="status-dot bg-slate-500"></span> {{ ucfirst($order->status) }}</span>
                            @endif
                            @if($order->tracking_number)
                                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 font-mono text-xs font-black text-slate-700"><i data-lucide="truck" class="h-3.5 w-3.5"></i>{{ $order->tracking_number }}</span>
                            @endif
                        </div>
                    </div>
                </header>

                <div class="divide-y divide-slate-200/70">
                    @foreach($order->details as $detail)
                        @php
                            $sudahDireview = $order->status === 'selesai' ? \App\Models\Review::where('order_id', $order->id)->where('product_id', $detail->product_id)->exists() : false;
                            $returAktif = $order->status === 'selesai' ? $detail->returnRequests->where('status', '!=', 'ditolak')->first() : null;
                        @endphp
                        <div class="p-5 sm:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center shrink-0">
                                        @if($detail->product->image)
                                            <img src="{{ Storage::disk('public')->url($detail->product->image) }}" alt="{{ $detail->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="image" class="w-5 h-5 text-slate-400"></i>
                                        @endif
                                    </div>

                                    <div>
                                        <h3 class="font-black text-ink">
                                            <a href="{{ url('/produk/' . $detail->product_id) }}" class="hover:underline">
                                                {{ $detail->product->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm font-semibold text-slate-500">
                                            {{ $detail->qty }} x Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-lg font-black tracking-[-0.03em] text-ink">Rp {{ number_format($detail->qty * $detail->price, 0, ',', '.') }}</div>
                            </div>

                            @if($order->status === 'selesai')
                                <div class="mt-4 flex flex-col gap-3">
                                    @if(!$sudahDireview)
                                        <form action="{{ url('/pesanan/ulasan') }}" method="POST" class="grid gap-3 rounded-2xl border border-slate-200 bg-white/70 p-4 sm:grid-cols-[180px_1fr_auto]">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="product_id" value="{{ $detail->product_id }}">
                                            <select name="rating" required class="select-modern px-3 py-2.5 text-sm font-semibold">
                                                <option value="">Rating</option>
                                                <option value="5">★★★★★</option><option value="4">★★★★</option><option value="3">★★★</option><option value="2">★★</option><option value="1">★</option>
                                            </select>
                                            <input type="text" name="comment" placeholder="Komentar singkat (opsional)" class="input-modern px-3 py-2.5 text-sm font-semibold">
                                            <button type="submit" class="btn-primary px-4 py-2.5 text-xs">Kirim</button>
                                        </form>
                                    @else
                                        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700 ring-1 ring-emerald-100"><i data-lucide="check-circle" class="h-3.5 w-3.5"></i> Sudah diulas</span>
                                    @endif

                                    @if($returAktif)
                                        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-black text-blue-700 ring-1 ring-blue-100"><i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i> Retur: {{ $returnStatusLabels[$returAktif->status] ?? ucfirst($returAktif->status) }}</span>
                                    @else
                                        <a href="{{ url('/pesanan/'.$order->id.'/retur?detail='.$detail->id) }}" class="btn-soft w-fit px-3 py-2 text-xs"><i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i> Ajukan pengembalian</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <footer class="border-t border-slate-200/70 bg-white/50 p-5 sm:p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-500">Total belanja</div>
                            <div class="mt-1 text-2xl font-black tracking-[-0.04em] text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            @if($order->status === 'pending')
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm">
                                    <div class="font-black text-ink">{{ $paymentLabels[$order->payment_method] ?? 'Payment Gateway' }}</div>
                                    <div class="mt-1 text-xs font-semibold text-slate-500">{{ optional($order->created_at)->format('d M Y, H:i') }}</div>
                                </div>
                                <a href="{{ url('/pembayaran/'.$order->id) }}" class="btn-primary px-5 py-3 text-sm"><i data-lucide="credit-card" class="h-4 w-4"></i> Bayar</a>
                            @elseif($order->status === 'dikemas' || $order->status === 'diproses')
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-500">Pesanan sedang dikemas.</div>
                            @elseif($order->status === 'dikirim')
                                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-bold text-indigo-700">Pesanan sedang dikirim.</div>
                            @elseif($order->status === 'dibatalkan')
                                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700">Pesanan dibatalkan.</div>
                            @endif
                            <a href="{{ url('/pesanan/'.$order->id.'/invoice') }}" target="_blank" class="btn-outline px-5 py-3 text-sm"><i data-lucide="printer" class="h-4 w-4"></i> Invoice</a>
                        </div>
                    </div>
                </footer>
            </article>
        @empty
            <div class="surface-card p-12 text-center">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-slate-300"><i data-lucide="shopping-bag" class="h-9 w-9"></i></div>
                <h2 class="mt-4 text-xl font-black text-ink">Belum ada pesanan</h2>
                <p class="mt-2 text-sm text-slate-500">Transaksi Anda akan tampil di sini.</p>
                <a href="{{ url('/katalog') }}" class="btn-primary mt-6 px-5 py-3 text-sm">Mulai Belanja</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
