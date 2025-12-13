# Langkah-langkah Fix Storage di VPS Production

Setelah pull perubahan dari repository, ikuti langkah-langkah berikut:

## 1. Rebuild Docker Container

```bash
cd /var/www/form-vaksin-RSI

# Rebuild container dengan no-cache untuk memastikan nginx config ter-update
docker compose build --no-cache app
```

## 2. Restart Container

```bash
# Stop dan start ulang container
docker compose down
docker compose up -d

# Atau restart saja
docker compose restart app
```

## 3. Tunggu Container Ready

```bash
# Tunggu beberapa detik agar container fully started
sleep 10

# Cek status container
docker compose ps
```

## 4. Restart Container (Nginx akan reload otomatis)

```bash
# Restart container untuk apply config baru
docker compose restart app

# Tunggu beberapa detik
sleep 10
```

**Catatan:** Jika supervisor tidak running, gunakan `docker compose restart app` untuk restart container (akan restart nginx juga).

## 5. Verifikasi Nginx Config

```bash
# Cek apakah location storage sudah di posisi yang benar (sebelum location /)
docker compose exec app cat /etc/nginx/http.d/default.conf | grep -B 2 -A 5 "location"
```

**Harusnya urutan:**
1. `location ~ /\.` (deny hidden files)
2. `location ~ ^/bootstrap/cache` (deny bootstrap cache)
3. `location /storage` (allow storage) ← **HARUS SEBELUM location /**
4. `location ~ \.php$` (PHP-FPM)
5. `location /` (catch-all) ← **HARUS DI AKHIR**

## 6. Test Akses Storage

```bash
# Test dengan file yang ada
docker compose exec app curl -I http://localhost/storage/foto-ktp/UOItznB135jpGJQa3hnm1lzN4IjzXgOPe9dsHD6D.jpg

# Harusnya return: HTTP/1.1 200 OK (bukan 403 Forbidden)
```

## 7. Clear Laravel Cache (Opsional)

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

## 8. Test di Browser

Buka halaman detail permohonan di browser dan cek apakah gambar sudah muncul.

---

## Troubleshooting

### Jika masih 403 Forbidden:

1. **Cek permissions:**
```bash
docker compose exec app sh -c "
    chown -R www-data:www-data /var/www/storage /var/www/public/storage && \
    chmod -R 755 /var/www/storage && \
    find /var/www/storage -type f -exec chmod 644 {} \; && \
    find /var/www/storage -type d -exec chmod 755 {} \;
"
```

2. **Cek nginx error log:**
```bash
docker compose exec app tail -50 /var/log/nginx/error.log
```

3. **Cek apakah file ada:**
```bash
docker compose exec app ls -la /var/www/storage/app/public/foto-ktp/
```

4. **Test read access sebagai www-data:**
```bash
docker compose exec app su -s /bin/sh www-data -c "test -r /var/www/storage/app/public/foto-ktp/UOItznB135jpGJQa3hnm1lzN4IjzXgOPe9dsHD6D.jpg && echo 'READABLE' || echo 'NOT READABLE'"
```

### Jika masih tidak muncul:

1. **Cek symlink:**
```bash
docker compose exec app ls -la /var/www/public/ | grep storage
# Harusnya: storage -> /var/www/storage/app/public
```

2. **Perbaiki symlink jika perlu:**
```bash
docker compose exec app sh -c "rm -rf /var/www/public/storage && php artisan storage:link"
```

3. **Cek Laravel log:**
```bash
docker compose exec app tail -50 /var/www/storage/logs/laravel.log
```

---

## Script Lengkap (Copy-Paste)

Jalankan semua perintah ini secara berurutan:

```bash
cd /var/www/form-vaksin-RSI

# 1. Pull perubahan terbaru
git pull

# 2. Rebuild container
docker compose build --no-cache app

# 3. Restart container
docker compose restart app

# 4. Tunggu container ready
sleep 10

# 5. Test
docker compose exec app curl -I http://localhost/storage/foto-ktp/UOItznB135jpGJQa3hnm1lzN4IjzXgOPe9dsHD6D.jpg

# Harusnya return: HTTP/1.1 200 OK

# 6. Clear cache (opsional)
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

---

**Catatan:** Setelah langkah-langkah ini, gambar seharusnya sudah muncul di production.

