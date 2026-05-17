@extends('layouts.app')
@section('title', 'Cara Pengembalian Barang - TechPart')

@section('content')
<div class="page-shell py-8 sm:py-10">
    <a href="{{ url('/katalog') }}" class="btn-outline mb-6 px-4 py-2.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Katalog</a>

    <section class="mesh-card rounded-[2rem] p-7 text-white shadow-glow sm:p-9">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-2 text-xs font-black uppercase tracking-[0.18em] text-blue-100">Pengembalian</span>
            <h1 class="mt-6 text-4xl font-black leading-[1.05] tracking-[-0.06em] sm:text-5xl">Retur dibuat jelas, bertahap, dan mudah dipantau.</h1>
            <p class="mt-5 text-sm leading-7 text-slate-300 sm:text-base">Ajukan pengembalian dari riwayat pesanan untuk item yang sudah selesai, lalu pantau statusnya dari halaman Retur Saya.</p>
        </div>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px] lg:items-start">
        <main class="space-y-6">
            <section class="surface-card p-6 sm:p-8">
                <span class="eyebrow">Alur</span>
                <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Langkah pengembalian</h2>
                <div class="mt-6 grid gap-4">
                    @foreach([
                        ['Buka Riwayat Pesanan', 'Pilih pesanan selesai, lalu klik Ajukan Pengembalian pada item terkait.'],
                        ['Isi Form Retur', 'Pilih produk, jumlah, alasan, deskripsi masalah, dan unggah foto bukti bila ada.'],
                        ['Menunggu Peninjauan', 'Admin mengecek detail pengajuan dan menentukan status persetujuan.'],
                        ['Kirim Barang', 'Jika disetujui, kirim barang sesuai instruksi admin dan simpan resi.'],
                        ['Pemeriksaan Barang', 'Admin memeriksa barang dan memperbarui status retur.'],
                        ['Penyelesaian', 'Penggantian produk atau pengembalian dana diproses sesuai ketentuan.'],
                    ] as $index => $step)
                        <div class="soft-card flex gap-4 p-4">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-slate-900 text-sm font-black text-white">{{ $index + 1 }}</div>
                            <div>
                                <h3 class="font-black text-ink">{{ $step[0] }}</h3>
                                <p class="mt-1 text-sm font-semibold leading-6 text-slate-500">{{ $step[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="surface-card p-6 sm:p-8">
                <span class="eyebrow">Syarat</span>
                <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Yang perlu disiapkan</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach([
                        ['check-circle', 'Produk rusak, tidak sesuai pesanan, atau tidak kompatibel dengan deskripsi.'],
                        ['clock', 'Pengajuan dilakukan setelah pesanan berstatus selesai.'],
                        ['package-check', 'Produk, label, dan kelengkapan dikirim kembali dengan aman.'],
                        ['image', 'Foto bukti disarankan agar proses pemeriksaan lebih cepat.'],
                    ] as $item)
                        <div class="soft-card p-4">
                            <i data-lucide="{{ $item[0] }}" class="h-5 w-5 text-primary"></i>
                            <p class="mt-3 text-sm font-semibold leading-6 text-slate-600">{{ $item[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>

        <aside class="surface-card p-6 lg:sticky lg:top-24">
            <span class="eyebrow">Mulai</span>
            <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Ajukan dari pesanan</h2>
            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500">Pengembalian hanya bisa diajukan dari item pesanan yang sudah selesai agar data transaksi tetap akurat.</p>
            @auth
                <a href="{{ url('/pesanan-saya') }}" class="btn-primary mt-6 w-full px-5 py-3.5 text-sm"><i data-lucide="clipboard-list" class="h-5 w-5"></i> Buka Pesanan Saya</a>
                <a href="{{ url('/retur-saya') }}" class="btn-outline mt-3 w-full px-5 py-3.5 text-sm"><i data-lucide="rotate-ccw" class="h-5 w-5"></i> Retur Saya</a>
            @else
                <a href="{{ url('/login') }}" class="btn-primary mt-6 w-full px-5 py-3.5 text-sm"><i data-lucide="log-in" class="h-5 w-5"></i> Masuk untuk Mengajukan</a>
            @endauth
        </aside>
    </div>
</div>
@endsection