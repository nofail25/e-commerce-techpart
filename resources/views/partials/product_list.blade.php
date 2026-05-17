@forelse($products as $product)
    @php
        $avgRating = $product->reviews->avg('rating');
        $reviewCount = $product->reviews->count();
        $isMitra = Auth::check() && Auth::user()->role === 'mitra';
        $displayPrice = $isMitra ? $product->priceMitra : $product->priceRetail;
    @endphp

    <article class="product-card group flex h-full flex-col">
        <a href="{{ url('/produk/' . $product->id) }}" class="relative block aspect-[4/3] overflow-hidden bg-slate-100">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(37,99,235,.16),transparent_35%),linear-gradient(135deg,#f8fafc,#e2e8f0)]"></div>

            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="relative h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
            @else
                <div class="relative flex h-full w-full items-center justify-center text-slate-300">
                    <i data-lucide="cpu" class="h-16 w-16"></i>
                </div>
            @endif

            <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                <span class="rounded-full border border-white/50 bg-white/80 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-slate-700 backdrop-blur">{{ $product->category }}</span>
                <span class="rounded-full border border-white/50 bg-slate-900/80 px-2.5 py-1 text-[10px] font-black text-white backdrop-blur">Stok {{ $product->stock }}</span>
            </div>
        </a>

        <div class="flex flex-1 flex-col p-4 sm:p-5">
            <div class="mb-3 flex items-center justify-between gap-2 text-xs font-bold text-slate-400">
                <span class="truncate">{{ $product->brand }} {{ $product->series }}</span>
                <span class="inline-flex items-center gap-1 text-amber-500">
                    <i data-lucide="star" class="h-3.5 w-3.5 {{ $reviewCount > 0 ? 'fill-amber-400 text-amber-400' : 'text-slate-300' }}"></i>
                    <span class="text-slate-600">{{ $reviewCount > 0 ? number_format($avgRating, 1) : 'Baru' }}</span>
                </span>
            </div>

            <a href="{{ url('/produk/' . $product->id) }}" class="block">
                <h3 class="line-clamp-2 min-h-[2.75rem] text-sm font-extrabold leading-snug text-ink transition group-hover:text-primary sm:text-[15px]">{{ $product->name }}</h3>
            </a>

            <div class="mt-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    @if($isMitra)
                        <div class="text-[11px] font-bold text-slate-400 line-through">Rp {{ number_format($product->priceRetail, 0, ',', '.') }}</div>
                        <div class="text-lg font-black tracking-[-0.03em] text-ink">Rp {{ number_format($displayPrice, 0, ',', '.') }}</div>
                        <div class="mt-1 inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-[0.14em] text-emerald-700 ring-1 ring-emerald-100">Mitra</div>
                    @else
                        <div class="text-lg font-black tracking-[-0.03em] text-ink">Rp {{ number_format($displayPrice, 0, ',', '.') }}</div>
                    @endif
                </div>
            </div>

            <form onsubmit="tambahKeKeranjang(event, {{ $product->id }})" class="mt-5">
                @csrf
                <button type="submit" class="btn-dark w-full px-4 py-3 text-sm">
                    <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                    Keranjang
                </button>
            </form>
        </div>
    </article>
@empty
    <div class="col-span-full surface-card px-6 py-14 text-center">
        <div class="mx-auto mb-5 grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-slate-300">
            <i data-lucide="search-x" class="h-9 w-9"></i>
        </div>
        <h3 class="text-xl font-black text-ink">Produk tidak ditemukan</h3>
        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Coba gunakan kata kunci lain, reset filter, atau pilih kategori berbeda untuk menemukan suku cadang yang cocok.</p>
        <a href="{{ url('/katalog') }}" class="btn-primary mt-6 px-5 py-3 text-sm">Reset Filter</a>
    </div>
@endforelse