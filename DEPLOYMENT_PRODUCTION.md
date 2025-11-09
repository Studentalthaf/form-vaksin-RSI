# 🚀 DEPLOYMENT GUIDE - Form Vaksin RSI (Production)

Panduan lengkap untuk deployment fresh start ke VPS menggunakan Docker.

---

## 📋 Prerequisites

Pastikan VPS Anda sudah memiliki:
- ✅ Docker & Docker Compose terinstall
- ✅ Git terinstall
- ✅ Port 8080 dan 33060 terbuka (firewall)
- ✅ Minimal 2GB RAM (recommended 4GB)
- ✅ Minimal 2 CPU cores

---

## 🗑️ STEP 1: Hapus Deployment Lama (WAJIB)

Jika ini adalah fresh start, hapus semua container dan data lama:

```bash
# SSH ke VPS
ssh user@103.16.116.182

# Masuk ke direktori project lama
cd /var/www/form-vaksin-RSI

# Stop semua container
docker-compose down

# Hapus semua container terkait (force)
docker rm -f formvaksin_app formvaksin_db formvaksin_nginx 2>/dev/null || true

# Hapus image lama (opsional, untuk rebuild fresh)
docker rmi $(docker images | grep formvaksin | awk '{print $3}') 2>/dev/null || true

# PENTING: Backup database jika ada data penting!
# docker exec formvaksin_db mysqldump -u root -prootpassword form_vaksin > backup.sql

# Hapus volume database (HATI-HATI: Data akan hilang!)
# Hanya lakukan jika Anda yakin ingin mulai dari nol
docker volume rm form-vaksin-rsi_dbdata 2>/dev/null || true

# Keluar dari direktori
cd ..

# Hapus direktori project lama
rm -rf form-vaksin-RSI

# Bersihkan docker (opsional, untuk free up space)
docker system prune -af --volumes
```

---

## 📥 STEP 2: Clone Repository dari GitHub

```bash
# Pastikan masih di /var/www/
cd /var/www/

# Clone repository terbaru
git clone https://github.com/YOUR_USERNAME/form-vaksin.git form-vaksin-RSI

# Masuk ke direktori
cd form-vaksin-RSI

# Cek branch yang aktif (pastikan main/master)
git branch
```

---

## 🔧 STEP 3: Setup Environment Production

```bash
# Copy file .env.production ke .env
cp .env.production .env

# Edit .env dengan nano/vim untuk update credentials jika perlu
nano .env

# Generate APP_KEY (WAJIB!)
# Karena belum ada PHP di host, kita akan generate setelah container jalan
# Atau bisa generate di lokal lalu paste ke .env
```

**Update nilai-nilai penting di `.env`:**
```env
APP_NAME="Form Vaksin RSI"
APP_ENV=production
APP_KEY=base64:GENERATE_SETELAH_CONTAINER_JALAN
APP_DEBUG=false
APP_URL=http://103.16.116.182:8080
APP_PORT=8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=GANTI_PASSWORD_INI_DENGAN_YANG_KUAT
DB_ROOT_PASSWORD=GANTI_PASSWORD_INI_JUGA

# reCAPTCHA Production Keys
# Register di https://www.google.com/recaptcha/admin
# Domain: 103.16.116.182 atau domain Anda
RECAPTCHA_SITE_KEY=YOUR_PRODUCTION_SITE_KEY
RECAPTCHA_SECRET_KEY=YOUR_PRODUCTION_SECRET_KEY
```

**Simpan file:** `Ctrl+O` lalu `Enter`, keluar: `Ctrl+X`

---

## 🐳 STEP 4: Build & Run Docker Containers

```bash
# Build image (akan install semua dependencies)
# Proses ini bisa memakan waktu 5-10 menit
docker-compose build --no-cache

# Start containers di background
docker-compose up -d

# Cek status containers (pastikan SEMUA healthy)
docker-compose ps

# Output yang diharapkan:
# NAME                 STATUS
# formvaksin_app       Up 30 seconds (healthy)
# formvaksin_db        Up 30 seconds (healthy)
```

