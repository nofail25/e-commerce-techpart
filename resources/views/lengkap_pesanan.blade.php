@extends('layouts.app')
@section('title', 'Lengkapi Pesanan - TechPart')

@section('content')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="page-shell py-8 sm:py-10">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="eyebrow">Checkout</span>
            <h1 class="mt-3 text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">Lengkapi Pesanan</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Pilih alamat pengiriman dan metode pembayaran untuk pesanan Anda.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
            <div class="font-black">Gagal menyimpan detail pesanan:</div>
            <ul class="mt-1 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_400px] lg:items-start">
        <section class="surface-card p-5 sm:p-6">
            <form id="lengkap-form" action="{{ url('/pesanan/'.$order->id.'/lengkap') }}" method="POST">
                @csrf
                
                <div>
                    <label class="field-label">Alamat pengiriman</label>
                    <div class="space-y-3 mt-3">
                        @foreach($addresses as $addr)
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white/70 p-3 transition hover:border-primary/30">
                                <input type="radio" name="address_option" value="{{ $addr->id }}" class="mt-1 h-4 w-4 text-primary focus:ring-primary" required onchange="toggleNewAddressForm()">
                                <span><span class="block text-sm font-black text-ink">{{ $addr->label }}</span><span class="mt-1 block text-xs font-semibold leading-5 text-slate-500">{{ $addr->address }}</span></span>
                            </label>
                        @endforeach
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-dashed border-primary/30 bg-primary-50/70 p-3">
                            <input type="radio" name="address_option" value="new" id="radio_new_address" class="h-4 w-4 text-primary focus:ring-primary" required onchange="toggleNewAddressForm()" {{ $addresses->count() == 0 ? 'checked' : '' }}>
                            <span class="text-sm font-black text-primary">Tambah alamat baru</span>
                        </label>
                    </div>

                    <div id="form_new_address" class="{{ $addresses->count() > 0 ? 'hidden' : 'block' }} mt-4 space-y-4 rounded-2xl border border-slate-200 bg-white/70 p-4">
                        <input type="text" name="new_label" placeholder="Label alamat (Cth: Rumah, Kantor)" class="input-modern px-4 py-3 text-sm font-semibold">
                        
                        <!-- Map integration -->
                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-500">Pilih Lokasi di Peta</label>
                                <button type="button" onclick="getCurrentLocation()" class="text-xs font-bold text-primary flex items-center gap-1 hover:text-blue-700">
                                    <i data-lucide="map-pin" class="h-3 w-3"></i> Gunakan Lokasi Saat Ini
                                </button>
                            </div>
                            <div id="map" class="h-48 w-full rounded-xl border border-slate-200 mb-3 z-0 relative" style="z-index: 10;"></div>
                            <p class="text-[10px] text-slate-400 mb-1">Geser pin pada peta untuk mendapatkan alamat otomatis. Anda bisa menambahkan detail patokan di kolom bawah.</p>
                        </div>

                        <textarea id="address_input" name="new_address" rows="3" placeholder="Alamat lengkap akan terisi otomatis..." class="textarea-modern px-4 py-3 text-sm font-semibold"></textarea>
                        <input type="text" name="detail_address" placeholder="Detail patokan (Opsional. Cth: Rumah cat biru, sebelah warung...)" class="input-modern px-4 py-3 text-sm font-semibold">
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-200">
                    <label class="field-label">Metode pembayaran</label>
                    <div class="space-y-3 mt-3">
                        @foreach($paymentMethods as $key => $method)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white/70 p-3 transition hover:border-primary/30">
                                <input type="radio" name="payment_method" value="{{ $key }}" class="h-4 w-4 text-primary focus:ring-primary" {{ $loop->first ? 'checked' : '' }} required>
                                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-primary"><i data-lucide="{{ $method['icon'] }}" class="h-4 w-4"></i></span>
                                <span class="min-w-0 flex-1"><span class="block text-sm font-black text-ink">{{ $method['label'] }}</span><span class="block text-xs font-semibold text-slate-500">Admin Rp {{ number_format($method['fee'], 0, ',', '.') }}</span></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-10 w-full px-5 py-4 text-sm">
                    Lanjutkan Pembayaran <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>
            </form>
        </section>

        <aside class="surface-card overflow-hidden lg:sticky lg:top-24">
            <div class="border-b border-slate-200/70 p-5 sm:p-6">
                <h2 class="text-xl font-black text-ink">Ringkasan pesanan</h2>
                <p class="mt-1 text-sm font-semibold text-slate-500">{{ $order->details->sum('qty') }} item</p>
            </div>
            <div class="max-h-[360px] space-y-4 overflow-y-auto p-5 sm:p-6">
                @foreach($order->details as $detail)
                    <div class="flex gap-3">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200">
                            @if($detail->product && $detail->product->image)
                                <img src="{{ Storage::disk('public')->url($detail->product->image) }}" alt="{{ $detail->product->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="grid h-full w-full place-items-center text-slate-300"><i data-lucide="package" class="h-6 w-6"></i></div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="line-clamp-2 text-sm font-black text-ink">{{ $detail->product->name ?? 'Produk' }}</div>
                            <div class="mt-1 text-xs font-semibold text-slate-500">{{ $detail->qty }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-slate-200/70 p-5 sm:p-6">
                <div class="space-y-3 text-sm">
                    <div class="flex items-end justify-between border-slate-200"><span class="font-black text-ink">Total</span><span class="text-2xl font-black tracking-[-0.04em] text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    let map;
    let marker;

    function initMap() {
        if(map) {
            setTimeout(() => { map.invalidateSize(); }, 100);
            return;
        }
        
        // Default to Jakarta
        const defaultLocation = [-6.200000, 106.816666];
        
        map = L.map('map').setView(defaultLocation, 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        marker = L.marker(defaultLocation, {draggable: true}).addTo(map);

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            fetchAddress(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            fetchAddress(e.latlng.lat, e.latlng.lng);
        });

        setTimeout(() => { map.invalidateSize(); }, 300);
    }

    async function fetchAddress(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const data = await response.json();
            if (data && data.display_name) {
                document.getElementById('address_input').value = data.display_name;
            }
        } catch (error) {
            console.error('Error fetching address:', error);
        }
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i data-lucide="loader-2" class="h-3 w-3 animate-spin"></i> Mencari...`;
            if (window.lucide) window.lucide.createIcons();

            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                if(!map) initMap();
                
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                fetchAddress(lat, lng);
                
                btn.innerHTML = originalHtml;
                if (window.lucide) window.lucide.createIcons();
            }, function(error) {
                btn.innerHTML = originalHtml;
                if (window.lucide) window.lucide.createIcons();
                alert('Tidak dapat mengambil lokasi. Pastikan izin lokasi diberikan pada browser Anda.');
            });
        } else {
            alert('Browser Anda tidak mendukung fitur ini.');
        }
    }

    function toggleNewAddressForm() {
        const newRadio = document.getElementById('radio_new_address');
        const form = document.getElementById('form_new_address');
        if (!newRadio || !form) return;
        
        if (newRadio.checked) {
            form.style.display = 'block';
            setTimeout(() => { initMap(); }, 100);
        } else {
            form.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleNewAddressForm();
    });
</script>
@endsection
