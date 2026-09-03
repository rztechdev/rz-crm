<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Maintenance {{ $invoiceNumber }} - {{ $lead?->nama_usaha }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo_rz_teks.jpeg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: black !important; }
            .invoice-box { box-shadow: none !important; border: none !important; margin: 0 !important; max-width: 100% !important; padding: 0 !important; }
            @page { size: A4; margin: 15mm; }
        }
    </style>
</head>
<body class="bg-zinc-100 text-zinc-800 antialiased p-4 sm:p-8 min-h-screen flex flex-col items-center">

    <!-- Top Action Bar (No Print) -->
    <div class="no-print w-full max-w-4xl mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-xl shadow-xs border border-zinc-200">
        <a href="{{ route('maintenance.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-600 hover:text-emerald-600 transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Data Maintenance</span>
        </a>
        <div class="flex flex-wrap items-center gap-2">
            @php
                $waShareText = rawurlencode("Halo Kak {$lead?->nama_kontak},\n\nBerikut Invoice langganan Pemeliharaan & Maintenance Website untuk *{$lead?->nama_usaha}*:\nNomor: {$invoiceNumber}\nBiaya: Rp " . number_format($subscription->harga_bulanan, 0, ',', '.') . " / Bulan\nJatuh Tempo: {$dueDate}\n\nTerima kasih! 🙏\n- RZ Digital Creative");
                $waPhone = preg_replace('/[^0-9]/', '', $lead?->kontak_wa ?? '');
            @endphp
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waShareText }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">chat</span>
                <span>Kirim WhatsApp</span>
            </a>
            <a href="{{ route('invoices.maintenance', ['subscription' => $subscription, 'format' => 'pdf']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                <span>Download PDF</span>
            </a>
            <a href="{{ route('invoices.maintenance', ['subscription' => $subscription, 'format' => 'word']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">description</span>
                <span>Download Word</span>
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">print</span>
                <span>Cetak</span>
            </button>
        </div>
    </div>

    <!-- Invoice Document Container -->
    <div class="invoice-box bg-white w-full max-w-4xl p-8 sm:p-12 rounded-2xl shadow-md border border-zinc-200 relative">
        
        <!-- Header: Logo & Company Info -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b-2 border-emerald-600 pb-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo_rz_teks.jpeg') }}" alt="RZ Digital Creative" class="h-12 w-auto object-contain rounded-lg">
                    <div>
                        <h1 class="text-xl font-black text-zinc-900 tracking-tight">RZ DIGITAL CREATIVE</h1>
                        <p class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Website Maintenance &amp; Cloud Support</p>
                    </div>
                </div>
                <div class="text-xs text-zinc-500 space-y-0.5 pt-1">
                    <p>Website: <span class="font-semibold text-zinc-700">rzdigital.id</span> | Email: <span class="font-semibold text-zinc-700">halo@rzdigital.id</span></p>
                    <p>WhatsApp Support: <span class="font-semibold text-zinc-700">0812-3456-7890</span></p>
                </div>
            </div>

            <div class="sm:text-right space-y-1">
                <div class="inline-block px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 font-extrabold text-sm tracking-wider uppercase">
                    TAGIHAN MAINTENANCE
                </div>
                <p class="font-mono text-sm font-bold text-zinc-900 pt-1">{{ $invoiceNumber }}</p>
                <p class="text-xs text-zinc-500">Tanggal: <span class="font-semibold text-zinc-700">{{ $invoiceDate }}</span></p>
                <p class="text-xs text-zinc-500">Jatuh Tempo: <span class="font-semibold text-zinc-700">{{ $dueDate }}</span></p>
            </div>
        </div>

        <!-- Client & Maintenance Info Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 my-8 p-5 bg-zinc-50 rounded-xl border border-zinc-100">
            <div>
                <p class="text-[10px] font-bold font-mono text-zinc-400 uppercase tracking-wider">Pelanggan (Klien):</p>
                <h3 class="text-base font-extrabold text-zinc-900 mt-1">{{ $lead?->nama_usaha ?? 'Klien Terhormat' }}</h3>
                <p class="text-xs text-zinc-600 mt-0.5 font-medium">Kontak: {{ $lead?->nama_kontak ?? '-' }}</p>
                <p class="text-xs text-zinc-500 font-mono mt-0.5">WhatsApp: {{ $lead?->kontak_wa ?? '-' }}</p>
            </div>
            <div class="sm:text-right">
                <p class="text-[10px] font-bold font-mono text-zinc-400 uppercase tracking-wider">Website Terkait:</p>
                <h4 class="text-sm font-extrabold text-zinc-900 mt-1">{{ $project?->nama_project ?? 'Website ' . $lead?->nama_usaha }}</h4>
                <p class="text-xs text-zinc-600 mt-0.5 font-mono">{{ $project?->link_website ?? '-' }}</p>
                <p class="text-xs text-zinc-500 mt-0.5">Siklus: <span class="font-bold text-emerald-700">Bulanan (Recurring)</span></p>
            </div>
        </div>

        <!-- Table Items -->
        <div class="overflow-x-auto my-6">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-zinc-200 text-[11px] font-bold font-mono text-zinc-500 uppercase tracking-wider bg-zinc-50">
                        <th class="py-3 px-4">No.</th>
                        <th class="py-3 px-4">Rincian Layanan Pemeliharaan</th>
                        <th class="py-3 px-4 text-center">Periode</th>
                        <th class="py-3 px-4 text-right">Biaya (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-zinc-800">
                    <tr>
                        <td class="py-4 px-4 font-mono">1</td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-zinc-900 text-sm">Paket Pemeliharaan Website &amp; Cloud Backup</p>
                            <p class="text-zinc-500 text-[11px] mt-0.5">• Auto Backup Database &amp; File Mingguan<br>• Monitoring Uptime &amp; Security Shield (SSL)<br>• Bantuan Update Konten / Banner &amp; Support Prioritas WA</p>
                        </td>
                        <td class="py-4 px-4 text-center font-mono">1 Bulan</td>
                        <td class="py-4 px-4 text-right font-mono font-bold">{{ number_format($subscription->harga_bulanan, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total Breakdown & Bank Info -->
        <div class="border-t border-zinc-200 pt-4 flex flex-col sm:flex-row justify-between items-start gap-6">
            <div class="w-full sm:w-1/2 space-y-3">
                <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200/60 space-y-1 text-xs">
                    <p class="font-bold text-emerald-950 uppercase tracking-wider text-[11px]">Rekening Resmi Pembayaran:</p>
                    <div class="font-mono text-zinc-800 space-y-0.5 pt-1">
                        <p>🏦 <span class="font-bold">BCA:</span> 8735-234-567 (a/n Ryan Zulkarnaen)</p>
                        <p>🏦 <span class="font-bold">Mandiri:</span> 131-00-1234567-8 (a/n Ryan Zulkarnaen)</p>
                    </div>
                </div>
            </div>

            <div class="w-full sm:w-5/12 space-y-2 text-xs">
                <div class="flex justify-between py-2 border-t-2 border-zinc-900 text-sm font-extrabold text-zinc-900">
                    <span>Total Tagihan:</span>
                    <span class="font-mono text-base text-emerald-700">Rp {{ number_format($subscription->harga_bulanan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures & Stamp -->
        <div class="mt-12 pt-8 border-t border-zinc-200 flex justify-between items-end text-xs">
            <div class="text-zinc-400 text-[10px] space-y-0.5">
                <p>Dokumen tagihan maintenance resmi RZ CRM.</p>
                <p>Hak Cipta &copy; {{ date('Y') }} RZ Digital Creative.</p>
            </div>
            
            <div class="text-center relative">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 opacity-20 pointer-events-none select-none">
                    <div class="w-24 h-24 rounded-full border-4 border-emerald-600 flex items-center justify-center font-black text-emerald-600 text-[10px] uppercase text-center rotate-[-15deg]">
                        RZ DIGITAL<br>MAINTENANCE
                    </div>
                </div>
                <p class="text-zinc-500 text-[11px] mb-8">Hormat Kami,<br><span class="font-bold text-zinc-900">RZ Digital Creative</span></p>
                <p class="font-bold text-zinc-900 border-b border-zinc-900 pb-0.5 inline-block">Ryan Zulkarnaen</p>
                <p class="text-[10px] text-zinc-500">Founder &amp; Project Lead</p>
            </div>
        </div>

    </div>

</body>
</html>