**Jika container `formvaksin_app` masih "starting" atau "unhealthy":**
```bash
# Tunggu 1-2 menit untuk healthcheck
# Lihat logs untuk debug
docker-compose logs -f app

# Tekan Ctrl+C untuk keluar dari logs
```

---

## 🔑 STEP 5: Generate Application Key

```bash
# Generate APP_KEY (ini akan update .env secara otomatis)
docker-compose exec app php artisan key:generate

# Verify APP_KEY sudah ter-generate
cat .env | grep APP_KEY

# Output: APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
```

---

## 🗄️ STEP 6: Setup Database

```bash
# Run migrations (create tables)
docker-compose exec app php artisan migrate --force

# Seed data (jika ada seeders)
docker-compose exec app php artisan db:seed --force

# Verify database
docker-compose exec db mysql -uroot -prootpassword -e "USE form_vaksin; SHOW TABLES;"
```

---

## 🔗 STEP 7: Create Storage Symbolic Link

```bash
# Create symbolic link (sudah otomatis di Dockerfile, tapi cek lagi)
docker-compose exec app php artisan storage:link

# Verify symbolic link
docker-compose exec app ls -la /var/www/public/ | grep storage

# Output: storage -> ../storage/app/public
```

---

## ✅ STEP 8: Test Application

### Test 1: Akses Web Interface
```bash
# Di browser, buka:
http://103.16.116.182:8080
```

**Yang harus terlihat:**
- ✅ Halaman welcome Laravel atau homepage form vaksin
- ✅ Tidak ada error 500
- ✅ CSS dan JS ter-load dengan baik

### Test 2: Test Form Upload
```bash
# Buka halaman permohonan:
http://103.16.116.182:8080/permohonan/create

# Upload foto KTP dan Paspor
# Submit form
# Cek apakah foto terlihat di halaman detail
```

### Test 3: Test reCAPTCHA
```bash
# Di halaman form permohonan
# Pastikan widget "I'm not a robot" muncul
# Centang checkbox dan submit
# Jika gagal, cek RECAPTCHA_SITE_KEY dan RECAPTCHA_SECRET_KEY
```

### Test 4: Test Storage Files
```bash
# Ambil URL foto dari database atau halaman detail
# Contoh: http://103.16.116.182:8080/storage/ktp/xxx.jpg
# Paste di browser, pastikan foto terlihat (bukan 404)
```

---

## 🔍 STEP 9: Monitoring & Logs

### Lihat logs semua containers
```bash
docker-compose logs -f
```

### Lihat logs container tertentu
```bash
# App (Nginx + PHP-FPM)
docker-compose logs -f app

# Database
docker-compose logs -f db
```

### Lihat logs Nginx di dalam container
```bash
docker-compose exec app tail -f /var/log/nginx/access.log
docker-compose exec app tail -f /var/log/nginx/error.log
```

### Lihat logs Supervisor
```bash
docker-compose exec app tail -f /var/log/supervisor/supervisord.log
```

### Cek proses yang berjalan di dalam container
```bash
docker-compose exec app ps aux

# Output yang diharapkan:
# PID   USER     COMMAND
#   1   root     /usr/bin/supervisord
#   X   root     nginx: master process
#   X   nobody   nginx: worker process
#   X   root     php-fpm: master process
#   X   www-data php-fpm: pool www
```

---

## 🐛 Troubleshooting

### Problem 1: Container `formvaksin_app` unhealthy

**Gejala:**
```bash
docker-compose ps
# formvaksin_app   Up 5 minutes (unhealthy)
```

**Solusi:**
```bash
# 1. Cek logs
docker-compose logs app

# 2. Cek apakah Nginx dan PHP-FPM jalan
docker-compose exec app ps aux | grep nginx
docker-compose exec app ps aux | grep php-fpm

# 3. Restart container
docker-compose restart app

# 4. Jika masih gagal, rebuild
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Problem 2: Foto 404 Not Found

**Gejala:**
```
GET http://103.16.116.182:8080/storage/ktp/xxx.jpg 404 (Not Found)
```

**Solusi:**
```bash
# 1. Cek symbolic link
docker-compose exec app ls -la /var/www/public/ | grep storage
# Harus output: storage -> ../storage/app/public

