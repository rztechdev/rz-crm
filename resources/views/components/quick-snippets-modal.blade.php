<div x-data="{
    openSnippetsModal: false,
    activeTab: 'pricelist',
    copiedSnippet: null,
    copyToClipboard(text, id) {
        navigator.clipboard.writeText(text).then(() => {
            this.copiedSnippet = id;
            if (window.showToast) {
                window.showToast('Teks berhasil disalin ke clipboard!', 'success');
            }
            setTimeout(() => { this.copiedSnippet = null; }, 2000);
        });
    }
}"
@open-quick-snippets.window="openSnippetsModal = true">

    <!-- Modal Backdrop & Window -->
    <div x-show="openSnippetsModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
         style="display: none;">

        <div @click.away="openSnippetsModal = false" 
             class="bg-white dark:bg-zinc-900 w-full max-w-2xl rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-6 sm:p-8 space-y-6 max-h-[90vh] overflow-y-auto custom-scrollbar">
            
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[20px]">content_paste</span>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-zinc-900 dark:text-white text-lg">Template Balasan Cepat (Quick Snippets)</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Salin format chat resmi 1-klik untuk komunikasi instan dengan calon klien.</p>
                    </div>
                </div>
                <button @click="openSnippetsModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-white rounded-lg">
                    <span class="material-symbols-outlined text-[20px] block">close</span>
                </button>
            </div>

            <!-- Tab Buttons -->
            <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2 overflow-x-auto text-xs font-bold">
                <button @click="activeTab = 'pricelist'" :class="activeTab === 'pricelist' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200'" class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap">
                    💰 Pricelist &amp; Paket
                </button>
                <button @click="activeTab = 'brief'" :class="activeTab === 'brief' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200'" class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap">
                    📋 Form Brief Desain
                </button>
                <button @click="activeTab = 'rekening'" :class="activeTab === 'rekening' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200'" class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap">
                    🏦 Rekening &amp; DP
                </button>
                <button @click="activeTab = 'maintenance'" :class="activeTab === 'maintenance' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200'" class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap">
                    🛡️ Penawaran Maintenance
                </button>
            </div>

            <!-- TAB 1: PRICELIST -->
            <div x-show="activeTab === 'pricelist'" class="space-y-4">
                <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono text-xs text-zinc-800 dark:text-zinc-200 whitespace-pre-line relative group">
Halo Kak! Terima kasih sudah menghubungi *RZ Digital Creative* 🙏

Berikut pilihan paket pembuatan website profesional kami:

🚀 *1. Landing Page UMKM — Rp 499.000*
• 1 Halaman Sales/Promo Konversi Tinggi
• Desain Modern Responsif (Mobile & Desktop)
• Tombol Direct WhatsApp & Integrasi Maps
• Gratis Domain .my.id / .biz.id & Cloud Hosting 1 Tahun

💼 *2. Company Profile Bisnis — Rp 999.000*
• Hingga 5 Halaman Lengkap (Beranda, Profil, Layanan, Galeri, Kontak)
• SEO Basic Setup & Google Indexing
• Integrasi Form Kontak & Email Bisnis
• Gratis Domain .com & Cloud Hosting 1 Tahun

🛒 *3. Toko Online & Kasir POS — Rp 1.500.000*
• Manajemen Produk & Sistem Kasir POS Terpadu
• Fitur Cetak Struk Bluetooth & Laporan Penjualan
• Notifikasi WhatsApp Transaksi
• Pelatihan & Panduan Penggunaan Lengkap

Ada paket yang paling cocok dengan kebutuhan bisnis Kakak saat ini? 😊
                </div>
                <div class="flex justify-end">
                    <button @click="copyToClipboard($el.closest('.space-y-4').querySelector('.font-mono').innerText, 'pricelist')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]" x-text="copiedSnippet === 'pricelist' ? 'check' : 'content_copy'"></span>
                        <span x-text="copiedSnippet === 'pricelist' ? 'Tersalin!' : 'Salin Template Teks'"></span>
                    </button>
                </div>
            </div>

            <!-- TAB 2: BRIEF FORM -->
            <div x-show="activeTab === 'brief'" class="space-y-4" style="display: none;">
                <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono text-xs text-zinc-800 dark:text-zinc-200 whitespace-pre-line">
