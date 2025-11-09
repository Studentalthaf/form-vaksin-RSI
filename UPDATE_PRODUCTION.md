# 🚀 Tutorial Update Production - Form Vaksin RSI

## 📋 Fitur Baru yang Akan Diupdate:

1. ✅ **Delete Button** - Admin bisa hapus permohonan + semua files
2. ✅ **PDF Page 4** - Halaman lampiran KTP & Paspor (layout 2 kolom)
3. ✅ **Google reCAPTCHA** - Anti spam bot di form permohonan
4. ✅ **Storage Link Fix** - Pastikan foto KTP/Paspor/Signature terlihat

---

## 🔧 LANGKAH 1: Persiapan di Localhost

### 1.1. Test Semua Fitur di Localhost

**Test Delete:**
```
- Login sebagai admin
- Buka menu "Permohonan Pasien"
- Klik tombol "Hapus" (merah)
- Pastikan confirm dialog muncul
- Klik OK → pastikan data & file terhapus
```

**Test PDF Page 4:**
```
- Buka "Permohonan Terverifikasi"
- Klik "Cetak PDF" pada permohonan yang punya foto KTP & Paspor
- Buka PDF → pastikan ada 4 halaman
- Page 4 harus tampil KTP (kiri) dan Paspor (kanan)
```

**Test reCAPTCHA:**
```
- Buka form: http://localhost/permohonan/create
- Isi form sampai bawah
- Pastikan checkbox reCAPTCHA muncul
- Centang → Submit → pastikan validasi jalan
```

### 1.2. Commit & Push ke GitHub

```bash
# Cek status
git status

# Add semua perubahan
git add .

# Commit dengan message jelas
git commit -m "Add delete functionality, PDF page 4, and reCAPTCHA

- Add admin delete button for permohonan with file cleanup
- Delete KTP, Passport, signature files from storage
- Add confirm dialog with detailed warning
- Add PDF page 4 with KTP and Passport photos (2 column layout)
- Add placeholders for missing photos
- Add Google reCAPTCHA v2 for anti-spam protection
- Update routes and controller for new features"

# Push ke GitHub
git push origin main
```

---

## 🐳 LANGKAH 2: Update di VPS (Docker)

### 2.1. SSH ke VPS

```bash
ssh user@your_vps_ip
# Contoh: ssh root@192.168.1.100
```

### 2.2. Masuk ke Direktori Project

```bash
cd /var/www/form-vaksin-RSI
```

### 2.3. Pull Update dari GitHub

```bash
# Pastikan tidak ada perubahan lokal yang conflict
git status

# Pull latest code
git pull origin main
```

**Jika ada conflict:**
```bash
# Backup .env dulu
cp .env .env.backup

# Stash local changes
git stash

# Pull lagi
git pull origin main

# Restore .env
cp .env.backup .env
```

---

## 🔑 LANGKAH 3: Setup reCAPTCHA di VPS

### 3.1. Daftar Domain VPS di Google reCAPTCHA

1. Buka: https://www.google.com/recaptcha/admin
2. Login dengan Google account
3. Pilih site Anda (yang keys: `6LcGLwcsAAAAA...`)
4. Klik nama site untuk edit
5. **Tambahkan domain VPS:**
   - Jika pakai IP: `192.168.1.100` (ganti dengan IP VPS Anda)
   - Jika pakai domain: `yourdomain.com`
6. Klik **Save**

### 3.2. Update .env di VPS

```bash
# Edit .env
nano .env
```

**Scroll ke bawah, pastikan ada:**

```env
# Google reCAPTCHA v2 - Production Keys
RECAPTCHA_SITE_KEY=6LcGLwcsAAAAAP_Qjvim6VDYCgP5eVM0VozOzQqb
RECAPTCHA_SECRET_KEY=6LcGLwcsAAAAAD7R5hNbn1NwjUV92qF_h7voMI8i
```

**Simpan:** `Ctrl+O` → Enter → `Ctrl+X`

---

## 🖼️ LANGKAH 4: Fix Storage Link (PENTING!)

### 4.1. Hapus Symbolic Link Lama

```bash
docker exec formvaksin_app rm -f public/storage
```

### 4.2. Buat Symbolic Link Baru

```bash
docker exec formvaksin_app php artisan storage:link
```

**Expected output:**
```
The [public/storage] link has been connected to [storage/app/public].
```

