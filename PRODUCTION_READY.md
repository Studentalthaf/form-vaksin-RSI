# ✅ PRODUCTION SETUP COMPLETE - READY TO DEPLOY

Semua file production sudah disiapkan dan siap untuk push ke GitHub.

---

## 📁 Files Created/Updated

### 1. Docker Configuration Files
- ✅ `Dockerfile` - All-in-one container (Nginx + PHP-FPM + Supervisor)
- ✅ `docker/nginx/nginx.conf` - Nginx config dengan `/storage` location
- ✅ `docker/php/supervisord.conf` - Supervisor untuk manage processes
- ✅ `docker/mysql/my.cnf` - MySQL optimization untuk VPS kecil
- ✅ `.dockerignore` - Optimize build size

### 2. Environment & Compose
- ✅ `.env.production` - Production environment (dengan reCAPTCHA keys)
- ✅ `docker-compose.yml` - Updated dengan path mysql config yang benar

### 3. Documentation
- ✅ `DEPLOYMENT_PRODUCTION.md` - Comprehensive deployment guide (langkah demi langkah)
- ✅ `QUICK_DEPLOY.md` - Quick reference commands untuk deployment cepat
- ✅ `RECAPTCHA_SETUP.md` - (sudah ada sebelumnya)
- ✅ `FIX_RECAPTCHA_LOCALHOST.md` - (sudah ada sebelumnya)
- ✅ `UPDATE_PRODUCTION.md` - (sudah ada sebelumnya)

---

## 🎯 What's Fixed

### Problem: Production Photos 404 Not Found
**Solution:** 
- ✅ Nginx config sekarang punya `location /storage` block
- ✅ Dockerfile membuat symbolic link dengan relative path (`../storage/app/public`)
- ✅ Permissions di-set dengan benar (www-data:www-data, 775)

### Problem: Container Unhealthy (Nginx not running)
**Solution:**
- ✅ Dockerfile install Nginx + Supervisor
- ✅ Supervisor config manage kedua Nginx dan PHP-FPM
- ✅ Nginx config di-copy ke path Alpine yang benar (`/etc/nginx/http.d/`)
- ✅ CMD di Dockerfile jalankan supervisord

### Problem: reCAPTCHA Missing in Production
**Solution:**
- ✅ `.env.production` sekarang include RECAPTCHA_SITE_KEY dan RECAPTCHA_SECRET_KEY
- ✅ Documentation lengkap cara register keys di Google

### Problem: Database Connection Issues
**Solution:**
- ✅ `.env.production` sudah set `DB_HOST=db` (bukan localhost)
- ✅ MySQL config optimized untuk VPS kecil (max_connections=50, buffer_pool=128M)

### Problem: Wrong Folder Structure
**Solution:**
- ✅ Dockerfile sekarang create folders: `ktp/`, `paspor/`, `photos/`, `signatures/`
- ✅ Symbolic link di-create otomatis saat build

---

## 🚀 Next Steps - WHAT YOU NEED TO DO

### STEP 1: Test Locally (OPTIONAL tapi RECOMMENDED)

Sebelum push ke GitHub, test dulu di lokal:

```powershell
# Di Windows (PowerShell)
cd c:\laragon\www\form-vaksin

# Copy .env.production ke .env untuk test
copy .env.production .env

# Edit .env: Ganti DB passwords dan reCAPTCHA keys jika perlu
notepad .env

# Build containers
docker-compose build --no-cache

# Start containers
docker-compose up -d

# Generate APP_KEY
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate --force

# Create symbolic link
docker-compose exec app php artisan storage:link

# Test di browser
# http://localhost:8080
# Pastikan:
# - Halaman terbuka
# - Upload foto KTP/Paspor
# - Foto terlihat (bukan 404)
# - reCAPTCHA muncul di form

# Jika test OK, stop containers
docker-compose down
```

### STEP 2: Commit & Push ke GitHub

```powershell
# Add semua file baru
git add .

# Commit dengan message yang jelas
git commit -m "Production Setup: Nginx + PHP-FPM + Supervisor dengan Storage Fix"

# Push ke GitHub
git push origin main
```

### STEP 3: Deploy ke VPS

SSH ke VPS dan ikuti salah satu guide:

**Option A: Comprehensive Guide (Recommended untuk first time)**
```bash
# Ikuti DEPLOYMENT_PRODUCTION.md step by step
# File location: form-vaksin/DEPLOYMENT_PRODUCTION.md
```

**Option B: Quick Deploy (Untuk yang sudah familiar)**
```bash
# Ikuti QUICK_DEPLOY.md
# File location: form-vaksin/QUICK_DEPLOY.md
```

