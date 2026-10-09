# EventTicket — Platform Pemesanan Tiket Event

Platform pemesanan tiket event berbasis web yang mirip pengalaman Loket / TipTip.  
Pelanggan dapat melihat daftar event, filter kategori, checkout, bayar, dan mengunduh tiket PDF. Admin dapat mengelola event dan melakukan check-in.

---

## Masalah

Event organizer, komunitas, dan penyelenggara seminar/konser sering kesulitan:
- Menampilkan daftar event secara profesional
- Menerima pembayaran tiket secara online
- Mengelola stok tiket per tipe
- Melakukan check-in peserta di lokasi event

## Solusi

EventTicket menyediakan sistem ticketing lengkap mulai dari katalog event, pemesanan, pembayaran (Midtrans), unduh tiket PDF, hingga check-in admin.

## Fitur Utama

**Customer**
- Beranda event dengan slider banner promo
- Filter event berdasarkan kategori (Teknologi, Musik, Bisnis, Desain, Olahraga, Edukasi, Kuliner, Seni & Budaya)
- Halaman detail event + pilih tipe tiket
- Checkout order (qty per tipe tiket)
- Pembayaran via Midtrans Snap
- Unduh tiket PDF
- Login dengan Google OAuth

**Admin**
- Kelola event & tipe tiket
- Kelola pesanan
- Check-in peserta

## Tech Stack

- Laravel 11
- PHP 8.2+
- Blade + Tailwind CSS 3 + Alpine.js + Vite
- Laravel Breeze + Google OAuth
- Midtrans Snap
- MySQL / SQLite / PostgreSQL

## Demo

Demo dapat dijalankan secara lokal. Ikuti langkah instalasi di bawah.

## Instalasi

```bash
git clone https://github.com/SulthanAfif/eventticket.git
cd eventticket
composer install
cp .env.example .env
php artisan key:generate
# Atur database & Midtrans key di .env
php artisan migrate --seed
php artisan serve
```

## Repository

📦 [https://github.com/SulthanAfif/eventticket](https://github.com/SulthanAfif/eventticket)

---

**EventTicket** — solusi ticketing untuk event organizer, seminar, konser, dan komunitas.  
