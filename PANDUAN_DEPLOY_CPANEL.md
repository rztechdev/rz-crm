# 📘 Panduan Lengkap Deploy RZ CRM ke cPanel

Panduan ini untuk mengaktifkan project **RZ CRM** di subdomain **`crm.rzdigitalcreative.my.id`**.

---

## 📋 Ringkasan Info Project
* **Subdomain**: `crm.rzdigitalcreative.my.id`
* **Folder cPanel**: `/home/rzdigita/repositories/rz-crm`
* **Document Root**: `repositories/rz-crm/public`
* **Database**: `rzdigita_db_rz_crm`
* **User Database**: `rzdigita_db_rzdigitalcreative`

---

## 🚀 Langkah 1: Setup Subdomain & Document Root

1. Di cPanel, buka menu **`Domains`**.
2. Pastikan domain/subdomain **`crm.rzdigitalcreative.my.id`** sudah ada:
   - **Document Root**: `repositories/rz-crm/public` *(PASTIKAN berakhiran `/public`)*
3. Aktifkan toggle **Force HTTPS Redirect** ke posisi **On**.

---

## ⚙️ Langkah 2: Setup File `.env` di cPanel

1. Di **File Manager cPanel**, buka folder:
   `/home/rzdigita/repositories/rz-crm/`
2. Buat file baru bernama **`.env`** (atau edit jika sudah ada).
3. Salin isi konfigurasi dari file `.env.production` berikut:

```ini
APP_NAME="RZ CRM"
APP_ENV=production
APP_KEY=base64:nRbJIVvADGaRy8kLnrzzregiEsYpttXguTA7JHpIcLY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://crm.rzdigitalcreative.my.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

# ====== AKUN ADMIN UTAMA ======
ADMIN_NAME="Owner RZ Digital"
ADMIN_EMAIL=rzcompanyidn@gmail.com
ADMIN_PASSWORD="12345678"

# ====== DATABASE MYSQL CPANEL ======
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rzdigita_db_rz_crm
DB_USERNAME=rzdigita_db_rzdigitalcreative
DB_PASSWORD="LE9P@rUVe84mXhV"

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

CACHE_STORE=database
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log

# Catatan: Flustra WhatsApp Gateway, Barcode QRIS, Rekening BCA, dan
# Pengaturan Perusahaan kini dikelola 100% via Database melalui menu
# "Pengaturan Perusahaan" di web CRM, tanpa perlu mengedit file .env ini lagi.
```
4. Klik **Save Changes**.

---

## ⏰ Langkah 3: Setup Cron Job cPanel (WAJIB untuk Reminder Otomatis)

> [!IMPORTANT]
> Tanpa langkah ini, **Reminder Maintenance WhatsApp H-3 jam 09:00 pagi TIDAK AKAN PERNAH JALAN**.
> Scheduler Laravel membutuhkan pemicu setiap menit dari Cron Job cPanel.

1. Di cPanel, cari dan buka menu **`Cron Jobs`** (atau **Tugas Cron**).
2. Di bagian **Add New Cron Job**:
   - **Common Settings**: Pilih **Once Per Minute (`* * * * *`)**
   - **Command**:
     ```bash
     /usr/local/bin/php /home/rzdigita/repositories/rz-crm/artisan schedule:run >> /dev/null 2>&1
     ```
     *(Catatan: Jika server menggunakan CloudLinux alt-php82, ganti `/usr/local/bin/php` dengan `/opt/cpanel/ea-php82/root/usr/bin/php`)*
3. Klik tombol **Add New Cron Job**.

---

## ⚡ Langkah 4: Jalankan Migrasi & Database Seeder

Jika Anda baru deploy pertama kali atau ada update tabel:
1. Buat file sementara `migrate.php` di `/home/rzdigita/repositories/rz-crm/public/migrate.php`
2. Isi skrip:
```php
<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "<p style='color:green'>✔ Migrasi Database Sukses: " . nl2br(\Illuminate\Support\Facades\Artisan::output()) . "</p>";

    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo "<p style='color:green'>✔ Storage Link Sukses</p>";
} catch (\Exception $e) {
    echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
}
```
3. Akses via browser: `https://crm.rzdigitalcreative.my.id/migrate.php`
4. **HAPUS** file `migrate.php` tersebut setelah selesai demi keamanan.

---

## 📲 Langkah 5: Hubungkan Nomor WA di Flustra Dashboard

1. Login ke [https://wa.flustra.id](https://wa.flustra.id).
2. Buka menu **Sesi / Nomor**.
3. Di sesi **"rz company"**, klik tombol **Scan QR**.
4. Buka aplikasi WhatsApp di HP Anda (nomor resmi RZ Digital Creative) ➡️ **Perangkat Tertaut (Linked Devices)** ➡️ **Tautkan Perangkat** ➡️ Scan kode QR di layar.
5. Status sesi akan berubah menjadi **`connected`**.
6. Sistem CRM Anda sekarang siap mengirim pesan WhatsApp otomatis dan manual!

---

## 🛡️ Langkah 6: Konfigurasi Perusahaan & WhatsApp Gateway di Web CRM

1. Login ke web CRM: `https://crm.rzdigitalcreative.my.id`
2. Buka menu **Tata Kelola & Tim ➡️ Pengaturan Perusahaan**.
3. Di halaman ini, Anda dapat langsung mengelola:
   - **Flustra API Key & WhatsApp Gateway API URL**.
   - **Nomor Rekening BCA & Nama Pemilik**.
   - **Upload Barcode QRIS Resmi & Logo**.
   - **Email & WhatsApp Alert Admin**.
   - **Uji Koneksi WhatsApp Gateway**: Terdapat tombol **"Kirim Pesan Uji Coba (Test Ping)"** untuk langsung mengetes gateway ke nomor HP Anda dengan 1-klik!
4. **Bebas dari File .env**: Semua perubahan tersimpan permanen di database dan langsung dibaca oleh sistem dokumen PDF, WhatsApp Gateway, serta Portal Klien tanpa perlu membuka cPanel lagi.
