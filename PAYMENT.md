# Panduan Midtrans Sandbox

## 1. Ambil API keys
1. Login ke https://dashboard.sandbox.midtrans.com
2. Pastikan toggle lingkungan di kiri atas = **Sandbox**
3. Settings → Access Keys → salin Merchant ID, Client Key, Server Key
   (key sandbox diawali `SB-Mid-client-` / `SB-Mid-server-`)

## 2. Isi `.env`
```env
MIDTRANS_MERCHANT_ID=Gxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_EXPIRY_MINUTES=60
```
Lalu: `php artisan config:clear`. Jangan commit `.env` dan jangan bagikan Server Key.

## 3. Notification URL (agar status otomatis jadi paid)
```bash
php artisan serve          # terminal 1
ngrok http 8000            # terminal 2
```
Dashboard → Settings → Configuration → **Payment Notification URL**:
`https://xxxx.ngrok-free.app/midtrans/callback`
(URL ngrok gratis berubah tiap restart, jadi update lagi. Samakan juga `APP_URL`.)

Tanpa ngrok: klik **Cek status pembayaran** di halaman order.

## 4. Data uji
| Metode | Cara |
|---|---|
| Kartu sukses | 4811 1111 1111 1114, CVV 123, expiry masa depan, OTP 112233 |
| Kartu ditolak | 4911 1111 1111 1113 |
| VA / GoPay / QRIS | bayar lewat https://simulator.sandbox.midtrans.com |

## 5. Alur
1. Pilih tiket → order `pending` (batas bayar sesuai `MIDTRANS_EXPIRY_MINUTES`)
2. Halaman bayar → Snap popup (token dibuat sekali dan dipakai ulang)
3. Midtrans kirim notifikasi (atau klik "Cek status") → order `paid` → tiket terbit
4. Lewat batas waktu tanpa bayar → order `cancelled`
