# EventTicket

Platform pemesanan tiket event berbasis **Laravel 11** + **Tailwind CSS** + **Alpine.js**, dengan integrasi pembayaran **Midtrans**.

Mirip pengalaman platform tiket modern (Loket / TipTip): daftar event, filter kategori, detail event, checkout, bayar, unduh tiket PDF, dan check-in admin.

---

## Fitur utama

### Publik (Customer)
- Beranda event dengan **slider banner promo**
- **Filter event berdasarkan kategori** (Teknologi, Musik, Bisnis, Desain, Olahraga, Edukasi, Kuliner, Seni & Budaya)
- Halaman detail event + pilih tipe tiket
- Checkout order (qty per tipe tiket)
- Pembayaran Midtrans (Snap / redirect)
- Riwayat order & status pembayaran
- **Unduh / cetak tiket PDF** (desain kartu dengan gradient / background kustom)
- Login email + Google OAuth

### Admin
- Dashboard statistik
- CRUD **Event** (judul, deskripsi, venue, kota, jadwal, status, **kategori**, poster)
- Kustom **background tiket** per event (preset gradient atau gambar upload/URL)
- Manajemen tipe tiket (nama, harga, kuota)
- Manajemen **Banner** promo beranda
- Daftar order & detail
- **Check-in tiket** (scan / input kode)
- Pengaturan site (hero text, WhatsApp support, dll.)

### Kategori Event
| Kategori              | Icon | Contoh event                          |
|-----------------------|------|---------------------------------------|
| Teknologi & IT        | 💻   | Tech Conference, DevOps Meetup, AI Summit |
| Musik & Konser        | 🎵   | Jazz Night, Indie Festival            |
| Bisnis & Startup      | 🚀   | Pitch Day, Founder Networking         |
| Desain & Kreatif      | 🎨   | Workshop UI/UX, Photography           |
| Olahraga & Kesehatan  | 🏃   | 10K Run, Yoga Retreat                 |
| Edukasi & Workshop    | 📚   | Public Speaking, Digital Marketing    |
| Kuliner & Food        | 🍜   | Street Food Festival, Baking Class    |
| Seni & Budaya         | 🎭   | Art Exhibition, Wayang Night          |

---

## Stack teknis

| Layer        | Teknologi                                      |
|--------------|------------------------------------------------|
| Backend      | Laravel 11, PHP 8.2+                           |
| Frontend     | Blade, Tailwind CSS 3, Alpine.js, Vite         |
| Auth         | Laravel Breeze + Google OAuth                  |
| Payment      | Midtrans Snap                                  |
| Database     | SQLite (default) / MySQL / PostgreSQL          |
| Storage      | Local disk (`storage/app/public`)              |

---

## Instalasi

### Prasyarat
- PHP 8.2+ dengan ekstensi: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`/`imagick`
- Composer 2
- Node.js 18+ & npm
- (Opsional) MySQL/PostgreSQL jika tidak memakai SQLite

### Langkah

```bash
# 1. Clone repository
git clone <url-repo-anda> eventticket
cd eventticket

# 2. Install dependensi PHP
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database (default SQLite)
# Pastikan file database ada:
touch database/database.sqlite
# Atau ubah DB_* di .env untuk MySQL/PostgreSQL

# 5. Migrasi + seeder (kategori + event contoh + user admin)
php artisan migrate --seed

# 6. Storage link (untuk poster & background tiket)
php artisan storage:link

# 7. Frontend assets
npm install
npm run build
# Development: npm run dev

# 8. Jalankan server
php artisan serve
# Buka http://localhost:8000
```

### Akun demo (dari seeder)

Cek `database/seeders/UserSeeder.php` untuk email/password admin.  
Biasanya ada user admin dengan flag `is_admin = true`.

---

## Konfigurasi penting (.env)

```env
APP_NAME=EventTicket
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# atau MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=eventticket
# DB_USERNAME=root
# DB_PASSWORD=

# Midtrans
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=false

# Google OAuth (opsional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Callback Midtrans: `POST /midtrans/callback` (sudah di-route, pastikan URL publik jika pakai ngrok).

---

## Struktur penting

```
app/
  Http/Controllers/
    EventController.php          # Publik: list + filter kategori + detail
    OrderController.php          # Checkout, pay, callback, unduh tiket
    Admin/
      EventController.php        # CRUD event + tipe tiket + kategori
      BannerController.php
      TicketController.php       # Check-in
  Models/
    Category.php                 # Kategori event
    Event.php                    # + belongsTo Category, ticket_style
    TicketType.php / Ticket.php / Order.php
database/
  migrations/                    # termasuk create_categories + category_id
  seeders/
    CategorySeeder.php           # 8 kategori
    EventSeeder.php              # 16+ event per kategori
resources/views/
  events/index.blade.php         # Filter chip kategori
  events/show.blade.php
  tickets/download.blade.php     # Template cetak/PDF tiket
  admin/events/
routes/web.php
```

---

## Alur fitur kategori

1. **Seeder** membuat 8 kategori + event yang terikat `category_id`.
2. **Admin** memilih kategori saat create/edit event (`admin/events/_form`).
3. **Beranda** menampilkan chip filter: `/?category=teknologi-it`.
4. **Controller** memfilter `Event::whereHas('category', ...)`.
5. Badge kategori tampil di kartu event dan halaman detail.

Menambah kategori baru:

```bash
php artisan tinker
>>> \App\Models\Category::create([
...   'name' => 'Film & Cinema',
...   'slug' => 'film-cinema',
...   'icon' => '🎬',
...   'color' => 'rose',
...   'sort_order' => 9,
... ]);
```

Atau tambahkan di `CategorySeeder` lalu `php artisan db:seed --class=CategorySeeder`.

---

## Unduh tiket (print → PDF)

Halaman `GET /orders/{code}/tickets` merender template Blade mirip kartu tiket.

- Background mengikuti `event.ticket_style` (gradient atau gambar).
- CSS memakai `print-color-adjust: exact` agar warna ikut tercetak.
- Di dialog print browser, aktifkan **Background graphics**.

---

## Perintah berguna

```bash
# Reset DB + seed ulang (hati-hati: data hilang)
php artisan migrate:fresh --seed

# Hanya seed kategori & event
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=EventSeeder

# Queue (jika pakai job)
php artisan queue:work

# Clear cache view setelah ubah Blade
php artisan view:clear
```

---

## Deployment singkat

1. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` ke domain publik.
2. `composer install --optimize-autoloader --no-dev`
3. `npm run build`
4. `php artisan migrate --force`
5. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. Pastikan `storage/` dan `bootstrap/cache/` writable.
7. Webhook Midtrans mengarah ke `https://domain-anda/midtrans/callback`.

---

## Kontribusi & lisensi

Proyek ini adalah aplikasi demo/pembelajaran. Silakan fork, modifikasi, dan gunakan sesuai kebutuhan.

Lisensi: MIT (sesuaikan jika diperlukan).

---

## Changelog singkat

- **2026-09-30** — Sistem **kategori event**: migration, model, seeder 8 kategori + 16 event, filter UI, badge di admin & publik, field kategori di form admin.
- Background tiket kustom (gradient / image).
- Unduh tiket dengan print-color-adjust.
- Banner promo, Midtrans, Google login, check-in admin.
