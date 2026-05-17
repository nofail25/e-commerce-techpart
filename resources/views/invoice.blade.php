<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        @media print { body { background: #fff !important; padding: 0 !important; } .invoice-card { box-shadow: none !important; border: none !important; } }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8" onload="window.print()">
    @php
        $invoiceStatus = $order->status === 'diproses' ? 'dikemas' : $order->status;
        $statusLabels = [
            'pending' => 'Menunggu Bayar',
            'dikemas' => 'Dikemas',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];
        $statusClasses = [
            'pending' => 'bg-amber-50 text-amber-700',
            'dikemas' => 'bg-blue-50 text-blue-700',
            'dikirim' => 'bg-indigo-50 text-indigo-700',
            'selesai' => 'bg-emerald-50 text-emerald-700',
            'dibatalkan' => 'bg-rose-50 text-rose-700',
        ];
    @endphp
    <main class="invoice-card mx-auto max-w-4xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-slate-200/70">
        <header class="bg-slate-950 p-8 text-white">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-200">Invoice</div>
                    <h1 class="mt-2 text-4xl font-black tracking-[-0.06em]">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
                </div>
                <div class="text-left sm:text-right">
                    <h2 class="text-xl font-black tracking-[-0.04em]">TechPart</h2>
                    <p class="mt-1 text-sm font-semibold text-slate-300">Suku cadang gadget presisi</p>
                </div>
            </div>
        </header>

        <section class="grid gap-6 border-b border-slate-200 p-8 sm:grid-cols-2">
            <div>
                <div class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Ditagihkan kepada</div>
                <p class="mt-3 font-black text-slate-900">{{ $order->user->name }}</p>
                <p class="mt-1 max-w-sm text-sm font-semibold leading-6 text-slate-500">{{ $order->shipping_address }}</p>
            </div>
            <div class="sm:text-right">
                <div class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Tanggal transaksi</div>
                <p class="mt-3 text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}</p>
                <p class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-black {{ $statusClasses[$invoiceStatus] ?? 'bg-slate-100 text-slate-700' }}">{{ $statusLabels[$invoiceStatus] ?? strtoupper($invoiceStatus) }}</p>
            </div>
        </section>

        <section class="p-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-[0.14em] text-slate-500">
                        <tr>
                            <th class="px-4 py-4">Produk</th>
                            <th class="px-4 py-4 text-center">Qty</th>
                            <th class="px-4 py-4 text-right">Harga</th>
                            <th class="px-4 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->details as $detail)
                            <tr>
                                <td class="px-4 py-4 font-bold text-slate-800">{{ $detail->product->name }}</td>
                                <td class="px-4 py-4 text-center font-semibold text-slate-600">{{ $detail->qty }}</td>
                                <td class="px-4 py-4 text-right font-semibold text-slate-600">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right font-black text-slate-900">Rp {{ number_format($detail->qty * $detail->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <div class="w-full max-w-sm rounded-2xl bg-slate-50 p-5">
                    <div class="flex justify-between text-sm font-semibold text-slate-500">
                        <span>Total belanja</span>
                        <span class="font-black text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-4 text-xs font-semibold leading-5 text-slate-400">Harga di atas mengikuti total transaksi pada sistem dan sudah termasuk pajak yang berlaku bila diterapkan.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
