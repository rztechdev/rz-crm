<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Payment;
use App\Models\MaintenanceSubscription;
use App\Models\MessageLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CrmDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or update Default Admin Owner
        $admin = User::updateOrCreate(
            ['email' => 'rzcompanyidn@gmail.com'],
            [
                'name' => 'Owner RZ Digital',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Sample Leads
        $lead1 = Lead::create([
            'nama_usaha' => 'Kopi Kenangan Senja',
            'nama_kontak' => 'Budi Santoso',
            'kontak_wa' => '081234567891',
            'sumber' => 'warm_network',
            'status' => 'belum_dihubungi',
            'paket_diminati' => 'landing_page',
            'catatan' => 'Teman komunitas UMKM, baru buka cabang kopi kedua.',
            'follow_up_date' => Carbon::now()->addDays(2),
        ]);

        $lead2 = Lead::create([
            'nama_usaha' => 'Batik Kencana Jaya',
            'nama_kontak' => 'Ibu Siti Rahma',
            'kontak_wa' => '081398765432',
            'sumber' => 'referral',
            'status' => 'sudah_chat',
            'paket_diminati' => 'company_profile',
            'catatan' => 'Tertarik paket company profile untuk katalog batik ekspor.',
            'follow_up_date' => Carbon::now()->subDays(2), // Overdue follow-up alert test
        ]);

        $lead3 = Lead::create([
            'nama_usaha' => 'Bengkel Motor Berkah',
            'nama_kontak' => 'Pak Hendra',
            'kontak_wa' => '085712345678',
            'sumber' => 'cold_outreach',
            'status' => 'nego',
            'paket_diminati' => 'toko_kasir',
            'catatan' => 'Lagi minta diskon paket POS Kasir + Website bengkel.',
            'follow_up_date' => Carbon::now(), // Today follow-up
        ]);

        $lead4 = Lead::create([
            'nama_usaha' => 'Laundry Express Bersih',
            'nama_kontak' => 'Rina Kartika',
            'kontak_wa' => '082188776655',
            'sumber' => 'marketplace',
            'status' => 'tidak_lanjut',
            'paket_diminati' => 'landing_page',
            'catatan' => 'Budget belum mencukupi saat ini, follow up 3 bulan lagi.',
            'follow_up_date' => null,
        ]);

        $lead5 = Lead::create([
            'nama_usaha' => 'Klinik Gigi drg. Anita',
            'nama_kontak' => 'drg. Anita Wijaya',
            'kontak_wa' => '081299887766',
            'sumber' => 'referral',
            'status' => 'deal',
            'paket_diminati' => 'company_profile',
            'catatan' => 'Deal paket Company Profile + Sistem Booking Konsultasi.',
            'follow_up_date' => null,
        ]);

        $lead6 = Lead::create([
            'nama_usaha' => 'Resto Seafood Bahari 99',
            'nama_kontak' => 'Koh Apin',
            'kontak_wa' => '081122334455',
            'sumber' => 'komunitas',
            'status' => 'deal',
            'paket_diminati' => 'toko_kasir',
            'catatan' => 'Deal paket Toko & Kasir POS meja restoran.',
            'follow_up_date' => null,
        ]);

        $lead7 = Lead::create([
            'nama_usaha' => 'Studio Foto Cahaya',
            'nama_kontak' => 'Dimas Pratama',
            'kontak_wa' => '087811223344',
            'sumber' => 'website',
            'status' => 'deal',
            'paket_diminati' => 'landing_page',
            'catatan' => 'Landing page portfolio photoshoot & booking.',
            'follow_up_date' => null,
        ]);

        // 3. Projects for Deal Leads
        $project1 = Project::create([
            'lead_id' => $lead5->id,
            'nama_project' => 'Website Profil & Jadwal drg. Anita',
            'paket' => 'company_profile',
            'harga' => 999000,
            'status' => 'dikerjakan',
            'tanggal_mulai' => Carbon::now()->subDays(5),
            'tanggal_selesai' => Carbon::now()->addDays(5),
            'link_website' => 'https://klinikdrganita.com',
            'catatan' => 'Proses desain halaman jadwal praktek dokter.',
        ]);

        Payment::create([
            'project_id' => $project1->id,
            'jenis' => 'dp',
            'jumlah' => 500000,
            'status' => 'lunas',
            'tanggal' => Carbon::now()->subDays(5),
            'catatan' => 'DP 50% via BCA',
        ]);

        $project2 = Project::create([
            'lead_id' => $lead6->id,
            'nama_project' => 'Sistem Kasir POS & Menu Seafood Bahari 99',
            'paket' => 'toko_kasir',
            'harga' => 1500000,
            'status' => 'selesai',
            'tanggal_mulai' => Carbon::now()->subDays(20),
            'tanggal_selesai' => Carbon::now()->subDays(2),
            'link_website' => 'https://seafoodbahari99.com',
            'catatan' => 'Project telah live dan serah terima akun POS kasir.',
        ]);

        Payment::create([
            'project_id' => $project2->id,
            'jenis' => 'dp',
            'jumlah' => 750000,
            'status' => 'lunas',
            'tanggal' => Carbon::now()->subDays(20),
            'catatan' => 'DP 50% via Mandiri',
        ]);

        Payment::create([
            'project_id' => $project2->id,
            'jenis' => 'pelunasan',
            'jumlah' => 750000,
            'status' => 'lunas',
            'tanggal' => Carbon::now()->subDays(2),
            'catatan' => 'Pelunasan 50% setelah website live',
        ]);

        $project3 = Project::create([
            'lead_id' => $lead7->id,
            'nama_project' => 'Landing Page Studio Foto Cahaya',
            'paket' => 'landing_page',
            'harga' => 499000,
            'status' => 'dp_diterima',
            'tanggal_mulai' => Carbon::now()->subDay(),
            'tanggal_selesai' => Carbon::now()->addDays(6),
            'link_website' => 'https://studiocahaya.id',
            'catatan' => 'DP baru masuk kemarin malam.',
        ]);

        Payment::create([
            'project_id' => $project3->id,
            'jenis' => 'dp',
            'jumlah' => 250000,
            'status' => 'lunas',
            'tanggal' => Carbon::now()->subDay(),
            'catatan' => 'DP 50% via BCA',
        ]);

        // 4. Maintenance Subscriptions
        MaintenanceSubscription::create([
            'lead_id' => $lead6->id,
            'project_id' => $project2->id,
            'harga_bulanan' => 150000,
            'status' => 'aktif',
            'tanggal_mulai' => Carbon::now()->subDays(2),
            'tanggal_jatuh_tempo_berikutnya' => Carbon::now()->addDays(2), // H-2 for testing reminder
            'terakhir_diingatkan_at' => null,
            'catatan' => 'Langganan maintenance backup & update menu bulanan.',
        ]);

        // 5. Message Logs (Outgoing & Incoming)
        MessageLog::create([
            'lead_id' => $lead5->id,
            'kontak_wa' => '6281299887766',
            'arah' => 'keluar',
            'tipe_pesan' => 'invoice_dp',
            'isi_pesan' => "Halo Kak drg. Anita Wijaya, DP untuk project *Company Profile* (Website Profil & Jadwal drg. Anita) sudah kami terima dengan baik. 🙏\n\nProject langsung kami masukkan ke antrean pengerjaan ya. Estimasi selesai sekitar 7-10 hari kerja.\n\nTerima kasih banyak!",
            'status_kirim' => 'sent',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        MessageLog::create([
            'lead_id' => $lead5->id,
            'kontak_wa' => '6281299887766',
            'arah' => 'masuk',
            'tipe_pesan' => 'webhook_masuk',
            'isi_pesan' => 'Sip Mas, nanti materi fotonya saya kirim lewat Google Drive ya. Makasih!',
            'status_kirim' => 'received',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        MessageLog::create([
            'lead_id' => $lead6->id,
            'kontak_wa' => '6281122334455',
            'arah' => 'keluar',
            'tipe_pesan' => 'project_selesai',
            'isi_pesan' => "Halo Kak Koh Apin! 🎉\n\nKabar gembira, website untuk *Sistem Kasir POS & Menu Seafood Bahari 99* sudah selesai dan LIVE!\n🌐 Link: https://seafoodbahari99.com\n\nInfo Maintenance: Ada paket pendampingan bulanan mulai Rp 150.000/bulan.",
            'status_kirim' => 'sent',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        MessageLog::create([
            'lead_id' => $lead6->id,
            'kontak_wa' => '6281122334455',
            'arah' => 'masuk',
            'tipe_pesan' => 'webhook_masuk',
            'isi_pesan' => 'Mantap Mas, hasilnya keren banget! Saya mau sekalian langganan maintenance ya biar aman.',
            'status_kirim' => 'received',
            'created_at' => Carbon::now()->subDays(2)->addHours(2),
        ]);
    }
}