# 2. Cek folder storage
docker-compose exec app ls -la /var/www/storage/app/public/

# 3. Cek Nginx config
docker-compose exec app cat /etc/nginx/http.d/default.conf | grep "location /storage"

# 4. Recreate symbolic link
docker-compose exec app rm /var/www/public/storage
docker-compose exec app ln -s /var/www/storage/app/public /var/www/public/storage

# 5. Set permissions
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

### Problem 3: Database connection refused

**Gejala:**
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solusi:**
```bash
# 1. Cek container db healthy
docker-compose ps

# 2. Cek DB_HOST di .env
cat .env | grep DB_HOST
# Harus: DB_HOST=db (bukan localhost!)

# 3. Test koneksi dari container app
docker-compose exec app ping -c 3 db

# 4. Restart database
docker-compose restart db
```

### Problem 4: reCAPTCHA tidak muncul atau selalu gagal

**Gejala:**
- Widget tidak tampil
- Error "Verifikasi reCAPTCHA gagal"

**Solusi:**
```bash
# 1. Cek keys di .env
cat .env | grep RECAPTCHA

# 2. Register site di Google reCAPTCHA Admin
# URL: https://www.google.com/recaptcha/admin
# Domain: 103.16.116.182 (atau domain Anda)

# 3. Update keys di .env
nano .env
# RECAPTCHA_SITE_KEY=YOUR_NEW_KEY
# RECAPTCHA_SECRET_KEY=YOUR_NEW_SECRET

# 4. Clear config cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

# 5. Restart container
docker-compose restart app
```

### Problem 5: Permission denied saat upload

**Gejala:**
```
failed to open stream: Permission denied
```

**Solusi:**
```bash
# Set permissions untuk storage
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage

# Set permissions untuk bootstrap/cache
docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/bootstrap/cache
```

---

## 🔄 Update Deployment (Git Pull)

Untuk update aplikasi setelah ada perubahan di GitHub:

```bash
# SSH ke VPS
ssh user@103.16.116.182
cd /var/www/form-vaksin-RSI

# Pull perubahan terbaru
git pull origin main

# Rebuild container jika ada perubahan Dockerfile/config
docker-compose build --no-cache

# Restart containers
docker-compose down
docker-compose up -d

# Run migrations jika ada perubahan database
docker-compose exec app php artisan migrate --force

# Clear cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

---

## 🛑 Stop & Remove Containers

```bash
# Stop containers (data tetap ada)
docker-compose stop

# Stop dan remove containers (data tetap ada di volume)
docker-compose down

# Stop, remove containers, dan HAPUS VOLUME (data hilang!)
docker-compose down -v
```

---

## 📊 Resource Monitoring

```bash
# Lihat penggunaan resource real-time
docker stats

# Lihat penggunaan disk
docker system df

# Lihat logs terakhir (last 100 lines)
docker-compose logs --tail=100

# Lihat logs sejak waktu tertentu
docker-compose logs --since="2024-01-01T00:00:00"
```

---

## 🔒 Security Checklist

- [ ] APP_DEBUG=false di production
- [ ] APP_KEY sudah di-generate
- [ ] Database password kuat (bukan default)
- [ ] reCAPTCHA keys production (bukan test keys)
- [ ] Firewall aktif (hanya port 8080, 33060, 22 terbuka)
- [ ] SSL/HTTPS aktif (jika punya domain)
- [ ] Regular backup database
- [ ] Update Docker images secara berkala

---

## 📞 Support

Jika mengalami masalah:
1. Cek section **Troubleshooting** di atas
2. Lihat logs: `docker-compose logs -f`
3. Cek healthcheck: `docker-compose ps`
4. Restart containers: `docker-compose restart`

---

**🎉 Selamat! Deployment berhasil!**

Aplikasi sekarang dapat diakses di: **http://103.16.116.182:8080**