**Command Summary:**
```bash
# 1. SSH ke VPS
ssh user@103.16.116.182

# 2. Hapus deployment lama
cd /var/www/form-vaksin-RSI
docker-compose down
docker rm -f formvaksin_app formvaksin_db 2>/dev/null || true
cd .. && rm -rf form-vaksin-RSI

# 3. Clone fresh dari GitHub
git clone https://github.com/YOUR_USERNAME/form-vaksin.git form-vaksin-RSI
cd form-vaksin-RSI

# 4. Setup .env
cp .env.production .env
nano .env  # Update passwords dan reCAPTCHA keys

# 5. Build & Run
docker-compose build --no-cache
docker-compose up -d

# 6. Setup Laravel
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link

# 7. Test di browser
# http://103.16.116.182:8080
```

---

## 🔍 Verification Checklist

Setelah deployment, pastikan semua ini berfungsi:

### Container Health
```bash
docker-compose ps
# formvaksin_app: Up (healthy) ✅
# formvaksin_db: Up (healthy) ✅
```

### Web Access
- [ ] Homepage terbuka: `http://103.16.116.182:8080`
- [ ] Tidak ada error 500
- [ ] CSS/JS ter-load dengan baik

### Form & Upload
- [ ] Form permohonan terbuka: `/permohonan/create`
- [ ] reCAPTCHA widget muncul ("I'm not a robot")
- [ ] Upload foto KTP berhasil
- [ ] Upload foto Paspor berhasil
- [ ] Foto terlihat di halaman detail (bukan 404)

### Storage Access
- [ ] Test URL: `http://103.16.116.182:8080/storage/ktp/test.jpg`
- [ ] Tidak 404 Not Found (jika file exists)
- [ ] Symbolic link benar: `docker-compose exec app ls -la /var/www/public/ | grep storage`

### Database
- [ ] Connection berhasil (tidak ada "Connection refused")
- [ ] Migrations berhasil
- [ ] Data bisa disimpan dan diambil

### PDF Export
- [ ] Generate PDF berhasil
- [ ] Page 4 show KTP dan Paspor side-by-side
- [ ] Foto terlihat di PDF (bukan broken image)

---

## 🐛 If Something Goes Wrong

### Container Unhealthy
```bash
# Lihat logs
docker-compose logs -f app

# Cek proses
docker-compose exec app ps aux

# Harus ada:
# - supervisord
# - nginx: master process
# - nginx: worker process
# - php-fpm: master process
# - php-fpm: pool www
```

### Photos Still 404
```bash
# Cek symbolic link
docker-compose exec app ls -la /var/www/public/ | grep storage
# Output: storage -> ../storage/app/public

# Cek Nginx config
docker-compose exec app cat /etc/nginx/http.d/default.conf | grep "location /storage"
# Harus ada block ini!

# Recreate symbolic link
docker-compose exec app rm /var/www/public/storage
docker-compose exec app php artisan storage:link

# Set permissions
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

### reCAPTCHA Not Working
```bash
# Cek keys di .env
cat .env | grep RECAPTCHA

# Pastikan production keys, bukan test keys
# Test keys: 6LeIxAcT...
# Production keys: 6LcGLwcs... (atau keys Anda sendiri)

# Register site di Google:
# https://www.google.com/recaptcha/admin
# Domain: 103.16.116.182

# Clear config cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

---

## 📞 Support Files

Jika butuh bantuan, lihat dokumentasi lengkap:

1. **DEPLOYMENT_PRODUCTION.md** - Step-by-step deployment guide
2. **QUICK_DEPLOY.md** - Quick command reference
3. **RECAPTCHA_SETUP.md** - Register reCAPTCHA keys
4. **FIX_RECAPTCHA_LOCALHOST.md** - Localhost testing

Atau lihat logs:
```bash
docker-compose logs -f app
docker-compose logs -f db
docker-compose exec app tail -f /var/log/nginx/error.log
```

---

## 🎉 Summary

**What Was Fixed:**
- ✅ Dockerfile sekarang build all-in-one container (Nginx + PHP-FPM + Supervisor)
- ✅ Nginx config punya `location /storage` untuk serve uploaded files
- ✅ Supervisor config manage kedua processes dengan benar
- ✅ Symbolic link menggunakan relative path (bukan absolute)
- ✅ Permissions di-set otomatis saat build
- ✅ MySQL config optimized untuk VPS kecil
- ✅ .env.production include reCAPTCHA keys
- ✅ Documentation lengkap untuk deployment

**What You Need to Do:**
1. (Optional) Test locally dengan `docker-compose up -d`
2. Commit & push semua changes ke GitHub
3. SSH ke VPS dan hapus deployment lama
4. Clone fresh dari GitHub
5. Run deployment commands (lihat DEPLOYMENT_PRODUCTION.md)
6. Verify semua fitur berfungsi

**Expected Result:**
- Application accessible di `http://103.16.116.182:8080`
- All containers healthy
- Photos display correctly (no 404)
- reCAPTCHA working
- PDF export dengan identity documents
- Delete functionality working

---

**🚀 Ready to deploy! Good luck!**
