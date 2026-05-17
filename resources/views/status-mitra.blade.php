@extends('layouts.app')
@section('title', 'Status Pengajuan Mitra - TechPart')

@section('content')
<div class="page-shell py-8 sm:py-10">
    <div class="mb-7">
        <a href="{{ url('/katalog') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Katalog
        </a>
        <h1 class="mt-4 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Status Pengajuan Mitra</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Pantau status pengajuan Anda untuk menjadi Mitra Teknisi TechPart.</p>
    </div>

    <div class="max-w-3xl">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                <i data-lucide="check-circle" class="inline h-4 w-4 mr-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(!$application)
            <!-- Belum ada pengajuan -->
            <div class="surface-card p-8 text-center">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-slate-300">
                    <i data-lucide="file-text" class="h-9 w-9"></i>
                </div>
                <h2 class="mt-4 text-xl font-black text-ink">Belum Ada Pengajuan</h2>
                <p class="mt-2 text-sm text-slate-500">Anda belum pernah mengajukan permohonan untuk menjadi Mitra Teknisi.</p>
                <a href="{{ url('/jadi-mitra') }}" class="btn-primary mt-6 inline-flex px-5 py-3 text-sm">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Ajukan Sekarang
                </a>
            </div>
        @else
            <!-- Ada pengajuan -->
            <article class="surface-card overflow-hidden">
                <!-- Header dengan status -->
                <header class="border-b border-slate-200/70 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                                {{ \Carbon\Carbon::parse($application->created_at)->translatedFormat('d F Y, H:i') }}
                            </div>
                            <h2 class="mt-2 text-xl font-black tracking-[-0.03em] text-ink">
                                Pengajuan Mitra Teknisi
                            </h2>
                        </div>
                        <div>
                            @if($application->status === 'pending')
                                <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-black text-amber-700">
                                    <span class="status-dot bg-amber-500 animate-pulse"></span>
                                    Dalam Peninjauan
                                </span>
                            @elseif($application->status === 'disetujui')
                                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-black text-emerald-700">
                                    <span class="status-dot bg-emerald-500"></span>
                                    Disetujui
                                </span>
                            @elseif($application->status === 'ditolak')
                                <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-black text-rose-700">
                                    <span class="status-dot bg-rose-500"></span>
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                </header>

                <!-- Isi pengajuan -->
                <div class="divide-y divide-slate-200/70">
                    <!-- Data Diri -->
                    <div class="p-5 sm:p-6">
                        <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-400 mb-4">Data Diri</h3>
                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-xs font-bold text-slate-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm font-black text-ink">{{ $application->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-slate-500">Email</dt>
                                <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->email }}</dd>
                            </div>
                            @if($application->no_whatsapp)
                                <div>
                                    <dt class="text-xs font-bold text-slate-500">Nomor WhatsApp</dt>
                                    <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->no_whatsapp }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Data Usaha -->
                    @if($application->nama_toko || $application->alamat_toko || $application->kota)
                        <div class="p-5 sm:p-6">
                            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-400 mb-4">Data Usaha</h3>
                            <dl class="grid gap-4 sm:grid-cols-2">
                                @if($application->nama_toko)
                                    <div>
                                        <dt class="text-xs font-bold text-slate-500">Nama Toko</dt>
                                        <dd class="mt-1 text-sm font-black text-ink">{{ $application->nama_toko }}</dd>
                                    </div>
                                @endif
                                @if($application->kota)
                                    <div>
                                        <dt class="text-xs font-bold text-slate-500">Kota</dt>
                                        <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->kota }}</dd>
                                    </div>
                                @endif
                                @if($application->alamat_toko)
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs font-bold text-slate-500">Alamat</dt>
                                        <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->alamat_toko }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    <!-- Pengalaman -->
                    @if($application->pengalaman_tahun || $application->spesialisasi)
                        <div class="p-5 sm:p-6">
                            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-400 mb-4">Pengalaman & Spesialisasi</h3>
                            <dl class="grid gap-4 sm:grid-cols-2">
                                @if($application->pengalaman_tahun)
                                    <div>
                                        <dt class="text-xs font-bold text-slate-500">Pengalaman</dt>
                                        <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->pengalaman_tahun }} tahun</dd>
                                    </div>
                                @endif
                                @if($application->spesialisasi)
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs font-bold text-slate-500">Spesialisasi</dt>
                                        <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->spesialisasi }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    <!-- Dokumen -->
                    @if($application->foto_toko || $application->foto_ktp || $application->sertifikat)
                        <div class="p-5 sm:p-6">
                            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-400 mb-4">Dokumen Pendukung</h3>
                            <div class="grid gap-4 sm:grid-cols-3">
                                @if($application->foto_toko)
                                    <div class="rounded-xl border border-slate-200 p-3 text-center">
                                        <i data-lucide="image" class="h-6 w-6 mx-auto text-slate-400"></i>
                                        <p class="mt-2 text-xs font-bold text-slate-600">Foto Toko</p>
                                        <a href="{{ Storage::url($application->foto_toko) }}" target="_blank" class="mt-1 text-xs text-primary hover:underline">Lihat</a>
                                    </div>
                                @endif
                                @if($application->foto_ktp)
                                    <div class="rounded-xl border border-slate-200 p-3 text-center">
                                        <i data-lucide="image" class="h-6 w-6 mx-auto text-slate-400"></i>
                                        <p class="mt-2 text-xs font-bold text-slate-600">Foto KTP</p>
                                        <a href="{{ Storage::url($application->foto_ktp) }}" target="_blank" class="mt-1 text-xs text-primary hover:underline">Lihat</a>
                                    </div>
                                @endif
                                @if($application->sertifikat)
                                    <div class="rounded-xl border border-slate-200 p-3 text-center">
                                        <i data-lucide="file-text" class="h-6 w-6 mx-auto text-slate-400"></i>
                                        <p class="mt-2 text-xs font-bold text-slate-600">Sertifikat</p>
                                        <a href="{{ Storage::url($application->sertifikat) }}" target="_blank" class="mt-1 text-xs text-primary hover:underline">Lihat</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Catatan Admin -->
                    @if($application->catatan_admin)
                        <div class="p-5 sm:p-6 bg-primary-50/50">
                            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-primary mb-2">Catatan dari Admin</h3>
                            <p class="text-sm font-semibold text-slate-700">{{ $application->catatan_admin }}</p>
                        </div>
                    @endif

                    <!-- Info tambahan -->
                    @if($application->alasan_bergabung || $application->approved_at)
                        <div class="p-5 sm:p-6">
                            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-slate-400 mb-4">Informasi Lainnya</h3>
                            <dl class="grid gap-4">
                                @if($application->alasan_bergabung)
                                    <div>
                                        <dt class="text-xs font-bold text-slate-500">Alasan Bergabung</dt>
                                        <dd class="mt-1 text-sm font-semibold text-ink">{{ $application->alasan_bergabung }}</dd>
                                    </div>
                                @endif
                                @if($application->approved_at)
                                    <div>
                                        <dt class="text-xs font-bold text-slate-500">Tanggal Disetujui</dt>
                                        <dd class="mt-1 text-sm font-semibold text-emerald-600">
                                            {{ \Carbon\Carbon::parse($application->approved_at)->translatedFormat('d F Y, H:i') }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <footer class="border-t border-slate-200/70 bg-white/50 p-5 sm:p-6">
                    @if($application->status === 'pending')
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-center">
                            <p class="text-sm font-semibold text-amber-800">
                                <i data-lucide="clock" class="inline h-4 w-4 mr-1"></i>
                                Pengajuan Anda sedang ditinjau. Kami akan menghubungi Anda melalui email jika ada update.
                            </p>
                        </div>
                    @elseif($application->status === 'disetujui')
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center">
                            <p class="text-sm font-semibold text-emerald-800">
                                <i data-lucide="check-circle" class="inline h-4 w-4 mr-1"></i>
                                Selamat! Anda sekarang adalah Mitra Teknisi TechPart. Nikmati harga khusus di katalog.
                            </p>
                            <a href="{{ url('/katalog') }}" class="btn-primary mt-4 inline-flex px-5 py-3 text-sm">
                                <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                                Belanja Sekarang
                            </a>
                        </div>
                    @elseif($application->status === 'ditolak')
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-center">
                            <p class="text-sm font-semibold text-rose-800">
                                <i data-lucide="alert-circle" class="inline h-4 w-4 mr-1"></i>
                                Pengajuan Anda belum disetujui. Anda dapat mengajukan kembali dengan melengkapi dokumen yang diperlukan.
                            </p>
                            <a href="{{ url('/jadi-mitra') }}" class="btn-primary mt-4 inline-flex px-5 py-3 text-sm">
                                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                                Ajukan Kembali
                            </a>
                        </div>
                    @endif
                </footer>
            </article>
        @endif
    </div>
</div>
@endsection