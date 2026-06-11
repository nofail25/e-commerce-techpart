@extends('layouts.app')
@section('title', 'Katalog Sparepart Gadget - TechPart')

@section('content')
    <section class="page-shell pt-10 sm:pt-14">
        <div class="mesh-card rounded-[2rem] px-5 py-8 text-white shadow-glow sm:px-8 lg:px-10 lg:py-12 animate-scale-in">
            <div class="relative z-10 grid gap-10 lg:grid-cols-[1.12fr_.88fr] lg:items-center">
                <div class="animate-slide-left">

                    <h1 class="mt-6 max-w-4xl text-4xl font-black leading-[1.02] tracking-[-0.06em] sm:text-5xl lg:text-7xl">
                        Sparepart gadget terlengkap, <span class="text-blue-200">pasti pas</span> untuk setiap kebutuhan.
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">Belanja LCD, baterai, konektor, hingga aksesoris kini makin praktis! Nikmati kemudahan mencari sparepart dalam hitungan detik berkat fitur pencarian pintar kami. Pas untuk customer, cepat untuk teknisi.</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <button onclick="document.getElementById('katalog-section').scrollIntoView({behavior: 'smooth'})" class="btn-primary px-6 py-3.5 text-sm">
                            Mulai Belanja <i data-lucide="arrow-down" class="h-4 w-4"></i>
                        </button>
                        <a href="{{ url('/pengembalian-barang') }}" class="btn-outline border-white/20 bg-white/10 px-6 py-3.5 text-sm text-white hover:border-white/30 hover:bg-white/20 hover:text-white">
                            Info Pengembalian
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 animate-slide-right">
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <i data-lucide="scan-search" class="h-7 w-7 text-blue-200"></i>
                        <div class="mt-6 text-3xl font-black tracking-[-0.05em]">Cari Sparepart Spesifik</div>
                        <p class="mt-2 text-sm leading-6 text-slate-300"> Berdasarkan brand, kategori, dan seri agar dijamin 100% cocok dengan perangkat Anda.</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur xl:mt-10">
                        <i data-lucide="badge-check" class="h-7 w-7 text-mint"></i>
                        <div class="mt-6 text-3xl font-black tracking-[-0.05em]">Produk Berkualitas</div>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Dapatkan produk berkualitas dengan stok terjamin, ulasan terpercaya, dan nikmati penawaran harga spesial khusus mitra!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kategori" class="page-shell mt-6 sm:mt-8 animate-fade-in-up" style="animation-delay: 150ms;">
        <div class="surface-card p-4 sm:p-5">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="eyebrow">Kategori</span>
                </div>
            </div>

            <div class="hide-scrollbar flex gap-4 overflow-x-auto pb-8 pt-6 px-2 snap-x">
                @php
                    $categories = [
                        ['label' => 'Semua', 'value' => '', 'icon' => 'layout-grid'],
                        ['label' => 'Layar LCD', 'value' => 'LCD Layar HP', 'icon' => 'smartphone'],
                        ['label' => 'Baterai Ori', 'value' => 'Baterai Ori', 'icon' => 'battery-charging'],
                        ['label' => 'Konektor', 'value' => 'Konektor', 'icon' => 'plug-2'],
                        ['label' => 'IC / Chipset', 'value' => 'IC / Chipset', 'icon' => 'cpu'],
                        ['label' => 'Kamera', 'value' => 'Kamera', 'icon' => 'camera'],
                        ['label' => 'Aksesoris', 'value' => 'Aksesoris', 'icon' => 'headphones'],
                        ['label' => 'Komponen Lain', 'value' => 'Komponen Lain', 'icon' => 'settings-2'],
                    ];
                @endphp

                @foreach($categories as $category)
                    @php $active = request('category', '') === $category['value']; @endphp
                    <a href="{{ $category['value'] ? url('/katalog?category=' . urlencode($category['value'])) : url('/katalog') }}"
                       data-category-link
                       data-category="{{ $category['value'] }}"
                       class="group relative flex min-w-[130px] flex-col items-center justify-center gap-4 overflow-hidden rounded-[2rem] border bg-white p-5 shadow-soft transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-primary/20 snap-start {{ $active ? 'border-primary ring-2 ring-primary ring-offset-2' : 'border-slate-100 hover:border-primary/30' }}">
                        
                        <!-- Animated background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
                        
                        <!-- Icon container -->
                        <span class="relative flex h-16 w-16 items-center justify-center rounded-2xl {{ $active ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-100 group-hover:text-primary group-hover:scale-110' }} transition-all duration-300">
                            <i data-lucide="{{ $category['icon'] }}" class="h-7 w-7"></i>
                        </span>
                        
                        <!-- Label -->
                        <span class="relative text-center text-sm font-black tracking-tight {{ $active ? 'text-primary' : 'text-slate-600 group-hover:text-primary' }}">{{ $category['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="katalog-section" class="page-shell py-8 sm:py-10">
        <div class="grid gap-6 lg:grid-cols-[320px_1fr] lg:items-start">
            <aside class="surface-card p-5 lg:sticky lg:top-24 animate-slide-left" style="animation-delay: 200ms;">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <span class="eyebrow">Filter</span>
                        <h3 class="mt-3 text-xl font-black tracking-[-0.04em] text-ink">Saring produk</h3>
                    </div>
                    <div id="loading_indicator" class="hidden items-center gap-2 rounded-full bg-primary-50 px-3 py-2 text-xs font-black text-primary ring-1 ring-primary-100">
                        <i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Memuat
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="search_input" class="field-label">Cari produk</label>
                        <div class="relative">
                            <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="search_input" placeholder="Nama produk / komponen" class="input-modern py-3 pl-10 pr-4 text-sm font-semibold" value="{{ request('search') }}">
                        </div>
                    </div>

                    <div>
                        <label for="brand_select" class="field-label">Merk gadget</label>
                        <div class="relative">
                            <i data-lucide="badge" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <select id="brand_select" class="select-modern appearance-none py-3 pl-10 pr-10 text-sm font-semibold">
                                <option value="">Semua merk</option>
                                @if(isset($brands))
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <i data-lucide="chevron-down" class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label for="series_select" class="field-label">Seri model</label>
                        <div class="relative">
                            <i data-lucide="smartphone" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <select id="series_select" class="select-modern appearance-none py-3 pl-10 pr-10 text-sm font-semibold disabled:cursor-not-allowed disabled:opacity-60" disabled>
                                <option value="">Pilih merk dulu</option>
                            </select>
                            <i data-lucide="chevron-down" class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="soft-card p-4">
                        <div class="flex gap-3">
                            <div class="grid h-9 w-9 shrink-0 place-items-center rounded-2xl bg-primary-50 text-primary"><i data-lucide="info" class="h-4 w-4"></i></div>
                            <p class="text-xs font-semibold leading-6 text-slate-500">Pilih brand dan seri yang tepat agar sparepart pas dan berfungsi maksimal di gadget kesayangan Anda!</p>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="animate-slide-right" style="animation-delay: 250ms;">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="eyebrow">Produk Terlaris</span>
                        <h2 id="katalog_title" class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink">Rekomendasi Spesial</h2>
                        <p id="katalog_desc" class="mt-2 text-sm leading-6 text-slate-500">Kurasi sparepart terbaik dan paling banyak dicari, khusus untuk Anda hari ini!</p>
                    </div>
                </div>

                <div id="product_container" class="grid min-h-[420px] grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @include('partials.product_list')
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brandSelect = document.getElementById('brand_select');
            const seriesSelect = document.getElementById('series_select');
            const searchInput = document.getElementById('search_input');
            const container = document.getElementById('product_container');
            const loadingIndicator = document.getElementById('loading_indicator');
            const categoryLinks = document.querySelectorAll('[data-category-link]');

            let fetchSeriesController = null;
            let fetchProductsController = null;
            let searchTimeout = null;
            let selectedCategory = @json(request('category', ''));
            const initialSeries = @json(request('series', ''));

            function updateCategoryActiveState() {
                categoryLinks.forEach((link) => {
                    const isActive = link.dataset.category === selectedCategory;
                    
                    // Element references
                    const iconWrapper = link.querySelector('span.rounded-2xl');
                    const textSpan = link.querySelector('span.text-center');

                    if (isActive) {
                        // Card
                        link.classList.remove('border-slate-100', 'hover:border-primary/30');
                        link.classList.add('border-primary', 'ring-2', 'ring-primary', 'ring-offset-2');
                        // Icon
                        iconWrapper.classList.remove('bg-slate-50', 'text-slate-400', 'group-hover:bg-primary-100', 'group-hover:text-primary', 'group-hover:scale-110');
                        iconWrapper.classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/30');
                        // Text
                        textSpan.classList.remove('text-slate-600', 'group-hover:text-primary');
                        textSpan.classList.add('text-primary');
                        
                        // Update dynamic heading text
                        const titleEl = document.getElementById('katalog_title');
                        const descEl = document.getElementById('katalog_desc');
                        if (titleEl && descEl) {
                            if (!selectedCategory) {
                                titleEl.textContent = 'Rekomendasi Spesial';
                                descEl.textContent = 'Kurasi sparepart terbaik dan paling banyak dicari, khusus untuk Anda hari ini!';
                            } else {
                                titleEl.textContent = `Pilihan ${selectedCategory}`;
                                descEl.textContent = `Temukan berbagai macam ${selectedCategory} berkualitas tinggi yang cocok untuk perangkat Anda.`;
                            }
                        }
                    } else {
                        // Card
                        link.classList.remove('border-primary', 'ring-2', 'ring-primary', 'ring-offset-2');
                        link.classList.add('border-slate-100', 'hover:border-primary/30');
                        // Icon
                        iconWrapper.classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/30');
                        iconWrapper.classList.add('bg-slate-50', 'text-slate-400', 'group-hover:bg-primary-100', 'group-hover:text-primary', 'group-hover:scale-110');
                        // Text
                        textSpan.classList.remove('text-primary');
                        textSpan.classList.add('text-slate-600', 'group-hover:text-primary');
                    }
                });
            }

            function buildFilterUrl() {
                const params = new URLSearchParams();
                const brand = brandSelect.value;
                const series = seriesSelect.value;
                const search = searchInput.value.trim();

                if (selectedCategory) params.set('category', selectedCategory);
                if (brand) params.set('brand', brand);
                if (series) params.set('series', series);
                if (search) params.set('search', search);

                const queryString = params.toString();
                return `/katalog${queryString ? `?${queryString}` : ''}`;
            }

            async function loadSeries(brand, selectedValue = '') {
                if (fetchSeriesController) fetchSeriesController.abort();

                if (!brand) {
                    seriesSelect.innerHTML = '<option value="">Pilih merk dulu</option>';
                    seriesSelect.disabled = true;
                    return;
                }

                seriesSelect.innerHTML = '<option value="">Memuat seri...</option>';
                seriesSelect.disabled = true;
                fetchSeriesController = new AbortController();

                try {
                    const response = await fetch(`/api/get-series?brand=${encodeURIComponent(brand)}`, { signal: fetchSeriesController.signal });
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();

                    let optionsHtml = '<option value="">Semua seri model</option>';
                    if (data.length > 0) {
                        data.forEach(series => {
                            const selected = selectedValue === series ? 'selected' : '';
                            optionsHtml += `<option value="${series}" ${selected}>${series}</option>`;
                        });
                        seriesSelect.innerHTML = optionsHtml;
                        seriesSelect.disabled = false;
                    } else {
                        seriesSelect.innerHTML = '<option value="">Seri tidak ditemukan</option>';
                    }
                } catch (error) {
                    if (error.name === 'AbortError') return;
                    console.error('Gagal memuat seri:', error);
                    seriesSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                }
            }

            async function fetchFilteredProducts() {
                if (fetchProductsController) fetchProductsController.abort();

                container.classList.add('is-filtering');
                loadingIndicator.classList.remove('hidden');
                loadingIndicator.classList.add('flex', 'animate-scale-in');

                fetchProductsController = new AbortController();
                const url = buildFilterUrl();
                window.history.replaceState({ category: selectedCategory }, '', url);

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        signal: fetchProductsController.signal
                    });
                    if (!response.ok) throw new Error('Gagal mengambil data produk');
                    container.innerHTML = await response.text();
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                } catch (error) {
                    if (error.name === 'AbortError') return;
                    console.error('Gagal filter produk:', error);
                    container.innerHTML = `<div class="col-span-full surface-card p-8 text-center text-rose-600"><strong>Gagal memuat produk.</strong><br><span class="text-sm text-slate-500">Silakan coba beberapa saat lagi.</span></div>`;
                } finally {
                    container.classList.remove('is-filtering');
                    loadingIndicator.classList.add('hidden');
                    loadingIndicator.classList.remove('flex', 'animate-scale-in');
                }
            }

            categoryLinks.forEach((link) => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    selectedCategory = this.dataset.category || '';
                    updateCategoryActiveState();
                    fetchFilteredProducts();
                });
            });

            brandSelect.addEventListener('change', async function() {
                await loadSeries(this.value);
                fetchFilteredProducts();
            });

            seriesSelect.addEventListener('change', fetchFilteredProducts);

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchFilteredProducts, 300);
            });

            if (brandSelect.value !== '') {
                loadSeries(brandSelect.value, initialSeries);
            }
            updateCategoryActiveState();
        });
    </script>
@endsection
