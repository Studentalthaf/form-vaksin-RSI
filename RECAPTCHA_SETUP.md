# Setup Google reCAPTCHA v2

## Langkah 1: Daftar ke Google reCAPTCHA

1. Buka **Google reCAPTCHA Admin Console**: https://www.google.com/recaptcha/admin/create
2. Login dengan akun Google Anda

## Langkah 2: Registrasi Website

### Form Pendaftaran:
- **Label**: `Form Vaksin RSI` (atau nama aplikasi Anda)
- **reCAPTCHA type**: Pilih **"reCAPTCHA v2"** → **"I'm not a robot" Checkbox**
- **Domains**: 
  - Untuk lokal: `localhost`
  - Untuk VPS: `your-domain.com` atau IP VPS (contoh: `192.168.1.100`)
  
  *Catatan: Anda bisa menambahkan multiple domains, pisahkan dengan Enter*
  
- **Accept the reCAPTCHA Terms of Service**: Centang checkbox
- Klik **"Submit"**

## Langkah 3: Copy Keys

Setelah submit, Google akan memberikan 2 keys:

### 1. Site Key (Public Key)
```
6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```
→ Key ini akan digunakan di **frontend** (HTML/Blade)

### 2. Secret Key (Private Key)
```
6LcYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
```
→ Key ini akan digunakan di **backend** (Laravel Controller)

## Langkah 4: Update File .env

Buka file `.env` di root project, lalu update:

```env
RECAPTCHA_SITE_KEY=6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
RECAPTCHA_SECRET_KEY=6LcYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
```

**Ganti** `your_site_key_here` dan `your_secret_key_here` dengan key yang Anda dapatkan.

## Langkah 5: Clear Config Cache (Jika di VPS)

Jika aplikasi sudah di VPS/production, jalankan command ini:

```bash
# Jika standalone Laravel
php artisan config:clear
php artisan config:cache

# Jika di Docker
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan config:cache
```

## Langkah 6: Testing

1. Buka form permohonan pasien: `http://localhost/permohonan/create`
2. Isi form sampai bawah
3. Anda akan melihat **reCAPTCHA checkbox** "I'm not a robot" sebelum tombol Submit
4. Centang checkbox tersebut
5. Klik **"Kirim Permohonan"**

### Testing Scenarios:

✅ **Sukses**: Jika centang reCAPTCHA → form terkirim
❌ **Gagal**: Jika tidak centang → muncul error: "Silakan centang 'Saya bukan robot'"

## Langkah 7: Whitelist Domains (Jika Error di VPS)

Jika di VPS muncul error, pastikan domain VPS sudah ditambahkan:

1. Kembali ke https://www.google.com/recaptcha/admin
2. Klik pada **label aplikasi** Anda
3. Di section **Domains**, klik **"Add a new domain"**
4. Masukkan domain atau IP VPS Anda
5. Klik **Save**

## Troubleshooting

### Error: "Invalid site key"
- Pastikan `RECAPTCHA_SITE_KEY` di `.env` benar
- Clear config cache: `php artisan config:clear`

### Error: "Verifikasi reCAPTCHA gagal"
- Pastikan `RECAPTCHA_SECRET_KEY` di `.env` benar
- Pastikan domain/IP sudah terdaftar di Google reCAPTCHA Console
- Check log Laravel: `storage/logs/laravel.log`

### reCAPTCHA tidak muncul
- Pastikan ada internet connection (reCAPTCHA butuh load dari Google)
- Check browser console untuk JavaScript errors
- Pastikan script Google sudah load: `<script src="https://www.google.com/recaptcha/api.js"></script>`

### reCAPTCHA selalu invalid di localhost
- Pastikan `localhost` sudah didaftarkan sebagai domain di Google reCAPTCHA Console
- Jika menggunakan `127.0.0.1`, tambahkan juga ke domains

## Production Checklist

Sebelum deploy ke VPS, pastikan:

- [ ] Sudah daftar domain VPS di Google reCAPTCHA Console
- [ ] `.env` production sudah diupdate dengan keys yang benar
- [ ] Run `config:cache` setelah update `.env`
- [ ] Test reCAPTCHA di production environment
- [ ] Monitor log untuk error reCAPTCHA

## Security Notes

⚠️ **JANGAN commit `.env` ke Git!**
⚠️ **Secret Key** harus dijaga kerahasiaannya, JANGAN share ke public

✅ `.env` sudah masuk `.gitignore` secara default di Laravel

---

## Links

- Google reCAPTCHA Admin: https://www.google.com/recaptcha/admin
- Documentation: https://developers.google.com/recaptcha/docs/display
- Test Site (untuk testing): https://www.google.com/recaptcha/api2/demo
