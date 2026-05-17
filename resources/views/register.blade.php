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

                <button type="submit" class="btn-primary w-full px-5 py-3.5 text-sm">
                    Daftar Akun <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-3 text-slate-400 font-semibold">atau</span>
                    </div>
                </div>

                <a href="{{ url('/auth/google/redirect') }}" class="w-full px-5 py-3.5 text-sm font-bold text-slate-600 bg-white border-2 border-slate-200 rounded-full flex items-center justify-center gap-2 hover:border-primary hover:text-primary transition-colors">
                    <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.377 4.657-5.717 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.963 3.037l5.657-5.657C34.392 6.053 29.57 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
                        <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 19.134 12 24 12c3.059 0 5.842 1.154 7.963 3.037l5.657-5.657C34.392 6.053 29.57 4 24 4c-7.938 0-14.812 4.686-18.194 11.482z"/>
                        <path fill="#4CAF50" d="M24 44c4.615 0 8.939-1.652 12.058-4.49l-5.817-5.817C28.438 35.777 26.3 36.5 24 36.5c-5.586 0-9.926-3.343-11.303-8l-6.571 4.819C8.88 39.314 15.753 44 24 44z"/>
                        <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.512 1.734-1.44 3.287-2.68 4.487l.024.024 6.571 4.819C40.109 36.007 44 30.479 44 24c0-1.341-.138-2.651-.389-3.917z"/>
                    </svg>
                    Daftar dengan Google
                </a>
            </form>

            <div class="mt-7 rounded-2xl border border-slate-200/80 bg-white/60 p-4 text-center text-sm font-semibold text-slate-500">
                Sudah punya akun?
                <a href="{{ url('/login') }}" class="font-black text-primary hover:underline">Masuk di sini</a>
            </div>
        </section>
    </div>
</div>

@endsection