@extends('layouts.app')
@section('title', 'Ajukan Pengembalian - TechPart')

@section('content')
<div class="page-shell py-8 sm:py-10">
    <div class="mb-7">
        <a href="{{ url('/pesanan-saya') }}" class="btn-outline mb-4 px-4 py-2.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Riwayat</a>
        <span class="eyebrow">Retur</span>
        <h1 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Ajukan pengembalian</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
            <div class="font-black">Pengajuan belum bisa dikirim:</div>
            <ul class="mt-1 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/pesanan/'.$order->id.'/retur') }}" method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1fr_360px] lg:items-start">
        @csrf
        <section class="surface-card p-6 sm:p-8">
            <div class="grid gap-5">
                <div>
                    <label class="field-label">Produk yang dikembalikan</label>
                    <select name="order_detail_id" id="order_detail_id" required class="select-modern px-4 py-3.5 text-sm font-semibold">
                        <option value="">Pilih produk</option>
                        @foreach($order->details as $detail)
                            @php $returAktif = $detail->returnRequests->where('status', '!=', 'ditolak')->first(); @endphp
                            <option value="{{ $detail->id }}" data-max="{{ $detail->qty }}" {{ old('order_detail_id', optional($selectedDetail)->id) == $detail->id ? 'selected' : '' }} {{ $returAktif ? 'disabled' : '' }}>
                                {{ $detail->product->name }} - Qty {{ $detail->qty }}{{ $returAktif ? ' (retur berjalan)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="field-label">Jumlah retur</label>
                        <input type="number" name="qty" value="{{ old('qty', 1) }}" min="1" required class="input-modern px-4 py-3.5 text-sm font-semibold">
                    </div>
                    <div>
                        <label class="field-label">Alasan</label>
                        <select name="reason" required class="select-modern px-4 py-3.5 text-sm font-semibold">
                            @foreach(['Produk rusak', 'Produk tidak sesuai', 'Komponen tidak kompatibel', 'Jumlah barang kurang', 'Lainnya'] as $reason)
                                <option value="{{ $reason }}" {{ old('reason') == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="field-label">Deskripsi masalah</label>
                    <textarea name="description" rows="6" required placeholder="Jelaskan kondisi barang, masalah yang ditemukan, dan solusi yang Anda harapkan." class="textarea-modern px-4 py-3.5 text-sm font-semibold">{{ old('description') }}</textarea>
                </div>

                <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white/60 p-5">
                    <label class="field-label">Foto bukti</label>
                    <input type="file" name="evidence_image" accept="image/png,image/jpeg,image/jpg" class="w-full text-sm font-semibold text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-primary-100 file:px-4 file:py-2 file:text-sm file:font-black file:text-primary hover:file:bg-primary-200">
                    <p class="mt-2 text-xs font-semibold text-slate-500">Opsional, format JPG/PNG maksimal 2 MB.</p>
                </div>
            </div>
        </section>

        <aside class="surface-card p-6 lg:sticky lg:top-24">
            <span class="eyebrow">Ringkasan</span>
            <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Proses retur</h2>
            <ol class="mt-5 space-y-4 text-sm font-semibold leading-6 text-slate-600">
                @foreach(['Kirim pengajuan dari form ini.', 'Admin meninjau alasan dan bukti.', 'Jika disetujui, kirim barang ke alamat retur.', 'Admin memproses penggantian atau pengembalian dana.'] as $step)
                    <li class="flex gap-3"><span class="font-black text-primary">{{ $loop->iteration }}.</span><span>{{ $step }}</span></li>
                @endforeach
            </ol>
            <button type="submit" class="btn-primary mt-7 w-full px-5 py-4 text-sm"><i data-lucide="send" class="h-5 w-5"></i> Kirim Pengajuan</button>
        </aside>
    </form>
</div>
@endsection