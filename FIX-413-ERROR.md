# Fix Error 413 Content Too Large

## Masalah
Error 413 terjadi karena ukuran upload (15.47 MB) melebihi batas server (8 MB).

## Solusi
Sudah dibuat konfigurasi untuk meningkatkan batas upload menjadi **20MB**:

1. ✅ **Nginx**: `client_max_body_size 20M` (di `docker/nginx/nginx.conf`)
2. ✅ **PHP**: `upload_max_filesize = 20M` dan `post_max_size = 20M` (di `docker/php/php.ini`)
3. ✅ **Dockerfile**: Sudah di-update untuk copy konfigurasi PHP

## Cara Deploy ke VPS

### 1. Push perubahan ke GitHub
```bash
git add .
git commit -m "Fix error 413: Increase upload limit to 20MB"
git push origin main
```

### 2. Di VPS, pull perubahan
```bash
cd /var/www/form-vaksin-RSI
git pull origin main
```

### 3. Rebuild container dengan konfigurasi baru
```bash
# Stop container
docker compose down

# Rebuild container (PENTING: --no-cache untuk pastikan konfigurasi baru terpakai)
docker compose build --no-cache app

# Start container
docker compose up -d

# Tunggu 30 detik
sleep 30

# Cek status
docker compose ps
```

### 4. Verifikasi konfigurasi sudah terpakai
```bash
# Cek Nginx config
docker compose exec app cat /etc/nginx/http.d/default.conf | grep client_max_body_size
# Harusnya muncul: client_max_body_size 20M;

# Cek PHP config
docker compose exec app php -i | grep -E "upload_max_filesize|post_max_size"
# Harusnya muncul:
# upload_max_filesize => 20M => 20M
# post_max_size => 20M => 20M
```

### 5. Test upload
Coba upload file lagi, seharusnya tidak ada error 413.

## File yang Diubah

1. **docker/nginx/nginx.conf** - Nginx configuration dengan `client_max_body_size 20M`
2. **docker/php/php.ini** - PHP configuration dengan `upload_max_filesize = 20M` dan `post_max_size = 20M`
3. **docker/php/supervisord.conf** - Supervisor configuration
4. **Dockerfile** - Menambahkan copy PHP configuration

## Catatan

- Batas upload sekarang: **20MB** (cukup untuk upload beberapa file sekaligus)
- Jika masih perlu lebih besar, edit `docker/nginx/nginx.conf` dan `docker/php/php.ini`
- Setelah edit, **harus rebuild container** dengan `docker compose build --no-cache app`

## Troubleshooting

Jika masih error setelah deploy:

1. **Pastikan container sudah rebuild:**
   ```bash
   docker compose exec app php -i | grep upload_max_filesize
   ```

2. **Restart Nginx di dalam container:**
   ```bash
   docker compose exec app supervisorctl restart nginx
   ```

3. **Clear cache Laravel:**
   ```bash
   docker compose exec app php artisan config:clear
   docker compose exec app php artisan cache:clear
   ```

4. **Cek log Nginx:**
   ```bash
   docker compose logs app | grep -i "413\|too large"
   ```

