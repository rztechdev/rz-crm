<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Pembayaran - RZ Digital Creative</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #18181b; font-size: 11px; margin: 20px; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 12px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; color: #09090b; }
        .subtitle { font-size: 11px; color: #71717a; margin-top: 2px; }
        .meta { font-size: 10px; color: #71717a; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10px; }
        th { background-color: #f4f4f5; color: #3f3f46; font-weight: bold; text-align: left; padding: 7px 6px; border-bottom: 1px solid #e4e4e7; font-size: 9px; text-transform: uppercase; }
        td { padding: 6px 6px; border-bottom: 1px solid #f4f4f5; vertical-align: top; }
        .badge { display: inline-block; padding: 2px 5px; font-size: 8px; font-weight: bold; border-radius: 4px; }
        .badge-lunas { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 30px; font-size: 9px; color: #a1a1aa; text-align: right; border-top: 1px solid #f4f4f5; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 0;">
                    <div class="title">RZ DIGITAL CREATIVE</div>
                    <div class="subtitle">Laporan Transaksi Pembayaran &amp; Invoicing</div>
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <div class="meta">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                    <div class="meta">Total Transaksi: {{ $payments->count() }} Data</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Proyek &amp; Klien</th>
                <th>Jenis Pembayaran</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal Transaksi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $idx => $payment)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $payment->project?->nama_project ?? '-' }}</strong><br>
                        <span style="font-size: 8.5px; color: #71717a;">{{ $payment->project?->lead?->nama_usaha ?? '-' }}</span>
                    </td>
                    <td>{{ $payment->jenis_label }}</td>
                    <td style="font-family: monospace; font-weight: bold; color: #059669;">
                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge {{ $payment->status === 'lunas' ? 'badge-lunas' : 'badge-pending' }}">
                            {{ $payment->status_label }}
                        </span>
                    </td>
                    <td>{{ $payment->tanggal ? $payment->tanggal->translatedFormat('d/m/Y') : '-' }}</td>
                    <td>{{ $payment->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #71717a;">Tidak ada data pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara resmi dari RZ CRM &bull; {{ config('app.name', 'RZ Digital Creative') }}
    </div>
</body>
</html>
