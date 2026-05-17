<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechPart - Suku Cadang Gadget Presisi')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        primary: {
                            DEFAULT: '#2563eb', 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a'
                        },
                        ink: '#0f172a', mint: '#14b8a6', accent: '#f97316'
                    },
                    boxShadow: {
                        soft: '0 22px 70px -42px rgba(15, 23, 42, .45)',
                        glow: '0 24px 80px -35px rgba(37, 99, 235, .55)'
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --ink: #0f172a; --muted: #64748b; --line: rgba(148, 163, 184, .24);
            --paper: rgba(255, 255, 255, .82); --primary: #2563eb; --canvas: #f8fafc;
        }
        * { scroll-behavior: smooth; }
        body { background: var(--canvas); font-feature-settings: "cv02", "cv03", "cv04", "cv11"; }
        body::before {
            content: ""; position: fixed; inset: 0; z-index: -2;
            background:
                radial-gradient(circle at 8% 6%, rgba(37, 99, 235, .14), transparent 26rem),
                radial-gradient(circle at 92% 12%, rgba(20, 184, 166, .13), transparent 24rem),
                radial-gradient(circle at 50% 100%, rgba(249, 115, 22, .08), transparent 28rem),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 44%, #f1f5f9 100%);
        }
        body::after {
            content: ""; position: fixed; inset: 0; z-index: -1; pointer-events: none; opacity: .28;
            background-image: linear-gradient(rgba(15, 23, 42, .05) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, .05) 1px, transparent 1px);
            background-size: 44px 44px; mask-image: linear-gradient(to bottom, #000 0%, transparent 70%);
        }
        ::selection { background: rgba(37, 99, 235, .18); color: var(--ink); }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .page-shell { width: min(100% - 2rem, 80rem); margin-inline: auto; }
        .surface-card { background: var(--paper); border: 1px solid var(--line); border-radius: 1.75rem; box-shadow: 0 20px 70px -46px rgba(15, 23, 42, .48); backdrop-filter: blur(18px); }
        .soft-card { background: rgba(255, 255, 255, .72); border: 1px solid var(--line); border-radius: 1.25rem; }
        .lift-card { transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
        .lift-card:hover { transform: translateY(-4px); box-shadow: 0 24px 70px -45px rgba(15, 23, 42, .5); border-color: rgba(37, 99, 235, .34); }
        .mesh-card { position: relative; overflow: hidden; background: radial-gradient(circle at top left, rgba(59, 130, 246, .28), transparent 18rem), radial-gradient(circle at bottom right, rgba(20, 184, 166, .18), transparent 20rem), linear-gradient(135deg, #0f172a 0%, #111827 48%, #172554 100%); }
        .mesh-card::after { content: ""; position: absolute; inset: 0; opacity: .12; background-image: linear-gradient(90deg, #fff 1px, transparent 1px), linear-gradient(#fff 1px, transparent 1px); background-size: 38px 38px; pointer-events: none; }
        .gradient-text { background: linear-gradient(100deg, #2563eb 0%, #14b8a6 55%, #f97316 105%); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .eyebrow { display: inline-flex; align-items: center; gap: .5rem; border: 1px solid rgba(37, 99, 235, .16); background: rgba(37, 99, 235, .08); color: #1d4ed8; border-radius: 999px; padding: .45rem .8rem; font-size: .72rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .btn-primary, .btn-dark, .btn-soft, .btn-outline { display: inline-flex; align-items: center; justify-content: center; gap: .55rem; border-radius: 1rem; font-weight: 800; transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease, color .2s ease; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; box-shadow: 0 18px 36px -22px rgba(37, 99, 235, .95); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 22px 48px -24px rgba(37, 99, 235, 1); }
        .btn-primary:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-dark { background: var(--ink); color: white; }
        .btn-dark:hover { background: #1e293b; transform: translateY(-2px); }
        .btn-soft { background: rgba(37, 99, 235, .08); color: #1d4ed8; border: 1px solid rgba(37, 99, 235, .14); }
        .btn-soft:hover { background: rgba(37, 99, 235, .12); transform: translateY(-2px); }
        .btn-outline { background: rgba(255,255,255,.72); color: #334155; border: 1px solid var(--line); }
        .btn-outline:hover { color: #1d4ed8; border-color: rgba(37, 99, 235, .28); transform: translateY(-2px); }
        .field-label { display: block; font-size: .78rem; font-weight: 800; color: #334155; margin-bottom: .55rem; }
        .input-modern, .select-modern, .textarea-modern { width: 100%; border: 1px solid rgba(148, 163, 184, .32); background: rgba(255, 255, 255, .76); color: #0f172a; border-radius: 1rem; outline: none; transition: border-color .2s ease, box-shadow .2s ease, background .2s ease; }
        .input-modern:focus, .select-modern:focus, .textarea-modern:focus { border-color: rgba(37, 99, 235, .7); box-shadow: 0 0 0 4px rgba(37, 99, 235, .1); background: white; }
        .status-dot { width: .55rem; height: .55rem; border-radius: 999px; display: inline-flex; }
        .category-chip { min-width: 8.5rem; display: flex; align-items: center; gap: .75rem; padding: .9rem; border-radius: 1.25rem; border: 1px solid rgba(148, 163, 184, .22); background: rgba(255, 255, 255, .68); color: #475569; transition: transform .2s ease, background .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease; }
        .category-chip:hover { transform: translateY(-2px); border-color: rgba(37, 99, 235, .25); color: #1d4ed8; }
        .category-chip.is-active { background: #0f172a; color: white; border-color: #0f172a; box-shadow: 0 22px 50px -34px rgba(15, 23, 42, .75); }
        .category-icon { width: 2.55rem; height: 2.55rem; border-radius: .95rem; display: inline-flex; align-items: center; justify-content: center; background: rgba(37, 99, 235, .08); color: #2563eb; flex-shrink: 0; }
        .category-chip.is-active .category-icon { background: rgba(255,255,255,.13); color: white; }
        .product-card { background: rgba(255, 255, 255, .82); border: 1px solid rgba(148, 163, 184, .22); border-radius: 1.45rem; overflow: hidden; box-shadow: 0 18px 65px -48px rgba(15, 23, 42, .52); transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease; }
        .product-card:hover { transform: translateY(-5px); border-color: rgba(37, 99, 235, .32); box-shadow: 0 26px 80px -48px rgba(37, 99, 235, .45); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .dropdown-transition { transition: opacity .2s ease, transform .2s ease, visibility .2s; }
        .line-clamp-2, .line-clamp-3 { display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { -webkit-line-clamp: 2; }
        .line-clamp-3 { -webkit-line-clamp: 3; }
        @media (max-width: 640px) { .page-shell { width: min(100% - 1rem, 80rem); } .surface-card { border-radius: 1.35rem; } }
        @media print { body { background: #fff !important; } body::before, body::after, header, footer, #toast-container { display: none !important; } }
    </style>
</head>
<body class="min-h-screen text-slate-800 font-sans antialiased flex flex-col">
    @php
        $isAuthPage = Request::is('login') || Request::is('register');
        $isFocusedPage = $isAuthPage || Request::is('keranjang') || Request::is('pesanan-saya') || Request::is('pembayaran/*') || Request::is('retur-saya') || Request::is('pesanan/*/retur');
    @endphp

    <header class="sticky top-0 z-40 border-b border-white/70 bg-white/80 backdrop-blur-2xl">
        <div class="page-shell">
            <div class="flex min-h-[72px] items-center justify-between gap-4 py-3">
                <a href="{{ url('/') }}" class="group flex items-center gap-3 shrink-0" aria-label="TechPart home">
                    <span class="relative grid h-11 w-11 place-items-center rounded-2xl bg-ink text-white shadow-soft overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-br from-primary via-blue-500 to-mint opacity-90"></span>
                        <i data-lucide="circuit-board" class="relative h-6 w-6"></i>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-lg font-black tracking-[-0.04em] text-ink">TechPart</span>
                        <span class="block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-400">Gadget Supply</span>
                    </span>
                </a>

                @if(!$isFocusedPage)
                    <nav class="hidden lg:flex items-center gap-1 rounded-full border border-slate-200/70 bg-white/60 p-1 text-sm font-bold text-slate-500">
                        <a href="{{ url('/katalog') }}" class="rounded-full px-4 py-2 transition hover:bg-slate-900 hover:text-white">Katalog</a>
                        <a href="{{ url('/pengembalian-barang') }}" class="rounded-full px-4 py-2 transition hover:bg-slate-900 hover:text-white">Pengembalian</a>
                        <a href="{{ url('/jadi-mitra') }}" class="rounded-full px-4 py-2 transition hover:bg-slate-900 hover:text-white">Jadi Mitra</a>
                        @auth
                            @if(Auth::user()->role !== 'admin')
                                <a href="{{ url('/pesanan-saya') }}" class="rounded-full px-4 py-2 transition hover:bg-slate-900 hover:text-white">Pesanan</a>
                            @endif
                        @endauth
                    </nav>

                    <form action="{{ url('/katalog') }}" method="GET" class="hidden md:flex flex-1 max-w-xl items-center gap-2 rounded-full border border-slate-200/80 bg-white/70 p-1.5 shadow-[0_18px_50px_-42px_rgba(15,23,42,.55)]">
                        <div class="pl-3 text-slate-400"><i data-lucide="search" class="h-5 w-5"></i></div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari LCD, baterai, konektor, IC..." class="min-w-0 flex-1 bg-transparent px-1 py-2 text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">
                        <button type="submit" class="btn-dark px-4 py-2 text-sm rounded-full">Cari</button>
                    </form>
                @endif

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    @if(!$isAuthPage)
                        <a href="{{ url('/keranjang') }}" class="relative grid h-11 w-11 place-items-center rounded-2xl border border-slate-200/80 bg-white/70 text-slate-600 transition hover:border-primary/30 hover:text-primary hover:shadow-soft" aria-label="Keranjang">
                            <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                            @auth
                                @php $cartTotal = \App\Models\Cart::where('user_id', Auth::id())->sum('qty'); @endphp
                                <span id="cart-badge" class="{{ $cartTotal > 0 ? '' : 'hidden' }} absolute -right-1 -top-1 inline-flex min-w-[21px] items-center justify-center rounded-full border-2 border-white bg-accent px-1.5 py-0.5 text-[10px] font-black text-white transition-transform">{{ $cartTotal }}</span>
                            @endauth
                        </a>
                    @endif

                    @guest
                        <div class="flex items-center gap-2">
                            <a href="{{ url('/login') }}" class="hidden sm:inline-flex rounded-full px-4 py-2 text-sm font-extrabold text-slate-600 transition hover:bg-slate-100 hover:text-ink">Masuk</a>
                            <a href="{{ url('/register') }}" class="btn-primary px-4 py-2.5 text-sm rounded-full">Daftar</a>
                        </div>
                    @endguest

                    @auth
                        <div class="relative" id="user-menu-container">
                            <button type="button" onclick="toggleDropdown()" class="flex items-center gap-2 rounded-2xl border border-slate-200/80 bg-white/70 p-1.5 pr-2.5 transition hover:border-primary/25 hover:shadow-soft focus:outline-none">
                                <span class="grid h-9 w-9 place-items-center rounded-xl bg-slate-900 text-sm font-black text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                <span class="hidden text-left sm:block">
                                    <span class="block max-w-[120px] truncate text-xs font-black text-ink">{{ Auth::user()->name }}</span>
                                    <span class="block text-[10px] font-extrabold uppercase tracking-[0.18em] text-primary">{{ Auth::user()->role }}</span>
                                </span>
                                <i data-lucide="chevron-down" class="hidden h-4 w-4 text-slate-400 sm:block"></i>
                            </button>

                            <div id="user-dropdown" class="dropdown-transition invisible absolute right-0 mt-3 w-64 origin-top-right scale-95 rounded-3xl border border-slate-200/80 bg-white/95 p-2 opacity-0 shadow-soft backdrop-blur-xl z-50">
                                <div class="px-4 py-3">
                                    <div class="text-sm font-black text-ink">{{ Auth::user()->name }}</div>
                                    <div class="mt-1 text-xs font-bold text-slate-400">{{ Auth::user()->email }}</div>
                                </div>
                                <div class="h-px bg-slate-100"></div>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ url('/admin/dashboard') }}" class="mt-2 flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-primary-50 hover:text-primary"><i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard Admin</a>
                                @else
                                    <a href="{{ url('/pesanan-saya') }}" class="mt-2 flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-primary-50 hover:text-primary"><i data-lucide="clipboard-list" class="h-4 w-4"></i> Pesanan Saya</a>
                                    <a href="{{ url('/retur-saya') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-primary-50 hover:text-primary"><i data-lucide="rotate-ccw" class="h-4 w-4"></i> Retur Saya</a>
                                    <a href="{{ url('/status-pengajuan-mitra') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-primary-50 hover:text-primary"><i data-lucide="badge-check" class="h-4 w-4"></i> Status Mitra</a>
                                @endif
                                <div class="my-2 h-px bg-slate-100"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-bold text-rose-600 transition hover:bg-rose-50"><i data-lucide="log-out" class="h-4 w-4"></i> Keluar Akun</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            @if(!$isFocusedPage)
                <form action="{{ url('/katalog') }}" method="GET" class="md:hidden mb-3 flex items-center gap-2 rounded-2xl border border-slate-200/80 bg-white/80 p-2">
                    <i data-lucide="search" class="ml-2 h-5 w-5 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sparepart..." class="min-w-0 flex-1 bg-transparent py-2 text-sm font-semibold outline-none placeholder:text-slate-400">
                    <button type="submit" class="btn-dark px-3 py-2 text-xs rounded-xl">Cari</button>
                </form>
            @endif
        </div>
    </header>

    <main class="flex-grow">@yield('content')</main>

    @if(!$isAuthPage)
        <footer class="mt-16 border-t border-white/70 bg-white/70 backdrop-blur-xl">
            <div class="page-shell py-10 sm:py-12">
                <div class="mesh-card rounded-[2rem] p-6 sm:p-8 text-white shadow-soft">
                    <div class="relative z-10 grid gap-8 lg:grid-cols-[1.15fr_.85fr] lg:items-end">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white/10 text-white ring-1 ring-white/20"><i data-lucide="circuit-board" class="h-6 w-6"></i></span>
                                <div><div class="text-xl font-black tracking-[-0.04em]">TechPart</div><div class="text-xs font-bold uppercase tracking-[0.22em] text-blue-100/75">Gadget Supply</div></div>
                            </div>
                            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-300">Belanja suku cadang gadget dengan pengalaman yang cepat, rapi, dan terarah. Dibuat untuk customer harian, teknisi, dan mitra yang butuh komponen presisi tanpa proses yang rumit.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                            <a href="{{ url('/katalog') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 font-bold text-white/90 transition hover:bg-white/20">Katalog</a>
                            <a href="{{ url('/pengembalian-barang') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 font-bold text-white/90 transition hover:bg-white/20">Pengembalian</a>
                            @guest
                                <a href="{{ url('/register') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 font-bold text-white/90 transition hover:bg-white/20">Daftar Mitra</a>
                            @else
                                <a href="{{ url('/pesanan-saya') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 font-bold text-white/90 transition hover:bg-white/20">Pesanan</a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex flex-col gap-4 text-sm text-slate-500 md:flex-row md:items-center md:justify-between">
                    <p>&copy; 2026 TechPart. Antarmuka modern untuk kebutuhan sparepart gadget.</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 font-bold text-slate-600 ring-1 ring-slate-200"><i data-lucide="shield-check" class="h-4 w-4 text-mint"></i> Garansi Produk</span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 font-bold text-slate-600 ring-1 ring-slate-200"><i data-lucide="truck" class="h-4 w-4 text-primary"></i> Siap Kirim</span>
                    </div>
                </div>
            </div>
        </footer>
    @endif

    <div id="toast-container" class="fixed right-3 top-20 z-50 flex w-full max-w-sm flex-col gap-3 px-3 pointer-events-none sm:right-6 sm:px-0"></div>

    <script>
        if (typeof lucide !== 'undefined') lucide.createIcons();

        function toggleDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            if (!dropdown) return;
            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.add('opacity-100', 'visible', 'scale-100');
            } else {
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            }
        }

        document.addEventListener('click', function(event) {
            const container = document.getElementById('user-menu-container');
            const dropdown = document.getElementById('user-dropdown');
            if (container && dropdown && !container.contains(event.target)) {
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            }
        });

        @if(session('success')) setTimeout(() => showToast({!! json_encode(session('success')) !!}, 'success'), 450); @endif
        @if(session('error')) setTimeout(() => showToast({!! json_encode(session('error')) !!}, 'error'), 450); @endif

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const isError = type === 'error';
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-start gap-3 rounded-2xl border bg-white/95 p-4 shadow-soft backdrop-blur-xl transition-all duration-300 translate-x-full opacity-0 ${isError ? 'border-rose-200 text-rose-700' : 'border-emerald-200 text-emerald-700'}`;
            toast.innerHTML = `
                <div class="grid h-8 w-8 shrink-0 place-items-center rounded-xl ${isError ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'}">
                    <i data-lucide="${isError ? 'alert-triangle' : 'check'}" class="h-4 w-4"></i>
                </div>
                <div>
                    <div class="text-sm font-black text-slate-900">${isError ? 'Perlu dicek' : 'Berhasil'}</div>
                    <div class="mt-0.5 text-sm font-semibold leading-snug ${isError ? 'text-rose-700' : 'text-slate-600'}">${message}</div>
                </div>`;
            container.appendChild(toast);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            setTimeout(() => toast.classList.remove('translate-x-full', 'opacity-0'), 20);
            setTimeout(() => { toast.classList.add('translate-x-full', 'opacity-0'); setTimeout(() => toast.remove(), 320); }, 3600);
        }

        async function tambahKeKeranjang(event, productId) {
            event.preventDefault();
            const form = event.target;
            const token = form.querySelector('input[name="_token"]')?.value;
            const submitBtn = form.querySelector('button[type="submit"]');
            let originalHtml = null;
            if (submitBtn) {
                originalHtml = submitBtn.innerHTML;
                submitBtn.innerHTML = `<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i><span>Menambahkan...</span>`;
                submitBtn.disabled = true;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
            try {
                const response = await fetch("{{ url('/keranjang/tambah') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: productId })
                });
                if (response.status === 401) { window.location.href = "{{ url('/login') }}"; return; }
                const data = await response.json();
                if (data.success) {
                    showToast(data.message || 'Produk masuk ke keranjang.');
                    const badge = document.getElementById('cart-badge');
                    if (badge) { badge.innerText = data.cartTotal; badge.classList.remove('hidden'); badge.classList.add('scale-150'); setTimeout(() => badge.classList.remove('scale-150'), 220); }
                } else {
                    showToast(data.message || 'Produk belum bisa ditambahkan.', 'error');
                }
            } catch (error) {
                console.error('Error Sistem:', error);
                showToast('Koneksi bermasalah. Silakan coba lagi.', 'error');
            } finally {
                if (submitBtn && originalHtml !== null) { submitBtn.innerHTML = originalHtml; submitBtn.disabled = false; if (typeof lucide !== 'undefined') lucide.createIcons(); }
            }
        }
    </script>
</body>
</html>