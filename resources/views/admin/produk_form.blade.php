@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk Baru')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('/admin/produk') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>
            <p class="text-slate-600 mt-1">{{ isset($product) ? 'Perbarui informasi produk' : 'Tambahkan produk baru ke katalog' }}</p>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ isset($product) ? url('/admin/produk/update/'.$product->id) : url('/admin/produk/simpan') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Section: Informasi Dasar -->
            <div class="p-6 sm:p-8 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="info" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    Informasi Dasar
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Produk <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ $product->name ?? old('name') }}" required class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="Contoh: LCD Layar iPhone 13">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="category" value="{{ $product->category ?? old('category') }}" placeholder="Contoh: LCD Layar Hp" required class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Section: Spesifikasi -->
            <div class="p-6 sm:p-8 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="settings" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    Spesifikasi Produk
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Merek <span class="text-rose-500">*</span></label>
                        <input type="text" name="brand" value="{{ $product->brand ?? old('brand') }}" required class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="Contoh: Apple">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Seri</label>
                        <input type="text" name="series" value="{{ $product->series ?? old('series') }}" class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="Contoh: Pro Max">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Model</label>
                        <input type="text" name="model" value="{{ $product->model ?? old('model') }}" class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="Contoh: A2338">
                    </div>
                </div>
            </div>

            <!-- Section: Harga & Stok -->
            <div class="p-6 sm:p-8 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="tag" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    Harga & Stok
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Retail (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="priceRetail" value="{{ $product->priceRetail ?? old('priceRetail') }}" required class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="0">
                        <p class="text-xs text-slate-500 mt-1">Harga untuk pelanggan umum</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-emerald-700 mb-2">Harga Mitra (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="priceMitra" value="{{ $product->priceMitra ?? old('priceMitra') }}" required class="w-full border border-emerald-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="0">
                        <p class="text-xs text-emerald-600 mt-1">Harga khusus untuk mitra</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Tersedia <span class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ $product->stock ?? old('stock') }}" required class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Berat (Gram) <span class="text-rose-500">*</span></label>
                        <input type="number" name="weight" value="{{ $product->weight ?? old('weight') }}" required class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Section: Deskripsi -->
            <div class="p-6 sm:p-8 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    Deskripsi & Detail
                </h2>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea name="description" rows="5" class="w-full border border-slate-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none" placeholder="Tuliskan spesifikasi lengkap, garansi, kompatibilitas, atau catatan penting tentang produk...">{{ old('description', $product->description ?? '') }}</textarea>
                    <p class="text-xs text-slate-500 mt-2">Gunakan Enter untuk memisahkan baris. Teks akan ditampilkan rapi di halaman detail produk.</p>
                </div>
            </div>

            <!-- Section: Foto Produk -->
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="image" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    Foto Produk
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Preview Foto Saat Ini -->
                    @if(isset($product) && $product->image)
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Foto Saat Ini</label>
                        <div class="relative">
                            <img src="{{ Storage::disk('public')->url($product->image) }}" class="w-full h-48 object-cover rounded-lg border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-500 mt-2">Upload foto baru untuk mengganti gambar ini</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Upload Area -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">{{ isset($product) && $product->image ? 'Ganti Foto' : 'Upload Foto Produk' }}</label>
                        <div class="relative">
                            <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
                            <label for="imageInput" class="block w-full p-6 border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 cursor-pointer hover:bg-slate-100 hover:border-primary-400 transition-all text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="cloud-upload-2" class="w-8 h-8 text-slate-400 mb-2"></i>
                                    <p class="text-sm font-medium text-slate-700">Klik atau drag foto di sini</p>
                                    <p class="text-xs text-slate-500 mt-1">JPG, PNG (Max 2MB)</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ url('/admin/produk') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all">
                {{ isset($product) ? 'Perbarui Produk' : 'Simpan Produk' }}
            </button>
        </div>
    </form>
</div>
@endsection