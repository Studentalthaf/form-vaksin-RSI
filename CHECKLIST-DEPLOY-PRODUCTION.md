# ✅ Checklist Deploy ke Production VPS

## Status: SIAP DEPLOY ✅

Semua file konfigurasi sudah ada dan benar. **TAPI** perlu di-deploy ke VPS dengan cara yang benar.

---

## 📋 File Konfigurasi yang Sudah Ada

- ✅ `docker/nginx/nginx.conf` - client_max_body_size 20M
- ✅ `docker/php/php.ini` - upload_max_filesize = 20M, post_max_size = 20M
- ✅ `docker/php/supervisord.conf` - Supervisor config
- ✅ `Dockerfile` - Sudah di-update untuk copy konfigurasi
- ✅ Validasi JavaScript - Support HEIC, validasi lengkap
- ✅ Custom error messages - Semua dalam bahasa Indonesia

---

## 🚀 Langkah Deploy (WAJIB DILAKUKAN!)

### Step 1: Commit & Push ke GitHub
```bash
# Di local komputer
git add .
git commit -m "Fix error 413: Support HEIC, fix signature pad iPhone, validasi lengkap"
git push origin main
```

### Step 2: Di VPS - Pull Perubahan
```bash
cd /var/www/form-vaksin-RSI
git pull origin main
```

### Step 3: Rebuild Container (PENTING! Jangan Skip!)
```bash
# Stop semua container
docker compose down

# Rebuild dengan --no-cache (WAJIB!)
docker compose build --no-cache app

# Start container
docker compose up -d

# Tunggu 30 detik
sleep 30
```

### Step 4: Verifikasi Konfigurasi
```bash
# Cek Nginx config (harusnya 20M)
docker compose exec app cat /etc/nginx/http.d/default.conf | grep client_max_body_size
# Output harus: client_max_body_size 20M;

# Cek PHP config (harusnya 20M)
docker compose exec app php -i | grep -E "upload_max_filesize|post_max_size"
# Output harus:
# upload_max_filesize => 20M => 20M
# post_max_size => 20M => 20M
```

### Step 5: Clear Cache Laravel
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
```

### Step 6: Test Upload
- Coba upload file 12MB+ (seharusnya tidak error 413)
- Coba upload file .heic dari iPhone
- Test signature pad di iPhone

---

## ⚠️ PENTING: Kenapa Masih Bisa Error?

**Error 413 akan TERUS TERJADI** jika:
1. ❌ Container belum di-rebuild dengan `--no-cache`
2. ❌ Konfigurasi lama masih ter-cache di container
3. ❌ File konfigurasi belum di-pull dari GitHub

**Error 413 akan HILANG** jika:
1. ✅ Container sudah di-rebuild dengan `--no-cache`
2. ✅ Konfigurasi baru sudah ter-apply (cek dengan Step 4)
3. ✅ Semua file sudah ter-pull dari GitHub

---

## 🔍 Cara Cek Apakah Konfigurasi Sudah Ter-apply

### Cek di VPS:
```bash
# 1. Cek apakah file konfigurasi ada
ls -la /var/www/form-vaksin-RSI/docker/nginx/nginx.conf
ls -la /var/www/form-vaksin-RSI/docker/php/php.ini

# 2. Cek isi file (harus ada 20M)
grep "client_max_body_size" /var/www/form-vaksin-RSI/docker/nginx/nginx.conf
grep "upload_max_filesize" /var/www/form-vaksin-RSI/docker/php/php.ini

# 3. Cek di dalam container (setelah rebuild)
docker compose exec app cat /etc/nginx/http.d/default.conf | grep client_max_body_size
docker compose exec app php -i | grep upload_max_filesize
```

---

## 📝 Quick Deploy Script (Copy-Paste)

```bash
# Di VPS, jalankan script ini:
cd /var/www/form-vaksin-RSI && \
git pull origin main && \
docker compose down && \
docker compose build --no-cache app && \
docker compose up -d && \
sleep 30 && \
docker compose exec app php artisan config:clear && \
docker compose exec app php artisan cache:clear && \
docker compose exec app php artisan view:clear && \
echo "✅ Deploy selesai! Cek konfigurasi:" && \
docker compose exec app php -i | grep upload_max_filesize
```

---

## ✅ Checklist Setelah Deploy

- [ ] File konfigurasi sudah ter-pull dari GitHub
- [ ] Container sudah di-rebuild dengan `--no-cache`
- [ ] Nginx config menunjukkan `client_max_body_size 20M`
- [ ] PHP config menunjukkan `upload_max_filesize = 20M`
- [ ] Test upload file 12MB+ berhasil (tidak error 413)
- [ ] Test upload file .heic berhasil
- [ ] Test signature pad di iPhone bisa digambar
- [ ] Validasi form bekerja (alert bahasa Indonesia muncul)

---

## 🎯 Kesimpulan

**JA, CODE SUDAH SIAP DEPLOY!** ✅

Tapi perlu:
1. ✅ Push ke GitHub
2. ✅ Pull di VPS
3. ✅ **Rebuild container dengan --no-cache** (PENTING!)
4. ✅ Verifikasi konfigurasi

**Setelah deploy dengan benar, error 413 TIDAK AKAN TERJADI LAGI!** 🎉