### 4.3. Set Permission yang Benar

```bash
# Set ownership
docker exec formvaksin_app chown -R www-data:www-data storage/
docker exec formvaksin_app chown -R www-data:www-data bootstrap/cache/

# Set permission
docker exec formvaksin_app chmod -R 775 storage/
docker exec formvaksin_app chmod -R 775 bootstrap/cache/
```

### 4.4. Verifikasi Symbolic Link

```bash
docker exec formvaksin_app ls -la public/storage
```

**Output yang BENAR:**
```
lrwxrwxrwx ... public/storage -> /var/www/html/storage/app/public
```

**Pastikan path mengarah ke `/var/www/html/storage/app/public`** (bukan `/var/www/storage/...`)

---

## 🧹 LANGKAH 5: Clear Cache & Update Config

### 5.1. Clear Semua Cache

```bash
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan route:clear
docker exec formvaksin_app php artisan view:clear
docker exec formvaksin_app php artisan cache:clear
```

### 5.2. Re-cache untuk Performance

```bash
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache
```

### 5.3. Jalankan Migration (Jika Ada yang Baru)

```bash
docker exec formvaksin_app php artisan migrate --force
```

**Note:** `--force` diperlukan di production untuk bypass konfirmasi.

---

## 🔄 LANGKAH 6: Restart Container

```bash
# Restart app container
docker restart formvaksin_app

# Wait 5 detik
sleep 5

# Cek status
docker ps | grep formvaksin

# Cek log (pastikan tidak ada error)
docker logs formvaksin_app --tail 50
```

---

## ✅ LANGKAH 7: Testing di Production

### 7.1. Test Storage Link (Foto Muncul)

1. Buka aplikasi di browser: `http://your_vps_ip:8080`
2. Login sebagai admin
3. Buka permohonan yang punya foto KTP
4. **Pastikan foto KTP terlihat** (tidak broken image)
5. Buka screening → **pastikan signature terlihat**

### 7.2. Test Delete Functionality

1. Login sebagai admin
2. Buka menu "Permohonan Pasien"
3. Pilih 1 record untuk test
4. Klik tombol **"Hapus"** (merah)
5. Pastikan **confirm dialog muncul** dengan detail warning
6. Klik **OK**
7. Pastikan **redirect ke index** dengan flash message sukses
8. Verifikasi data **hilang dari database**
9. Verifikasi **file terhapus dari storage**:

```bash
# Cek apakah file benar-benar terhapus
docker exec formvaksin_app ls -la storage/app/public/ktp/
docker exec formvaksin_app ls -la storage/app/public/paspor/
docker exec formvaksin_app ls -la storage/app/public/signatures/
```

### 7.3. Test PDF Page 4

1. Login sebagai admin
2. Buka "Permohonan Terverifikasi"
3. Pilih permohonan yang **punya foto KTP & Paspor**
4. Klik **"Cetak PDF"**
5. Download dan buka PDF
6. Verifikasi **ada 4 halaman**:
   - Page 1: Penilaian Dokter
   - Page 2: Hasil Screening
   - Page 3: Formulir Persetujuan
   - **Page 4: Lampiran Dokumen Identitas** (NEW!)
7. Pastikan **Page 4 menampilkan**:
   - Info pasien (Nama, NIK, Tanggal Lahir)
   - **Foto KTP** (kolom kiri)
   - **Foto Paspor** (kolom kanan)
   - Footer dengan timestamp

### 7.4. Test reCAPTCHA

1. Buka form permohonan: `http://your_vps_ip:8080/permohonan/create`
2. Isi form sampai bawah
3. Pastikan **checkbox reCAPTCHA muncul**
4. **JANGAN centang** → klik Submit
   - ❌ Harus muncul error: **"Silakan centang 'Saya bukan robot'"**
5. **Centang checkbox** → mungkin muncul challenge (pilih gambar)
6. Selesaikan challenge → klik Submit
   - ✅ Form harus **terkirim sukses**

### 7.5. Test Anti-Spam

Coba submit form **tanpa centang reCAPTCHA** beberapa kali:
- Harus **selalu gagal** dengan error
- Log Laravel tidak mencatat request (berarti ditolak di level controller)

---

## 🐛 TROUBLESHOOTING

### Problem 1: Foto Tidak Muncul (Broken Image)

**Cek symbolic link:**
```bash
docker exec formvaksin_app ls -la public/storage
```

