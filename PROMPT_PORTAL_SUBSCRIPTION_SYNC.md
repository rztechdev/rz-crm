# Prompt: Integrasi Subscription / Masa Berlaku di Portal Client

## Konteks

CRM RZ Digital Creative sudah memiliki fitur **Project Subscription (Masa Berlaku / Lisensi)** yang baru ditambahkan. Fitur ini mengelola masa berlaku project seperti POS-Kasir yang masa berlakunya 1 tahun (domain, hosting, lisensi aplikasi).

### Struktur Data di CRM (tabel `project_subscriptions`):
```
- id
- project_id (FK → projects)
- lead_id (FK → leads / klien)
- tipe: enum('tahunan', 'bulanan', '6_bulan', 'custom')
- harga: unsigned big integer (biaya perpanjangan)
- tanggal_mulai: date
- tanggal_expired: date
- status: enum('aktif', 'akan_expired', 'expired', 'diperpanjang', 'nonaktif')
- auto_renew: boolean
- terakhir_diingatkan_at: timestamp nullable
- catatan: text nullable
- created_at, updated_at
```

### Apa yang sudah ada di CRM:
1. **Model** `App\Models\ProjectSubscription` — dengan accessor: `tipe_label`, `status_label`, `status_color`, `sisa_hari`, methods: `isExpired()`, `isExpiringSoon()`, `renew()`
2. **Controller** CRUD + renew + toggle status + send reminder WA
3. **Artisan Command** `crm:check-subscriptions` — scheduled daily 08:00, auto-update status ke `akan_expired` (H-30) dan `expired`, serta kirim WA reminder
4. **WA Templates**: `subscriptionExpiringReminder()`, `subscriptionExpired()`, `subscriptionRenewed()`
5. **PortalSyncService** sudah ada di `app/Services/Portal/PortalSyncService.php` — melakukan HTTP POST ke Portal untuk sync project + payment data

---

## Yang Perlu Dilakukan di Portal Client

### 1. Migration: Tambah kolom subscription di tabel projects Portal
```
- subscription_type: enum('tahunan', 'bulanan', '6_bulan', 'custom') nullable
- subscription_price: unsigned big integer default 0
- subscription_start: date nullable
- subscription_expired: date nullable
- subscription_status: enum('aktif', 'akan_expired', 'expired', 'diperpanjang', 'nonaktif') default 'aktif'
- auto_renew: boolean default false
```

### 2. Update API endpoint `sync-client-project` di Portal
Portal sudah punya endpoint `/api/internal/v1/sync-client-project` yang menerima data dari CRM.

Tambahkan field subscription di payload yang diterima:
```json
{
  "...existing fields...",
  "subscription": {
    "tipe": "tahunan",
    "harga": 500000,
    "tanggal_mulai": "2026-09-01",
    "tanggal_expired": "2027-09-01",
    "status": "aktif",
    "auto_renew": false
  }
}
```

Simpan data subscription ke kolom project di Portal saat sync diterima.

### 3. Tampilkan info masa berlaku di Portal Client dashboard
Di halaman project detail yang bisa diakses klien:
- Tampilkan badge status subscription (Aktif / Akan Expired / Expired)
- Tampilkan tanggal expired dan sisa hari
- Jika `akan_expired` atau `expired`, tampilkan alert/banner dengan instruksi perpanjangan + info rekening

### 4. (Opsional) Buat endpoint baru di Portal untuk CRM fetch status subscription
```
GET /api/internal/v1/subscription-status/{project_id}
```
Agar CRM bisa cek apakah Portal sudah meng-update status kliennya.

### 5. Update CRM PortalSyncService untuk mengirim data subscription
Di sisi CRM (`app/Services/Portal/PortalSyncService.php`), tambahkan data subscription ke payload `syncProject()`:

```php
// Di dalam method syncProject(), tambahkan ke $payload:
$activeSubscription = $project->activeSubscription;
if ($activeSubscription) {
    $payload['subscription'] = [
        'tipe' => $activeSubscription->tipe,
        'harga' => (int) $activeSubscription->harga,
        'tanggal_mulai' => $activeSubscription->tanggal_mulai->toDateString(),
        'tanggal_expired' => $activeSubscription->tanggal_expired->toDateString(),
        'status' => $activeSubscription->status,
        'auto_renew' => $activeSubscription->auto_renew,
    ];
}
```

---

## Alur Sinkronisasi

```
CRM (create/update subscription)
  ↓ PortalSyncService::syncProject()
  ↓ HTTP POST /api/internal/v1/sync-client-project
Portal Client (simpan subscription data)
  ↓ Tampilkan ke klien di dashboard
  ↓ Klien melihat status masa berlaku + instruksi perpanjangan
```

## Catatan
- Autentikasi menggunakan header `X-Internal-Secret` yang sudah ada
- CompanySetting sudah mengelola `portal_sync_url` dan `portal_sync_secret`
- Tidak perlu buat tabel terpisah di Portal, cukup tambah kolom di tabel projects yang sudah ada
