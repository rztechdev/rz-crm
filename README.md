# RZ CRM — CRM & Flustra WhatsApp Gateway

> **Sistem Manajemen Prospek UMKM & Automasi WhatsApp** untuk RZ Digital Creative.

---

## 🚀 Fitur Utama

- **Pipeline & Prospek**: Manajemen lead mulai dari belum dihubungi, sudah chat, nego, hingga deal.
- **Proyek Website**: Pelacakan status pengerjaan proyek (`draft` -> `dp_diterima` -> `dikerjakan` -> `review` -> `selesai`).
- **Pembayaran & Invoicing**: Pencatatan mutasi DP, pelunasan, dan biaya langganan bulanan.
- **Maintenance Rutin (MRR)**: Manajemen langganan maintenance website bulanan dengan jadwal jatuh tempo otomatis.
- **Flustra WhatsApp Integration**:
  - Auto-notifikasi transaksional (Konfirmasi DP, Website Live & Maintenance Offer, Reminder H-3 Jatuh Tempo).
  - Anti-Spam Guardrail (otomatis memblokir auto-blast ke prospek dingin).
  - Webhook listener untuk merekam balasan chat masuk langsung ke timeline kontak lead.
- **Multi-Akun Internal**: Manajemen akun pengguna internal untuk tim RZ Digital Creative.
- **Desain UI RZ Sage Green**: Tampilan modern, responsif, zero-flicker layout, dan dark mode support.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Components, Tailwind CSS, Alpine.js
- **Database**: SQLite / MySQL
- **Gateway**: Flustra WA Gateway REST API & Webhooks

---

## 📦 Panduan Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/rztechdev/rz-crm.git
cd rz-crm

# 2. Install PHP & Node dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database migration & seeding
touch database/database.sqlite
php artisan migrate --seed

# 5. Build assets & jalankan server (Port 8022)
npm run build
npm run all
```

---

## 🔑 Kredensial Default Admin

- **URL**: `http://localhost:8022`
- **Email**: `rzcompanyidn@gmail.com`
- **Password**: `12345678`

---

## 📄 Lisensi

Hak Cipta © RZ Digital Creative. Dilindungi Undang-Undang.
