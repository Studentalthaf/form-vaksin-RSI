# Deploy Fix Error 413 ke VPS

## ⚠️ PENTING: Error 413 Masih Terjadi

Error 413 masih terjadi karena **konfigurasi di VPS belum di-update**. File konfigurasi sudah benar (20MB), tapi perlu di-deploy ke VPS.

## Langkah Deploy ke VPS

### 1. Push perubahan ke GitHub
```bash
git add .
git commit -m "Fix error 413: Support HEIC, fix signature pad iPhone, increase limit to 5MB"
git push origin main
```

### 2. Di VPS, pull perubahan
```bash
cd /var/www/form-vaksin-RSI
git pull origin main
```

### 3. Rebuild container dengan konfigurasi baru (PENTING!)
```bash
# Stop container
docker compose down

# Rebuild container dengan --no-cache untuk pastikan konfigurasi baru terpakai
docker compose build --no-cache app

# Start container
docker compose up -d

# Tunggu 30 detik untuk container siap
sleep 30

# Cek status
docker compose ps
```

### 4. Verifikasi konfigurasi sudah terpakai
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

### 5. Restart Nginx di dalam container (jika perlu)
```bash
docker compose exec app supervisorctl restart nginx
```

### 6. Clear cache Laravel
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
```

## Perubahan yang Sudah Dibuat

### 1. ✅ Support File HEIC (iPhone)
- **View**: Tambahkan `.heic,.heif` di `accept` attribute
- **Controller**: Tambahkan `heic,heif` di validasi `mimes`
- **Pesan**: Update menjadi "Format: JPG, PNG, PDF, atau HEIC (iPhone)"

### 2. ✅ Fix Signature Pad untuk iPhone
- Tambahkan `touchcancel` event
- Tambahkan `{ passive: false }` untuk semua touch events
- Tambahkan `e.stopPropagation()` untuk mencegah event bubbling
- Fix di 3 tempat:
  - `resources/views/permohonan/screening.blade.php` (signature pasien & keluarga)
  - `resources/views/dokter/pasien/show.blade.php` (signature dokter)

### 3. ✅ Increase Upload Limit
- **Nginx**: `client_max_body_size 20M`
- **PHP**: `upload_max_filesize = 20M`, `post_max_size = 20M`
- **Laravel**: Per file maksimal 5MB (total maksimal 20MB)

## Troubleshooting

### Jika masih error 413 setelah deploy:

1. **Pastikan container sudah rebuild:**
   ```bash
   docker compose exec app php -i | grep upload_max_filesize
   # Harusnya: upload_max_filesize => 20M => 20M
   ```

2. **Cek Nginx config:**
   ```bash
   docker compose exec app cat /etc/nginx/http.d/default.conf | grep client_max_body_size
   # Harusnya: client_max_body_size 20M;
   ```

3. **Restart semua service:**
   ```bash
   docker compose restart app
   sleep 10
   docker compose exec app supervisorctl restart nginx
   docker compose exec app supervisorctl restart php-fpm
   ```

4. **Cek log error:**
   ```bash
   docker compose logs app | grep -i "413\|too large"
   ```

### Jika signature pad masih tidak bisa di iPhone:

1. **Clear browser cache** di iPhone
2. **Test di Safari** (bukan Chrome di iOS)
3. **Pastikan JavaScript sudah load** (cek console di Safari Developer Tools)

## Checklist Setelah Deploy

- [ ] Container sudah rebuild dengan `--no-cache`
- [ ] Nginx config menunjukkan `client_max_body_size 20M`
- [ ] PHP config menunjukkan `upload_max_filesize = 20M`
- [ ] Test upload file 12MB berhasil (tidak error 413)
- [ ] Test upload file .heic dari iPhone berhasil
- [ ] Test signature pad di iPhone bisa digambar
- [ ] Cache Laravel sudah di-clear

## Catatan

- **File .heic** akan diterima, tapi perlu library tambahan untuk convert ke JPG/PNG jika diperlukan
- **Signature pad** sekarang support iPhone dengan touch events yang lebih baik
- **Upload limit** sekarang 20MB total, per file maksimal 5MB

