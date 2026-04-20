# Jaf Donation — Backend Laravel 11

CMS Panel untuk mengelola artikel, donasi, program, dan galeri website Jaf Donation.

---

## Persyaratan

| Software    | Versi Minimum |
|-------------|---------------|
| PHP         | 8.2+          |
| Composer    | 2.x           |
| MySQL       | 8.0+          |
| Node.js     | 18+           |

---

## Cara Setup

### 1. Install PHP & Composer
Jika belum ada, download:
- **PHP 8.2+**: https://windows.php.net/download/ (Thread Safe)
- **Composer**: https://getcomposer.org/download/

Atau gunakan **Laragon** (rekomendasi untuk Windows):
- Download: https://laragon.org/download/

### 2. Install Dependencies Laravel
```bash
cd Backend
composer install
```

### 3. Konfigurasi .env
```bash
# Copy file konfigurasi
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit file `.env`, sesuaikan database:
```env
DB_DATABASE=jaf_donation
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database
Buka phpMyAdmin atau MySQL CLI:
```sql
CREATE DATABASE jaf_donation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan Migrasi & Seeder
```bash
php artisan migrate
php artisan db:seed
```

Ini akan membuat:
- Semua tabel database
- Admin user default: `admin@jafdonation.id` / `admin123`
- 7 kategori default (Amal, Kesehatan, Pendidikan, dll.)

### 6. Buat Symlink Storage
```bash
php artisan storage:link
```

### 7. Jalankan Server
```bash
php artisan serve
```

Buka browser: http://localhost:8000
Login CMS: http://localhost:8000/login

---

## Konfigurasi Midtrans

Setelah key Midtrans Anda siap, tambahkan ke `.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false   # ganti true saat production
```

Webhook URL untuk didaftarkan di dashboard Midtrans:
```
https://yourdomain.com/api/v1/donations/callback
```

---

## Struktur Folder

```
Backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          ← Controller CMS panel
│   │   │   ├── Api/            ← API donasi & Midtrans
│   │   │   └── Auth/           ← Login/logout
│   │   └── Middleware/
│   ├── Models/                 ← User, Article, Category, Donation, Program, Gallery
│   └── Services/
│       └── MidtransService.php ← Integrasi pembayaran Midtrans
├── database/
│   ├── migrations/             ← Skema database
│   └── seeders/                ← Data awal (admin + kategori)
├── resources/views/
│   ├── admin/                  ← Blade views CMS panel
│   │   ├── layouts/app.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── articles/           ← CRUD artikel
│   │   ├── categories/         ← Manajemen kategori
│   │   ├── donations/          ← Data donasi
│   │   ├── programs/           ← Manajemen program
│   │   └── gallery/            ← Upload foto & video
│   └── auth/login.blade.php
├── routes/
│   ├── web.php                 ← Route CMS (admin panel)
│   └── api.php                 ← Route API (donasi/Midtrans)
├── config/midtrans.php         ← Konfigurasi Midtrans
└── .env.example                ← Template konfigurasi
```

---

## Fitur CMS

| Modul        | Fitur                                                       |
|--------------|-------------------------------------------------------------|
| **Dashboard** | Statistik: artikel, donasi, program aktif, total terkumpul |
| **Artikel**   | CRUD artikel, editor Markdown, upload thumbnail, SEO meta   |
| **Kategori**  | Tambah/edit/hapus kategori dengan warna & icon kustom       |
| **Program**   | Buat program donasi, progress bar, target dana              |
| **Donasi**    | Lihat semua donasi, filter, update status manual, export CSV|
| **Galeri**    | Upload foto, embed video URL, kelola per kategori           |

---

## API Endpoints

| Method | URL                                  | Deskripsi                  |
|--------|--------------------------------------|----------------------------|
| POST   | `/api/v1/donations`                  | Buat donasi baru           |
| POST   | `/api/v1/donations/callback`         | Webhook Midtrans           |
| GET    | `/api/v1/donations/{orderId}/status` | Cek status donasi          |

### Contoh Request Donasi (dari Frontend)
```javascript
fetch('/api/v1/donations', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    donor_name:  'Budi Santoso',
    donor_email: 'budi@email.com',
    donor_phone: '08123456789',
    program_id:  1,           // opsional
    amount:      100000,
    note:        'Semoga bermanfaat',
    is_anonymous: false
  })
})
.then(r => r.json())
.then(data => {
  if (data.success) {
    // Buka Midtrans Snap popup
    window.snap.pay(data.snap_token);
  }
});
```

---

## Default Login

| Field    | Value                  |
|----------|------------------------|
| Email    | admin@jafdonation.id   |
| Password | admin123               |

> **Penting:** Segera ganti password setelah pertama kali login!

---

## Catatan

- Upload gambar disimpan di `public/uploads/`
- Jalankan `php artisan migrate:fresh --seed` untuk reset database
- Log ada di `storage/logs/laravel.log`