Halo Kak! Untuk mulai proses pengerjaan website, mohon bantu isi data singkat berikut ya:

*FORM BRIEF WEBSITE RZ DIGITAL CREATIVE*
1. Nama Usaha/Brand:
2. Bidang Usaha:
3. Paket Website:
4. Referensi Web yang Disukai (jika ada):
5. Warna Dominan / Identitas Brand:
6. Kontak Utama (No. WA / Email / Alamat):
7. Link Logo / Foto Produk (bisa via Google Drive):

Jika ada pertanyaan, jangan sungkan untuk tanyakan ya Kak. Terima kasih! 🙏
                </div>
                <div class="flex justify-end">
                    <button @click="copyToClipboard($el.closest('.space-y-4').querySelector('.font-mono').innerText, 'brief')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]" x-text="copiedSnippet === 'brief' ? 'check' : 'content_copy'"></span>
                        <span x-text="copiedSnippet === 'brief' ? 'Tersalin!' : 'Salin Template Teks'"></span>
                    </button>
                </div>
            </div>

            <!-- TAB 3: REKENING & DP -->
            <div x-show="activeTab === 'rekening'" class="space-y-4" style="display: none;">
                <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono text-xs text-zinc-800 dark:text-zinc-200 whitespace-pre-line">
Halo Kak, untuk pembayaran pengerjaan website dapat ditransfer melalui rekening resmi kami:

🏦 *Bank BCA*
No. Rekening: *8735234567*
A/N: *Ryan Zulkarnaen* (RZ Digital)

🏦 *Bank Mandiri*
No. Rekening: *1310012345678*
A/N: *Ryan Zulkarnaen*

*Ketentuan DP (Uang Muka):*
• DP minimal 50% untuk memulai pengerjaan desain & setup server.
• Pelunasan 50% dilakukan setelah website selesai direview dan siap online.

Mohon konfirmasi dengan mengirimkan bukti transfer jika sudah melakukan pembayaran ya Kak. Terima kasih! 🙏
                </div>
                <div class="flex justify-end">
                    <button @click="copyToClipboard($el.closest('.space-y-4').querySelector('.font-mono').innerText, 'rekening')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]" x-text="copiedSnippet === 'rekening' ? 'check' : 'content_copy'"></span>
                        <span x-text="copiedSnippet === 'rekening' ? 'Tersalin!' : 'Salin Template Teks'"></span>
                    </button>
                </div>
            </div>

            <!-- TAB 4: MAINTENANCE -->
            <div x-show="activeTab === 'maintenance'" class="space-y-4" style="display: none;">
                <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono text-xs text-zinc-800 dark:text-zinc-200 whitespace-pre-line">
Halo Kak! Agar performa website tetap optimal dan data selalu terlindungi, kami menyediakan layanan pendampingan:

🛡️ *Paket Pemeliharaan & Maintenance Website (Rp 150.000 / Bulan)*
✅ Auto Backup Database & File Mingguan ke Cloud Storage
✅ Pemantauan Uptime Server & Keamanan (Anti-Malware/SSL Guard)
✅ Bantuan Update Teks, Foto Produk & Banner Berkala
✅ Konsultasi Teknis & Support Prioritas via WhatsApp

Dengan layanan ini, Kakak bisa fokus mengembangkan bisnis tanpa khawatir kendala teknis website. 😊
                </div>
                <div class="flex justify-end">
                    <button @click="copyToClipboard($el.closest('.space-y-4').querySelector('.font-mono').innerText, 'maintenance')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]" x-text="copiedSnippet === 'maintenance' ? 'check' : 'content_copy'"></span>
                        <span x-text="copiedSnippet === 'maintenance' ? 'Tersalin!' : 'Salin Template Teks'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
