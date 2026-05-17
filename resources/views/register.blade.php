@extends('layouts.app')
@section('title', 'Daftar - TechPart')

@section('content')
<div class="page-shell py-10 sm:py-14">
    <div class="grid gap-6 lg:grid-cols-[.86fr_1fr] lg:items-start">
        <aside class="mesh-card rounded-[2rem] p-7 text-white shadow-glow lg:sticky lg:top-24">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-2 text-xs font-black uppercase tracking-[0.18em] text-blue-100">
                    Akun baru
                </span>
                <h1 class="mt-6 text-4xl font-black leading-[1.05] tracking-[-0.06em] sm:text-5xl">Buat akun yang siap untuk belanja dan kemitraan teknisi.</h1>
                <p class="mt-5 text-sm leading-7 text-slate-300">Customer bisa belanja langsung, sementara teknisi dapat mengajukan harga mitra dengan bukti toko atau aktivitas servis.</p>
                <div class="mt-8 grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                    @foreach([['shield-check','Aman'], ['badge-percent','Mitra'], ['package-check','Order']] as $item)
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <i data-lucide="{{ $item[0] }}" class="h-5 w-5 text-blue-200"></i>
                            <div class="mt-4 text-sm font-black">{{ $item[1] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <section class="surface-card p-6 sm:p-8">
            <span class="eyebrow">Daftar</span>
            <h2 class="mt-4 text-3xl font-black tracking-[-0.05em] text-ink">Mulai dengan data dasar</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">Form dibuat ringkas agar customer baru bisa masuk lebih cepat.</p>

            @if($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST" enctype="multipart/form-data" class="mt-7 space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="field-label">Nama lengkap</label>
                        <input type="text" name="name" required class="input-modern px-4 py-3.5 text-sm font-semibold" value="{{ old('name') }}" placeholder="Nama Anda">
                    </div>
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" name="email" required class="input-modern px-4 py-3.5 text-sm font-semibold" value="{{ old('email') }}" placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label class="field-label">Kata sandi</label>
                    <input type="password" name="password" required class="input-modern px-4 py-3.5 text-sm font-semibold" placeholder="Minimal sesuai ketentuan sistem">
                </div>

                <div class="rounded-[1.5rem] border border-primary/20 bg-primary-50/70 p-5">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="checkbox" name="is_mitra" id="is_mitra_checkbox" value="1" class="mt-1 h-5 w-5 rounded border-slate-300 text-primary focus:ring-primary" onchange="toggleUploadField()" {{ old('is_mitra') ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-black text-ink">Ajukan upgrade ke Mitra Teknisi</span>
                            <span class="mt-1 block text-xs font-semibold leading-5 text-slate-500">Centang bila Anda teknisi/toko servis dan ingin mengajukan harga khusus.</span>
                        </span>
                    </label>

                    <div id="upload_bukti_area" class="{{ old('is_mitra') ? '' : 'hidden' }} mt-4 rounded-2xl border border-dashed border-primary/25 bg-white/70 p-4">
                        <label class="field-label">Foto toko / keterangan servis</label>
                        <input type="file" name="bukti_toko" accept="image/*" class="w-full text-sm font-semibold text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-primary-100 file:px-4 file:py-2 file:text-sm file:font-black file:text-primary hover:file:bg-primary-200">
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full px-5 py-3.5 text-sm">
                    Daftar Akun <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>
            </form>

            <div class="mt-7 rounded-2xl border border-slate-200/80 bg-white/60 p-4 text-center text-sm font-semibold text-slate-500">
                Sudah punya akun?
                <a href="{{ url('/login') }}" class="font-black text-primary hover:underline">Masuk di sini</a>
            </div>
        </section>
    </div>
</div>

<script>
    function toggleUploadField() {
        const checkbox = document.getElementById('is_mitra_checkbox');
        const uploadArea = document.getElementById('upload_bukti_area');
        if (!checkbox || !uploadArea) return;
        uploadArea.classList.toggle('hidden', !checkbox.checked);
    }
</script>
@endsection