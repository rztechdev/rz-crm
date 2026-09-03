<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi {{ $receiptNumber }} - {{ $project?->nama_project ?? 'Pembayaran' }}</title>
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
            .receipt-box { box-shadow: none !important; border: 2px solid #059669 !important; margin: 0 !important; max-width: 100% !important; padding: 24px !important; }
            @page { size: A4; margin: 15mm; }
        }
    </style>
</head>
<body class="bg-zinc-100 text-zinc-800 antialiased p-4 sm:p-8 min-h-screen flex flex-col items-center justify-center">

    <!-- Top Action Bar (No Print) -->
    <div class="no-print w-full max-w-3xl mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-xl shadow-xs border border-zinc-200">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-600 hover:text-emerald-600 transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Riwayat Pembayaran</span>
        </a>
        <div class="flex flex-wrap items-center gap-2">
            @php
                $waShareText = rawurlencode("Halo Kak {$lead?->nama_kontak},\n\nTerima kasih! Pembayaran {$payment->jenis_label} untuk project *{$project?->nama_project}* telah kami terima.\n\nNomor Kwitansi: {$receiptNumber}\nJumlah: Rp " . number_format($payment->jumlah, 0, ',', '.') . "\nStatus: LUNAS & TERVERIFIKASI\n\nSalam,\nRZ Digital Creative");
                $waPhone = preg_replace('/[^0-9]/', '', $lead?->kontak_wa ?? '');
            @endphp
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waShareText }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">chat</span>
                <span>Kirim WhatsApp</span>
            </a>
            <a href="{{ route('invoices.receipt', ['payment' => $payment, 'format' => 'pdf']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                <span>Download PDF</span>
            </a>
            <a href="{{ route('invoices.receipt', ['payment' => $payment, 'format' => 'word']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">description</span>
                <span>Download Word</span>
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">print</span>
                <span>Cetak</span>
            </button>
        </div>
    </div>

    <!-- Official Kwitansi Box -->
    <div class="receipt-box bg-white w-full max-w-3xl p-8 sm:p-10 rounded-2xl shadow-md border-2 border-emerald-600 relative overflow-hidden">
        
        <!-- Header: Logo & Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-emerald-600 pb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_rz_teks.jpeg') }}" alt="RZ Digital Creative" class="h-11 w-auto object-contain rounded-lg">
                <div>
                    <h1 class="text-lg font-black text-zinc-900 tracking-tight">RZ DIGITAL CREATIVE</h1>
                    <p class="text-[10px] font-semibold text-emerald-700 uppercase tracking-wider">Kwitansi Tanda Terima Resmi</p>
                </div>
            </div>

            <div class="sm:text-right">
                <div class="inline-block px-3 py-1 rounded-md bg-emerald-700 text-white font-extrabold text-xs tracking-wider uppercase">
                    KWITANSI RESMI
                </div>
                <p class="font-mono text-xs font-bold text-zinc-900 pt-1.5">No: {{ $receiptNumber }}</p>
                <p class="text-[11px] text-zinc-500">Tanggal: <span class="font-semibold text-zinc-700">{{ $receiptDate }}</span></p>
            </div>
        </div>

        <!-- Kwitansi Fields Table -->
        <div class="my-6 space-y-4 text-xs">
            
            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 border-b border-zinc-100 pb-3">
                <span class="w-44 text-zinc-500 font-semibold uppercase tracking-wider text-[11px]">Telah Diterima Dari :</span>
                <span class="flex-1 font-bold text-zinc-900 text-sm">{{ $lead?->nama_usaha ?? 'Klien Terhormat' }} ({{ $lead?->nama_kontak ?? '-' }})</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 border-b border-zinc-100 pb-3">
                <span class="w-44 text-zinc-500 font-semibold uppercase tracking-wider text-[11px]">Uang Sejumlah :</span>
                <span class="flex-1 font-extrabold text-emerald-800 bg-emerald-50 px-3 py-2 rounded-lg italic border border-emerald-200">
                    # {{ $terbilang }} #
                </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 border-b border-zinc-100 pb-3">
                <span class="w-44 text-zinc-500 font-semibold uppercase tracking-wider text-[11px]">Untuk Pembayaran :</span>
                <div class="flex-1 space-y-1">
                    <p class="font-bold text-zinc-900">Pembayaran {{ $payment->jenis_label }} - {{ $project?->nama_project ?? '-' }}</p>
                    @if($payment->catatan)
                        <p class="text-[11px] text-zinc-500 italic">{{ $payment->catatan }}</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Bottom: Amount & Signature -->
        <div class="mt-8 pt-4 flex flex-col sm:flex-row items-center justify-between gap-6">
            
            <!-- Amount Tag -->
            <div class="p-4 rounded-xl bg-zinc-900 text-white font-mono flex items-center gap-3 shadow-sm w-full sm:w-auto">
                <span class="text-xs font-sans text-zinc-400 font-bold uppercase tracking-wider">Jumlah:</span>
                <span class="text-xl font-extrabold text-emerald-400">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
            </div>

            <!-- Signature & Digital Stamp -->
            <div class="text-center relative sm:text-right pr-4">
                <div class="absolute -top-6 right-6 opacity-20 pointer-events-none select-none">
                    <div class="w-20 h-20 rounded-full border-4 border-emerald-600 flex items-center justify-center font-black text-emerald-600 text-[9px] uppercase text-center rotate-[-12deg]">
                        LUNAS<br>RZ DIGITAL
                    </div>
                </div>
                <p class="text-zinc-500 text-[11px] mb-8">Penerima,<br><span class="font-bold text-zinc-900">RZ Digital Creative</span></p>
                <p class="font-bold text-zinc-900 border-b border-zinc-900 pb-0.5 inline-block">Ryan Zulkarnaen</p>
                <p class="text-[10px] text-zinc-500">Finance &amp; Operations</p>
            </div>

        </div>

    </div>

</body>
</html>