**Jika path salah, fix dengan:**
```bash
docker exec formvaksin_app rm -f public/storage
docker exec formvaksin_app php artisan storage:link
docker exec formvaksin_app chmod -R 775 storage/
docker restart formvaksin_app
```

### Problem 2: reCAPTCHA Error "Invalid site key"

**Penyebab:** Domain VPS belum didaftarkan.

**Solusi:**
1. Buka https://www.google.com/recaptcha/admin
2. Edit site Anda
3. Tambahkan IP/domain VPS ke daftar domains
4. Clear cache:
```bash
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan config:cache
```

### Problem 3: PDF Page 4 Foto Tidak Muncul

**Cek permission:**
```bash
docker exec formvaksin_app ls -la storage/app/public/ktp/
docker exec formvaksin_app ls -la storage/app/public/paspor/
```

**Fix permission:**
```bash
docker exec formvaksin_app chmod -R 775 storage/app/public/
docker exec formvaksin_app chown -R www-data:www-data storage/app/public/
```

### Problem 4: Delete Button Error 500

**Cek log:**
```bash
docker exec formvaksin_app tail -f storage/logs/laravel.log
```

**Kemungkinan:** File tidak ada di storage (sudah dihapus manual).

**Solusi:** Code sudah handle dengan `exists()` check, tapi pastikan log tidak ada error lain.

### Problem 5: Cache Tidak Clear

**Force clear semua:**
```bash
docker exec formvaksin_app php artisan optimize:clear
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache
docker restart formvaksin_app
```

---

## 🎯 ONE-LINER UPDATE COMMAND

Copy-paste command ini untuk **update cepat** (setelah git pull):

```bash
cd /var/www/form-vaksin-RSI && \
git pull origin main && \
docker exec formvaksin_app rm -f public/storage && \
docker exec formvaksin_app php artisan storage:link && \
docker exec formvaksin_app chown -R www-data:www-data storage/ && \
docker exec formvaksin_app chmod -R 775 storage/ && \
docker exec formvaksin_app php artisan config:clear && \
docker exec formvaksin_app php artisan route:clear && \
docker exec formvaksin_app php artisan view:clear && \
docker exec formvaksin_app php artisan cache:clear && \
docker exec formvaksin_app php artisan migrate --force && \
docker exec formvaksin_app php artisan config:cache && \
docker exec formvaksin_app php artisan route:cache && \
docker exec formvaksin_app php artisan view:cache && \
docker restart formvaksin_app && \
echo "✅ Update selesai! Tunggu 5 detik lalu test di browser."
```

---

## 📝 CHECKLIST FINAL

Sebelum selesai, pastikan:

- [ ] Git pull berhasil tanpa conflict
- [ ] .env production punya `RECAPTCHA_SITE_KEY` dan `RECAPTCHA_SECRET_KEY`
- [ ] Domain VPS sudah terdaftar di Google reCAPTCHA Console
- [ ] Symbolic link `public/storage` mengarah ke path yang benar
- [ ] Permission storage sudah 775 dengan owner www-data
- [ ] Cache sudah di-clear dan di-cache ulang
- [ ] Container sudah di-restart
- [ ] **Foto KTP/Paspor terlihat** di browser
- [ ] **Delete button** muncul dan berfungsi
- [ ] **PDF Page 4** tampil dengan 2 kolom (KTP kiri, Paspor kanan)
- [ ] **reCAPTCHA checkbox** muncul di form permohonan
- [ ] **Validasi reCAPTCHA** bekerja (error jika tidak centang)
- [ ] Log Laravel tidak ada error critical

---

## 🚨 BACKUP SEBELUM UPDATE

**SANGAT DISARANKAN** backup dulu:

```bash
# Backup database
docker exec formvaksin_db mysqldump -u root -p form_vaksin_db > backup_$(date +%Y%m%d).sql

# Backup .env
cp .env .env.backup_$(date +%Y%m%d)

# Backup storage (opsional, kalau mau aman)
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/
```

---

## 📞 Support

Jika ada masalah:
1. Cek log Laravel: `docker exec formvaksin_app tail -f storage/logs/laravel.log`
2. Cek log Nginx: `docker logs formvaksin_nginx --tail 50`
3. Cek log PHP: `docker logs formvaksin_app --tail 50`

---

**Good luck! 🚀**
