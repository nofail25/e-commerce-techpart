@extends('layouts.admin')
@section('title', 'Verifikasi Mitra - TechPart')

@section('content')
<div class="space-y-6 sm:space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Verifikasi Mitra Teknisi</h1>
            <p class="text-slate-600">Tinjau dan persetujui pengajuan mitra baru untuk bergabung</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-start gap-3 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Partners Grid -->
    @forelse($pengajuan as $application)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 sm:p-8">
                <!-- Header: User Info -->
                <div class="flex flex-col sm:flex-row sm:items-start gap-6 justify-between mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700 flex items-center justify-center font-bold text-xl border border-primary-300 shadow-sm shrink-0">
                            {{ substr($application->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-900">{{ $application->name }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span> Calon Mitra
                                </span>
                            </div>
                            <div class="mt-3 space-y-1">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                                    {{ $application->email }}
                                </div>
                                @if($application->no_whatsapp)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                                        {{ $application->no_whatsapp }}
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                    Diajukan: {{ \Carbon\Carbon::parse($application->created_at)->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Data -->
                <div class="grid sm:grid-cols-2 gap-6 mb-6 p-5 bg-slate-50 rounded-xl border border-slate-100">
                    @if($application->nama_toko || $application->kota || $application->alamat_toko)
                        <div>
                            <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-3">Data Usaha</h4>
                            <dl class="space-y-2">
                                @if($application->nama_toko)
                                    <div>
                                        <dt class="text-xs text-slate-500">Nama Toko</dt>
                                        <dd class="text-sm font-semibold text-slate-900">{{ $application->nama_toko }}</dd>
                                    </div>
                                @endif
                                @if($application->kota)
                                    <div>
                                        <dt class="text-xs text-slate-500">Kota</dt>
                                        <dd class="text-sm font-semibold text-slate-900">{{ $application->kota }}</dd>
                                    </div>
                                @endif
                                @if($application->alamat_toko)
                                    <div>
                                        <dt class="text-xs text-slate-500">Alamat</dt>
                                        <dd class="text-sm font-semibold text-slate-900">{{ $application->alamat_toko }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    @if($application->pengalaman_tahun || $application->spesialisasi)
                        <div>
                            <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-3">Pengalaman</h4>
                            <dl class="space-y-2">
                                @if($application->pengalaman_tahun)
                                    <div>
                                        <dt class="text-xs text-slate-500">Pengalaman</dt>
                                        <dd class="text-sm font-semibold text-slate-900">{{ $application->pengalaman_tahun }} tahun</dd>
                                    </div>
                                @endif
                                @if($application->spesialisasi)
                                    <div>
                                        <dt class="text-xs text-slate-500">Spesialisasi</dt>
                                        <dd class="text-sm font-semibold text-slate-900">{{ $application->spesialisasi }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif
                </div>

                <!-- Alasan Bergabung -->
                @if($application->alasan_bergabung)
                    <div class="mb-6 p-5 bg-amber-50/50 rounded-xl border border-amber-100">
                        <h4 class="text-sm font-bold text-amber-800 uppercase tracking-wide mb-2">Alasan Bergabung</h4>
                        <p class="text-sm text-slate-700">{{ $application->alasan_bergabung }}</p>
                    </div>
                @endif

                <!-- Dokumen -->
                @if($application->foto_toko || $application->foto_ktp || $application->sertifikat)
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-3">Dokumen Pendukung</h4>
                        <div class="grid sm:grid-cols-3 gap-4">
                            @if($application->foto_toko)
                                <a href="{{ Storage::url($application->foto_toko) }}" target="_blank" class="group block p-3 rounded-xl border border-slate-200 hover:border-primary-300 hover:bg-primary-50 transition">
                                    <div class="aspect-video rounded-lg bg-slate-100 flex items-center justify-center mb-2 overflow-hidden">
                                        <img src="{{ Storage::url($application->foto_toko) }}" alt="Foto Toko" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 text-center group-hover:text-primary">Foto Toko</p>
                                </a>
                            @endif
                            @if($application->foto_ktp)
                                <a href="{{ Storage::url($application->foto_ktp) }}" target="_blank" class="group block p-3 rounded-xl border border-slate-200 hover:border-primary-300 hover:bg-primary-50 transition">
                                    <div class="aspect-video rounded-lg bg-slate-100 flex items-center justify-center mb-2 overflow-hidden">
                                        <img src="{{ Storage::url($application->foto_ktp) }}" alt="Foto KTP" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 text-center group-hover:text-primary">Foto KTP</p>
                                </a>
                            @endif
                            @if($application->sertifikat)
                                <a href="{{ Storage::url($application->sertifikat) }}" target="_blank" class="group block p-3 rounded-xl border border-slate-200 hover:border-primary-300 hover:bg-primary-50 transition">
                                    <div class="aspect-video rounded-lg bg-slate-100 flex items-center justify-center mb-2">
                                        <i data-lucide="file-text" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 text-center group-hover:text-primary">Sertifikat</p>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-200">
                    <!-- Input Catatan -->
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 mb-2">Catatan (Opsional)</label>
                        <input type="text" name="catatan_{{ $application->id }}" placeholder="Tambahkan catatan..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                    </div>

                    <!-- Tombol Tolak -->
                    <form action="{{ url('/admin/pengajuan-mitra/'.$application->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="aksi" value="tolak">
                        <input type="hidden" name="catatan" id="catatan_tolak_{{ $application->id }}">
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded-xl transition-all duration-200 border border-rose-200 hover:border-rose-300" onclick="return confirm('Tolak pengajuan mitra ini?')">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Tolak
                        </button>
                    </form>

                    <!-- Tombol Setujui -->
                    <form action="{{ url('/admin/pengajuan-mitra/'.$application->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="aksi" value="setujui">
                        <input type="hidden" name="catatan" id="catatan_setuju_{{ $application->id }}">
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 hover:from-emerald-700 to-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg" onclick="return confirm('Setujui pengajuan mitra ini?')">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> Setujui Mitra
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl shadow-sm border border-slate-200 py-16 text-center">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm">
                    <i data-lucide="users-check" class="w-10 h-10 text-slate-400"></i>
                </div>
                <div class="max-w-sm">
                    <h4 class="text-lg font-bold text-slate-700">Belum Ada Pengajuan Baru</h4>
                    <p class="text-slate-600 mt-2">Saat ini tidak ada pengajuan mitra teknisi yang menunggu persetujuan.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection