@extends('layouts.app')
@section('title', 'Jadi Mitra Teknisi - TechPart')

@section('content')
<div class="page-shell py-10 sm:py-14">
    <div class="grid gap-8 lg:grid-cols-[1fr_1.2fr] lg:items-start">
        <!-- Left: Info Section -->
        <aside class="mesh-card rounded-[2rem] p-7 text-white shadow-glow lg:sticky lg:top-24">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-2 text-xs font-black uppercase tracking-[0.18em] text-blue-100">
                    <i data-lucide="badge-check" class="h-4 w-4"></i>
                    Mitra Teknisi
                </span>
                <h1 class="mt-6 text-4xl font-black leading-[1.05] tracking-[-0.06em] sm:text-5xl">Bergabunglah dengan jaringan teknisi TechPart</h1>
                <p class="mt-5 text-sm leading-7 text-slate-300">Dapatkan akses ke harga khusus mitra, prioritas pengiriman, dan berbagai keuntungan lainnya untuk mengembangkan bisnis servis Anda.</p>
                
                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white/10">
                            <i data-lucide="percent" class="h-4 w-4 text-blue-200"></i>
                        </div>
                        <div>
                            <div class="text-sm font-black">Harga Khusus Mitra</div>
                            <div class="text-xs text-slate-300">Diskon hingga 30% untuk produk tertentu</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white/10">
                            <i data-lucide="truck" class="h-4 w-4 text-blue-200"></i>
                        </div>
                        <div>
                            <div class="text-sm font-black">Prioritas Pengiriman</div>
                            <div class="text-xs text-slate-300">Proses pesanan lebih cepat</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white/10">
                            <i data-lucide="headphones" class="h-4 w-4 text-blue-200"></i>
                        </div>
                        <div>
                            <div class="text-sm font-black">Dukungan Khusus</div>
                            <div class="text-xs text-slate-300">Akses ke tim support prioritas</div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="h-5 w-5 text-blue-200 shrink-0 mt-0.5"></i>
                        <p class="text-xs leading-6 text-slate-200">
                            Pengajuan Anda akan ditinjau oleh tim kami dalam 1-3 hari kerja. 
                            Pastikan semua data yang diisi sudah benar dan dokumen yang diunggah jelas.
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Right: Form Section -->
        <section class="surface-card p-6 sm:p-8">
            <span class="eyebrow">Formulir Pendaftaran</span>
            <h2 class="mt-4 text-3xl font-black tracking-[-0.05em] text-ink">Lengkapi data di bawah ini</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">
                @auth
                    Data Anda akan terhubung dengan akun yang sedang login.
                @else
                    Anda akan diminta membuat akun setelah mengirim formulir ini.
                @endauth
            </p>

            @if($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                    <ul class="list-disc pl-5 text-sm font-semibold text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/jadi-mitra') }}" method="POST" enctype="multipart/form-data" class="mt-7 space-y-8">
                @csrf

                <!-- Data Diri -->
                <div>
                    <h3 class="text-lg font-black text-ink flex items-center gap-2">
                        <i data-lucide="user" class="h-5 w-5 text-primary"></i>
                        Data Diri
                    </h3>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Nama lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required class="input-modern px-4 py-3.5 text-sm font-semibold" 
                                value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Nama lengkap Anda">
                        </div>
                        <div>
                            <label class="field-label">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required class="input-modern px-4 py-3.5 text-sm font-semibold" 
                                value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="nama@email.com">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Nomor WhatsApp</label>
                            <input type="text" name="no_whatsapp" class="input-modern px-4 py-3.5 text-sm font-semibold" 
                                value="{{ old('no_whatsapp') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- Data Usaha -->
                <div>
                    <h3 class="text-lg font-black text-ink flex items-center gap-2">
                        <i data-lucide="store" class="h-5 w-5 text-primary"></i>
                        Data Usaha / Toko
                    </h3>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="field-label">Nama Toko / Tempat Servis</label>
                            <input type="text" name="nama_toko" class="input-modern px-4 py-3.5 text-sm font-semibold" 
                                value="{{ old('nama_toko') }}" placeholder="Contoh: TechFix Service">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Alamat Lengkap</label>
                            <textarea name="alamat_toko" rows="3" class="textarea-modern px-4 py-3.5 text-sm font-semibold" 
                                placeholder="Alamat lengkap toko/tempat servis Anda">{{ old('alamat_toko') }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Kota / Kabupaten</label>
                            <input type="text" name="kota" class="input-modern px-4 py-3.5 text-sm font-semibold" 
                                value="{{ old('kota') }}" placeholder="Contoh: Jakarta Selatan">
                        </div>
                    </div>
                </div>

                <!-- Pengalaman -->
                <div>
                    <h3 class="text-lg font-black text-ink flex items-center gap-2">
                        <i data-lucide="briefcase" class="h-5 w-5 text-primary"></i>
                        Pengalaman & Spesialisasi
                    </h3>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Lama Pengalaman (tahun)</label>
                            <input type="number" name="pengalaman_tahun" min="0" max="50" class="input-modern px-4 py-3.5 text-sm font-semibold" 
                                value="{{ old('pengalaman_tahun') }}" placeholder="Contoh: 5">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Spesialisasi (pilih yang sesuai)</label>
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @php
                                    $specializations = [
                                        'smartphone' => 'Smartphone',
                                        'laptop' => 'Laptop',
                                        'tablet' => 'Tablet',
                                        'pc' => 'PC / Desktop',
                                        'printer' => 'Printer',
                                        'lainnya' => 'Lainnya'
                                    ];
                                @endphp
                                @foreach($specializations as $value => $label)
                                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white/60 p-3 cursor-pointer hover:border-primary/30 transition">
                                        <input type="checkbox" name="spesialisasi[]" value="{{ $value }}" 
                                            class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary"
                                            {{ in_array($value, old('spesialisasi', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Pendukung -->
                <div>
                    <h3 class="text-lg font-black text-ink flex items-center gap-2">
                        <i data-lucide="upload" class="h-5 w-5 text-primary"></i>
                        Dokumen Pendukung
                    </h3>
                    <div class="mt-4 space-y-5">
                        <div>
                            <label class="field-label">Foto Toko / Tempat Usaha</label>
                            <input type="file" name="foto_toko" accept="image/*" class="w-full text-sm font-semibold text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-primary-100 file:px-4 file:py-2 file:text-sm file:font-black file:text-primary hover:file:bg-primary-200">
                            <p class="mt-1.5 text-xs text-slate-400">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                        <div>
                            <label class="field-label">Foto KTP (Opsional)</label>
                            <input type="file" name="foto_ktp" accept="image/*" class="w-full text-sm font-semibold text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-primary-100 file:px-4 file:py-2 file:text-sm file:font-black file:text-primary hover:file:bg-primary-200">
                            <p class="mt-1.5 text-xs text-slate-400">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                        <div>
                            <label class="field-label">Sertifikat / Training (Opsional)</label>
                            <input type="file" name="sertifikat" accept="image/*,application/pdf" class="w-full text-sm font-semibold text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-primary-100 file:px-4 file:py-2 file:text-sm file:font-black file:text-primary hover:file:bg-primary-200">
                            <p class="mt-1.5 text-xs text-slate-400">Format: JPG, PNG, PDF. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div>
                    <h3 class="text-lg font-black text-ink flex items-center gap-2">
                        <i data-lucide="message-circle" class="h-5 w-5 text-primary"></i>
                        Informasi Tambahan
                    </h3>
                    <div class="mt-4 grid gap-5">
                        <div>
                            <label class="field-label">Alasan Ingin Bergabung sebagai Mitra</label>
                            <textarea name="alasan_bergabung" rows="4" class="textarea-modern px-4 py-3.5 text-sm font-semibold" 
                                placeholder="Ceritakan mengapa Anda ingin menjadi mitra TechPart...">{{ old('alasan_bergabung') }}</textarea>
                        </div>
                        <div>
                            <label class="field-label">Dari mana Anda mengetahui TechPart?</label>
                            <select name="sumber_info" class="select-modern px-4 py-3.5 text-sm font-semibold">
                                <option value="">-- Pilih --</option>
                                <option value="google">Google Search</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="tiktok">TikTok</option>
                                <option value="teman">Rekomendasi Teman</option>
                                <option value="youtube">YouTube</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="btn-primary w-full px-5 py-4 text-sm">
                        <i data-lucide="send" class="h-4 w-4"></i>
                        Kirim Pengajuan Mitra
                    </button>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                    <p class="text-sm font-semibold text-slate-600">
                        Sudah punya akun? 
                        @auth
                            <a href="{{ url('/status-pengajuan-mitra') }}" class="font-black text-primary hover:underline">Cek status pengajuan</a>
                        @else
                            <a href="{{ url('/login') }}" class="font-black text-primary hover:underline">Masuk di sini</a>
                        @endauth
                    </p>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection