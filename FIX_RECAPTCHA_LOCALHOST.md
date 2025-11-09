# Fix reCAPTCHA "localhost not supported" Error

## Problem
Error: **"Localhost is not in the list of supported domains for this site key"**

## Solution

### Step 1: Login ke Google reCAPTCHA Console
Buka: https://www.google.com/recaptcha/admin

### Step 2: Pilih Site Anda
Klik pada site yang sudah dibuat (atau buat baru jika belum ada)

### Step 3: Edit Settings
1. Klik **icon gear/settings** di site Anda
2. Atau klik **label site** untuk edit

### Step 4: Tambahkan Domains
Di section **"Domains"**, tambahkan:
```
localhost
127.0.0.1
```

**Cara menambahkan:**
1. Ketik `localhost` di textbox
2. Tekan **Enter**
3. Ketik `127.0.0.1` di textbox
4. Tekan **Enter**
5. Klik **Save**

### Step 5: Update .env dengan Production Keys
```env
RECAPTCHA_SITE_KEY=6LcGLwcsAAAAAP_Qjvim6VDYCgP5eVM0VozOzQqb
RECAPTCHA_SECRET_KEY=6LcGLwcsAAAAAD7R5hNbn1NwjUV92qF_h7voMI8i
```

### Step 6: Clear Cache
```bash
php artisan config:clear
php artisan config:cache
```

### Step 7: Test
Refresh browser (Ctrl+Shift+R) dan coba lagi!

---

## Alternative: Buat Site reCAPTCHA Baru

Jika tidak bisa edit yang lama, buat baru:

### 1. Buka https://www.google.com/recaptcha/admin/create

### 2. Isi Form:
- **Label**: Form Vaksin RSI - Development
- **reCAPTCHA type**: reCAPTCHA v2 → "I'm not a robot" Checkbox
- **Domains**: 
  ```
  localhost
  127.0.0.1
  ```
- Accept terms → **Submit**

### 3. Copy Keys Baru
Ganti di `.env`:
```env
RECAPTCHA_SITE_KEY=<site_key_baru>
RECAPTCHA_SECRET_KEY=<secret_key_baru>
```

### 4. Clear Cache & Test
```bash
php artisan config:clear
php artisan config:cache
```

---

## Expected Result

✅ reCAPTCHA checkbox muncul
✅ Setelah dicentang, mungkin muncul **challenge** (pilih gambar)
✅ Setelah berhasil verify, baru bisa submit form

## Notes

- Test keys (6LeIxAcTAAAAA...) **tidak ada challenge**, langsung pass
- Production keys **akan ada challenge** untuk keamanan real
- Untuk VPS, tambahkan IP/domain VPS ke daftar domains
