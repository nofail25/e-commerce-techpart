@extends('layouts.app')
@section('title', 'Keranjang Belanja - TechPart')

@section('content')
<div class="page-shell py-8 sm:py-10">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="eyebrow">Keranjang</span>
            <h1 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Checkout Pesanan</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Periksa kembali detail belanjaan Anda dan pilih metode pembayaran yang paling nyaman.</p>
        </div>
        <a href="{{ url('/katalog') }}" class="btn-outline px-4 py-2.5 text-sm"><i data-lucide="arrow-left" class="h-4 w-4"></i> Katalog</a>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
            <div class="font-black">Checkout belum bisa diproses:</div>
            <ul class="mt-1 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_400px] lg:items-start">
        <section class="surface-card overflow-hidden">
            <div class="border-b border-slate-200/70 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="select-all" class="rounded-xl border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" checked>
                        <label for="select-all" class="text-sm font-black text-ink cursor-pointer">Pilih Semua</label>
                    </div>
                    <span id="summary-item-count" class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-600 ring-1 ring-slate-200">{{ $cartItems->sum('qty') }} item</span>
                </div>
            </div>

            <div class="divide-y divide-slate-200/70">
                @forelse($cartItems as $item)
                    @php
                        $hargaSatuan = $isMitra ? $item->product->priceMitra : $item->product->priceRetail;
                        $totalHargaItem = $hargaSatuan * $item->qty;
                    @endphp
                    <article class="grid grid-cols-[auto_80px_1fr] gap-x-4 gap-y-3 p-4 sm:grid-cols-[auto_96px_1fr_auto] sm:gap-6 sm:p-6 sm:items-center">
                        <div class="flex items-center justify-center">
                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" form="checkout-form"
                                   data-price="{{ $hargaSatuan }}" data-qty="{{ $item->qty }}" 
                                   class="cart-item-checkbox rounded-xl border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" 
                                   checked>
                        </div>
                        <div class="h-20 w-20 sm:h-24 sm:w-24 overflow-hidden rounded-3xl bg-slate-100 ring-1 ring-slate-200/70">
                            @if($item->product->image)
                                <img src="{{ Storage::disk('public')->url($item->product->image) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="grid h-full w-full place-items-center text-slate-300"><i data-lucide="package" class="h-9 w-9"></i></div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-black leading-snug text-ink text-sm sm:text-base">{{ $item->product->name }}</h3>
                            <p class="mt-1 text-xs sm:text-sm font-semibold text-slate-500">{{ $item->product->brand }} • Stok {{ $item->product->stock }}</p>
                            <div class="mt-2 text-xs sm:text-sm font-black text-primary">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-span-3 flex flex-row items-center justify-between gap-4 sm:col-span-1 sm:flex-col sm:items-end border-t border-slate-100/80 pt-3 sm:border-t-0 sm:pt-0">
                            <div class="text-right text-lg font-black tracking-[-0.03em] text-ink">Rp {{ number_format($totalHargaItem, 0, ',', '.') }}</div>
                            <div class="flex items-center gap-2">
                                <form action="{{ url('/keranjang/hapus/' . $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="grid h-9 w-9 place-items-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100" title="Hapus">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </form>
                                <form action="{{ url('/keranjang/update/' . $item->id) }}" method="POST" class="flex h-9 overflow-hidden rounded-xl border border-slate-200 bg-white">
                                    @csrf
                                    <button type="submit" name="action" value="minus" class="grid w-9 place-items-center text-slate-600 hover:bg-slate-50"><i data-lucide="minus" class="h-3.5 w-3.5"></i></button>
                                    <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->product->stock }}" readonly class="w-11 border-x border-slate-200 text-center text-sm font-black outline-none">
                                    <button type="submit" name="action" value="plus" class="grid w-9 place-items-center text-slate-600 hover:bg-slate-50"><i data-lucide="plus" class="h-3.5 w-3.5"></i></button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="p-12 text-center">
                        <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-slate-300"><i data-lucide="shopping-bag" class="h-9 w-9"></i></div>
                        <h2 class="mt-4 text-xl font-black text-ink">Keranjang kosong</h2>
                        <p class="mt-2 text-sm text-slate-500">Tambahkan produk dari katalog untuk mulai checkout.</p>
                        <a href="{{ url('/katalog') }}" class="btn-primary mt-6 px-5 py-3 text-sm">Mulai Belanja</a>
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="surface-card p-5 sm:p-6 lg:sticky lg:top-24">
            <form id="checkout-form" action="{{ url('/checkout') }}" method="POST">
                @csrf
                <span class="eyebrow">Ringkasan</span>
                <div class="mt-5 space-y-3 border-b border-slate-200 pb-5 text-sm">
                    <div class="flex justify-between gap-4"><span class="font-semibold text-slate-500">Subtotal</span><span id="summary-subtotal" class="font-black text-ink">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    @if($isMitra)
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-3 text-xs font-bold leading-5 text-emerald-700">Harga mitra aktif untuk akun Anda.</div>
                    @endif
                </div>
                <div class="mt-5 flex items-end justify-between gap-4">
                    <span class="font-black text-ink">Total</span>
                    <span id="summary-total" class="text-2xl font-black tracking-[-0.05em] text-primary">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</span>
                </div>

                <button type="submit" id="btn-checkout" class="btn-primary mt-7 w-full px-5 py-4 text-sm" {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                    Buat Pesanan <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>
            </form>
        </aside>
    </div>
</div>

<script>
    function toggleNewAddressForm() {
        const newRadio = document.getElementById('radio_new_address');
        const form = document.getElementById('form_new_address');
        if (!newRadio || !form) return;
        form.style.display = newRadio.checked ? 'block' : 'none';
    }

    function updateTotals() {
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        const checkedBoxes = document.querySelectorAll('.cart-item-checkbox:checked');
        const selectAllCheckbox = document.getElementById('select-all');
        const btnCheckout = document.getElementById('btn-checkout');
        const itemCountBadge = document.getElementById('summary-item-count');

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkboxes.length > 0 && checkedBoxes.length === checkboxes.length;
        }

        let subtotal = 0;
        let totalQty = 0;

        checkedBoxes.forEach(box => {
            const price = parseFloat(box.dataset.price);
            const qty = parseInt(box.dataset.qty);
            subtotal += price * qty;
            totalQty += qty;
        });

        const total = subtotal;

        const formatRupiah = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));

        const subtotalEl = document.getElementById('summary-subtotal');
        const totalEl = document.getElementById('summary-total');

        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);
        if (totalEl) totalEl.textContent = formatRupiah(total);
        if (itemCountBadge) itemCountBadge.textContent = `${totalQty} item`;

        if (btnCheckout) {
            if (checkedBoxes.length === 0) {
                btnCheckout.disabled = true;
                btnCheckout.classList.add('opacity-50', 'cursor-not-allowed');
                btnCheckout.innerHTML = 'Pilih produk dahulu <i data-lucide="arrow-right" class="h-4 w-4"></i>';
            } else {
                btnCheckout.disabled = false;
                btnCheckout.classList.remove('opacity-50', 'cursor-not-allowed');
                btnCheckout.innerHTML = 'Buat Pesanan <i data-lucide="arrow-right" class="h-4 w-4"></i>';
            }
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleNewAddressForm();

        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        const selectAllCheckbox = document.getElementById('select-all');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', () => {
                checkboxes.forEach(box => {
                    box.checked = selectAllCheckbox.checked;
                });
                updateTotals();
            });
        }

        checkboxes.forEach(box => {
            box.addEventListener('change', updateTotals);
        });

        updateTotals();
    });
</script>
@endsection