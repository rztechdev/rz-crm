<?php

namespace App\Services\WhatsApp;

use App\Models\Lead;
use App\Models\Project;
use App\Models\MaintenanceSubscription;

class WhatsAppTemplates
{
    /**
     * Template: Konfirmasi DP Diterima & Pengerjaan Dimulai.
     */
    public static function dpReceived(Lead $lead, Project $project, ?string $estimasiSelesai = null): string
    {
        $nama = $lead->nama_kontak ?: $lead->nama_usaha;
        $paket = $project->paket_label;
        $estimasi = $estimasiSelesai ?: ($project->tanggal_selesai ? $project->tanggal_selesai->translatedFormat('d F Y') : '7-14 hari kerja ke depan');

        return "Halo Kak {$nama}, DP untuk project *{$paket}* ({$project->nama_project}) sudah kami terima dengan baik. 🙏\n\n"
             . "Project langsung kami masukkan ke antrean pengerjaan ya. Estimasi selesai pengerjaan sekitar *{$estimasi}*.\n\n"
             . "Nanti setiap progres penting akan kami update berkala. Terima kasih banyak atas kepercayaannya bersama RZ Digital Creative! ✨";
    }

    /**
     * Template: Project Selesai & Live + Penawaran Maintenance Bulanan.
     */
    public static function projectCompleted(Lead $lead, Project $project, ?string $linkWebsite = null, ?int $hargaMaintenance = null): string
    {
        $nama = $lead->nama_kontak ?: $lead->nama_usaha;
        $link = $linkWebsite ?: ($project->link_website ?: 'https://' . strtolower(str_replace(' ', '', $lead->nama_usaha)) . '.com');
        $maintPrice = number_format($hargaMaintenance ?: config('flustra.default_maintenance_price', 150000), 0, ',', '.');

        return "Halo Kak {$nama}! 🎉\n\n"
             . "Kabar gembira, website untuk *{$project->nama_project}* sudah selesai dan *LIVE* bisa diakses publik!\n\n"
             . "🌐 Link Website: {$link}\n\n"
             . "Semoga websitenya berkontribusi menaikkan omzet dan kredibilitas usaha Kakak ya. 🚀\n\n"
             . "💡 *Info Pendampingan & Maintenance:*\n"
             . "Biar website tetap aman, cepat, dan kalau butuh update promo/banner tanpa ribet coding, kami ada paket pendampingan bulanan mulai *Rp {$maintPrice}/bulan*. Tinggal chat kami aja kalau mau diaktifkan ya Kak. Terima kasih banyak! 🙏";
    }

    /**
     * Template: Pengingat Tagihan Maintenance Bulanan (H-3).
     */
    public static function maintenanceReminder(Lead $lead, MaintenanceSubscription $subscription, ?string $bankInfo = null): string
    {
        $nama = $lead->nama_kontak ?: $lead->nama_usaha;
        $jumlah = number_format($subscription->harga_bulanan, 0, ',', '.');
        $jatuhTempo = $subscription->tanggal_jatuh_tempo_berikutnya ? $subscription->tanggal_jatuh_tempo_berikutnya->translatedFormat('d F Y') : 'akhir bulan ini';
        $infoBank = $bankInfo ?: config('flustra.bank_info', 'BCA 1234567890 a.n RZ Digital Creative');

        return "Halo Kak {$nama}, pengingat santai untuk tagihan layanan pemeliharaan & maintenance website bulan ini sebesar *Rp {$jumlah}* dengan jatuh tempo tanggal *{$jatuhTempo}*.\n\n"
             . "💳 Pembayaran dapat ditransfer ke:\n"
             . "{$infoBank}\n\n"
             . "Jika sudah transfer, mohon konfirmasi bukti transfernya ke nomor ini ya. Terima kasih banyak atas kerjasamanya! 🙏✨";
    }
}
