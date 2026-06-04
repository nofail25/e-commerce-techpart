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
                        <div class="mt-6 text-3xl font-black tracking-[-0.05em]">Pasti Bergaransi</div>
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
                    <h2 class="mt-3 text-2xl font-black tracking-[-0.04em] text-ink">Temukan Kebutuhan Anda</h2>
                </div>
                <p class="max-w-md text-sm leading-6 text-slate-500">Pilihan kategori ringkas yang dirancang khusus agar Anda bisa langsung belanja sparepart incaran tanpa ribet.</p>
            </div>

            <div class="hide-scrollbar flex gap-3 overflow-x-auto pb-2 md:grid md:grid-cols-5 md:overflow-visible md:pb-0">
                @php
                    $categories = [
                        ['label' => 'Semua', 'value' => '', 'icon' => 'layout-grid'],
                        ['label' => 'Layar LCD', 'value' => 'LCD Layar HP', 'icon' => 'smartphone'],
                        ['label' => 'Baterai Ori', 'value' => 'Baterai Ori', 'icon' => 'battery-charging'],
                        ['label' => 'Komponen Lain', 'value' => 'Komponen Lain', 'icon' => 'settings-2'],
                        ['label' => 'Aksesoris', 'value' => 'Aksesoris', 'icon' => 'headphones'],
                    ];
                @endphp

                @foreach($categories as $category)
                    @php $active = request('category', '') === $category['value']; @endphp
                    <a href="{{ $category['value'] ? url('/katalog?category=' . urlencode($category['value'])) : url('/katalog') }}"
                       data-category-link
                       data-category="{{ $category['value'] }}"
                       class="category-chip flex-none staggered-item-delayed {{ $active ? 'is-active' : '' }}"
                       style="--item-index: {{ $loop->index }}">
                        <span class="category-icon"><i data-lucide="{{ $category['icon'] }}" class="h-5 w-5"></i></span>
                        <span class="text-sm font-black">{{ $category['label'] }}</span>
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
                        <h2 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink">Rekomendasi Spesial</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Kurasi sparepart terbaik dan paling banyak dicari, khusus untuk Anda hari ini!</p>
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
                    link.classList.toggle('is-active', isActive);
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
