# Panduan Deploy ke Production VPS

## Masalah: Setelah pull dari GitHub, perubahan belum terlihat

Ini terjadi karena **cache Laravel dan Opcache PHP** masih menyimpan versi lama.

## Solusi Lengkap (Step-by-Step)

### Metode 1: Menggunakan Script (Disarankan)

1. **Copy script `deploy-production.sh` ke VPS**

2. **Edit path project di script:**
   ```bash
   nano deploy-production.sh
   # Ganti: PROJECT_DIR="/path/to/form-vaksin"
   # Dengan path sebenarnya, contoh: PROJECT_DIR="/var/www/form-vaksin"
   ```

3. **Beri permission:**
   ```bash
   chmod +x deploy-production.sh
   ```

4. **Jalankan script:**
   ```bash
   ./deploy-production.sh
   ```

### Metode 2: Manual (Step-by-Step)

```bash
# 1. Masuk ke direktori project
cd /path/to/form-vaksin

# 2. Pull dari GitHub
git pull origin main

# 3. Stop container
docker compose down

# 4. Rebuild container (PENTING!)
docker compose build --no-cache app

# 5. Start container
docker compose up -d

# 6. Tunggu container siap (15 detik)
sleep 15

# 7. Install dependencies
docker exec formvaksin_app composer install --optimize-autoloader --no-dev --no-interaction

# 8. Run migrations
docker exec formvaksin_app php artisan migrate --force

# 9. CLEAR SEMUA CACHE (SANGAT PENTING!)
docker exec formvaksin_app php artisan view:clear
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan cache:clear
docker exec formvaksin_app php artisan route:clear

# 10. Clear Opcache PHP (PENTING untuk update kode!)
docker exec formvaksin_app php -r "if(function_exists('opcache_reset'))opcache_reset();"

# 11. Re-cache untuk production
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache

# 12. Set permissions
docker exec formvaksin_app chmod -R 775 storage bootstrap/cache
docker exec formvaksin_app chown -R www-data:www-data storage bootstrap/cache
```

### Metode 3: Quick Fix (Jika hanya update kode, tidak ada migration)

```bash
cd /path/to/form-vaksin
git pull origin main

# Restart container
docker compose restart app

# Tunggu 5 detik
sleep 5

# Clear cache
docker exec formvaksin_app php artisan view:clear
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan cache:clear

# Clear Opcache
docker exec formvaksin_app php -r "if(function_exists('opcache_reset'))opcache_reset();"

# Re-cache
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan view:cache
```

## Troubleshooting

### 1. Jika masih belum muncul perubahan:

```bash
# Cek apakah file sudah ter-update
docker exec formvaksin_app ls -la /var/www/app/Http/Controllers/PermohonanController.php
docker exec formvaksin_app head -30 /var/www/app/Http/Controllers/PermohonanController.php

# Jika file masih lama, force rebuild tanpa cache
docker compose down
docker compose build --no-cache app
docker compose up -d
```

### 2. Cek logs jika ada error:

```bash
# Logs container app
docker logs formvaksin_app -f

# Logs container database
docker logs formvaksin_db -f

# Logs Laravel
docker exec formvaksin_app tail -f /var/www/storage/logs/laravel.log
```

### 3. Verifikasi file di container:

```bash
# Cek apakah kode sudah update di container
docker exec formvaksin_app grep -A 5 "public function create" /var/www/app/Http/Controllers/PermohonanController.php

# Seharusnya muncul:
# $vaksins = \App\Models\Vaksin::where('aktif', true)
```

### 4. Verifikasi menu jenis vaksin:

```bash
# Cek apakah menu sudah ada di layout admin
docker exec formvaksin_app grep -i "Jenis Vaksin" /var/www/resources/views/layouts/admin.blade.php
```

### 5. Clear cache secara manual:

```bash
# Hapus semua file cache di storage
docker exec formvaksin_app rm -rf /var/www/storage/framework/views/*
docker exec formvaksin_app rm -rf /var/www/storage/framework/cache/*
docker exec formvaksin_app rm -rf /var/www/bootstrap/cache/*.php

# Clear lagi via artisan
docker exec formvaksin_app php artisan view:clear
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan cache:clear
```

## Checklist Setelah Deploy

- [ ] Container sudah running (`docker ps`)
- [ ] Cache sudah di-clear (view, config, cache, route)
- [ ] Opcache sudah di-reset
- [ ] Migrations sudah jalan (jika ada migration baru)
- [ ] Menu "Jenis Vaksin" muncul di admin panel
- [ ] Form permohonan menampilkan vaksin dari database
- [ ] Data vaksin sudah ada di database (cek via admin panel)

## Catatan Penting

1. **Rebuild container sangat penting** jika ada perubahan kode PHP/Blade
2. **Clear cache WAJIB** setelah setiap deploy
3. **Opcache reset** penting untuk update kode PHP
4. **Data vaksin** harus ditambahkan via admin panel (menu Jenis Vaksin)

## Quick Reference

```bash
# Deploy cepat (update kode saja)
cd /path/to/form-vaksin && \
git pull origin main && \
docker compose restart app && \
sleep 5 && \
docker exec formvaksin_app php artisan view:clear && \
docker exec formvaksin_app php artisan config:clear && \
docker exec formvaksin_app php -r "if(function_exists('opcache_reset'))opcache_reset();" && \
docker exec formvaksin_app php artisan config:cache && \
docker exec formvaksin_app php artisan view:cache
```

