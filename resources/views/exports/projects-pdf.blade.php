<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Proyek - RZ Digital Creative</title>
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
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .badge-dikerjakan { background: #fef3c7; color: #92400e; }
        .badge-dp { background: #e0f2fe; color: #0369a1; }
        .badge-draft { background: #f4f4f5; color: #3f3f46; }
        .footer { margin-top: 30px; font-size: 9px; color: #a1a1aa; text-align: right; border-top: 1px solid #f4f4f5; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 0;">
                    <div class="title">RZ DIGITAL CREATIVE</div>
                    <div class="subtitle">Laporan Rekapitulasi Proyek Website &amp; Aplikasi</div>
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <div class="meta">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                    <div class="meta">Total Proyek: {{ $projects->count() }} Data</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Proyek</th>
                <th>Klien / Usaha</th>
                <th>Paket</th>
                <th>Nilai Proyek</th>
                <th>Terbayar</th>
                <th>Sisa Tagihan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $idx => $project)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $project->nama_project }}</strong>
                        @if($project->link_website)
                            <div style="font-size: 8.5px; color: #059669;">{{ $project->link_website }}</div>
                        @endif
                    </td>
                    <td>
                        {{ $project->lead?->nama_usaha ?? '-' }}<br>
                        <span style="font-size: 8.5px; color: #71717a;">{{ $project->lead?->kontak_wa }}</span>
                    </td>
                    <td>{{ $project->paket_label }}</td>
                    <td style="font-family: monospace;">Rp {{ number_format($project->harga, 0, ',', '.') }}</td>
                    <td style="font-family: monospace; color: #059669;">Rp {{ number_format($project->total_terbayar, 0, ',', '.') }}</td>
                    <td style="font-family: monospace; color: {{ $project->sisa_tagihan > 0 ? '#e11d48' : '#71717a' }};">
                        Rp {{ number_format($project->sisa_tagihan, 0, ',', '.') }}
                    </td>
                    <td>
                        @php
                            $badgeClass = match($project->status) {
                                'selesai' => 'badge-selesai',
                                'dikerjakan' => 'badge-dikerjakan',
                                'dp_diterima' => 'badge-dp',
                                default => 'badge-draft'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $project->status_label }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #71717a;">Tidak ada data proyek.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara resmi dari RZ CRM &bull; {{ config('app.name', 'RZ Digital Creative') }}
    </div>
</body>
</html>
